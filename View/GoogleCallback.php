<?php
session_start();
$pdo = require __DIR__ . '/../Config.php';
require_once __DIR__ . '/../Controller/UserController.php';

$Controller = new UserController($pdo);

// Valida parâmetros obrigatórios
if (!isset($_GET['code']) || !isset($_GET['state'])) {
    http_response_code(400);
    // Se a intenção era reautenticar para excluir, volte para a tela de exclusão com erro amigável
    if (!empty($_SESSION['oauth_action']) && $_SESSION['oauth_action'] === 'reauth_delete') {
        header('Location: DeleteAccount.php?error=' . urlencode('Falha na autenticação. Tente novamente.'));
        exit;
    }
    echo 'Parâmetros inválidos.';
    exit;
}

if (empty($_SESSION['oauth2state']) || $_GET['state'] !== $_SESSION['oauth2state']) {
    http_response_code(400);
    if (!empty($_SESSION['oauth_action']) && $_SESSION['oauth_action'] === 'reauth_delete') {
        header('Location: DeleteAccount.php?error=' . urlencode('Sessão expirada. Tente novamente.'));
        exit;
    }
    echo 'State inválido. Tente novamente.';
    exit;
}
// State só é usado uma vez
unset($_SESSION['oauth2state']);

if (empty($googleClientId) || empty($googleClientSecret) || empty($googleRedirectUri)) {
    http_response_code(500);
    if (!empty($oauthAction) && $oauthAction === 'reauth_delete') {
        header('Location: DeleteAccount.php?error=' . urlencode('Google OAuth não configurado.'));
        exit;
    }
    echo 'Google OAuth não configurado. Defina GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET e GOOGLE_REDIRECT_URI.';
    exit;
}

$code = $_GET['code'];
$oauthAction = $_SESSION['oauth_action'] ?? null; // e.g., 'reauth_delete'
if (isset($_SESSION['oauth_action'])) unset($_SESSION['oauth_action']);

// Troca o code por tokens
$tokenEndpoint = 'https://oauth2.googleapis.com/token';
$postFields = [
    'code' => $code,
    'client_id' => $googleClientId,
    'client_secret' => $googleClientSecret,
    'redirect_uri' => $googleRedirectUri,
    'grant_type' => 'authorization_code',
];

$ch = curl_init($tokenEndpoint);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr = curl_error($ch);
curl_close($ch);

if ($response === false || $httpCode >= 400) {
    http_response_code(500);
    if (!empty($oauthAction) && $oauthAction === 'reauth_delete') {
        header('Location: DeleteAccount.php?error=' . urlencode('Falha ao obter token do Google.'));
        exit;
    }
    echo 'Falha ao obter token do Google: ' . htmlspecialchars($curlErr ?: $response);
    exit;
}

$tokenData = json_decode($response, true);
$accessToken = $tokenData['access_token'] ?? null;

if (!$accessToken) {
    http_response_code(500);
    if (!empty($oauthAction) && $oauthAction === 'reauth_delete') {
        header('Location: DeleteAccount.php?error=' . urlencode('Resposta inválida do Google.'));
        exit;
    }
    echo 'Resposta inválida do token endpoint.';
    exit;
}

// Busca dados do usuário
$userInfoUrl = 'https://www.googleapis.com/oauth2/v3/userinfo';
$ch = curl_init($userInfoUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$userInfoResponse = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr = curl_error($ch);
curl_close($ch);

if ($userInfoResponse === false || $httpCode >= 400) {
    http_response_code(500);
    if (!empty($oauthAction) && $oauthAction === 'reauth_delete') {
        header('Location: DeleteAccount.php?error=' . urlencode('Falha ao obter dados do usuário do Google.'));
        exit;
    }
    echo 'Falha ao obter dados do usuário do Google: ' . htmlspecialchars($curlErr ?: $userInfoResponse);
    exit;
}

$userInfo = json_decode($userInfoResponse, true);
$email = $userInfo['email'] ?? '';
$name = $userInfo['name'] ?? ($userInfo['given_name'] ?? '');
$pictureUrl = $userInfo['picture'] ?? null;

if (!$email) {
    http_response_code(500);
    if (!empty($oauthAction) && $oauthAction === 'reauth_delete') {
        header('Location: DeleteAccount.php?error=' . urlencode('Não foi possível obter o e-mail da conta Google.'));
        exit;
    }
    echo 'Não foi possível obter o e-mail do Google.';
    exit;
}

// Fluxo especial: reautenticação para excluir a conta
if ($oauthAction === 'reauth_delete') {
    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        header('Location: DeleteAccount.php?error=' . urlencode('Sessão expirada. Faça login e tente novamente.'));
        exit;
    }
    $currentUserId = $_SESSION['user_id'];
    $currentUser = $Controller->findById($currentUserId);
    if (!$currentUser) {
        http_response_code(404);
        header('Location: DeleteAccount.php?error=' . urlencode('Usuário atual não encontrado.'));
        exit;
    }
    // Compara e-mails (case-insensitive)
    if (strcasecmp($currentUser['email'] ?? '', $email) !== 0) {
        http_response_code(403);
        header('Location: DeleteAccount.php?error=' . urlencode('A conta Google autenticada não corresponde ao usuário logado.'));
        exit;
    }
    // E-mails conferem: excluir a conta
    $deleted = $Controller->delete($currentUserId);
    if ($deleted) {
        session_destroy();
        header('Location: ../index.php?msg=account_deleted');
        exit;
    } else {
        http_response_code(500);
        header('Location: DeleteAccount.php?error=' . urlencode('Erro ao excluir a conta. Tente novamente.'));
        exit;
    }
}

// Fluxo normal: criar/obter usuário local e logar
$localUser = $Controller->createOrGetFromGoogle($name, $email);

if (!$localUser || !isset($localUser['id'])) {
    http_response_code(500);
    echo 'Erro ao criar/obter usuário local.';
    exit;
}

// Salvar/atualizar foto de perfil do Google (best-effort)
if (!empty($pictureUrl)) {
    try {
        // Baixa a imagem
        $ch = curl_init($pictureUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $imgData = curl_exec($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $statusImg = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($imgData !== false && $statusImg >= 200 && $statusImg < 300) {
            // Determina extensão
            $ext = 'jpg';
            if (is_string($contentType)) {
                if (stripos($contentType, 'png') !== false) $ext = 'png';
                elseif (stripos($contentType, 'jpeg') !== false || stripos($contentType, 'jpg') !== false) $ext = 'jpg';
                elseif (stripos($contentType, 'gif') !== false) $ext = 'gif';
                elseif (stripos($contentType, 'webp') !== false) $ext = 'webp';
            }
            $saveDir = __DIR__ . '/IMG/pfps';
            if (!is_dir($saveDir)) {
                @mkdir($saveDir, 0775, true);
            }
            $fileName = $localUser['id'] . '.' . $ext;
            $filePath = $saveDir . '/' . $fileName;
            if (file_put_contents($filePath, $imgData) !== false) {
                // Atualiza coluna no banco
                $Controller->updateFotoPerfil($localUser['id'], $fileName);
            }
        }
    } catch (Throwable $e) {
        // ignora erros de download/IO
    }
}

// Inicia sessão
$_SESSION['user_id'] = $localUser['id'];
$_SESSION['username'] = $localUser['username'] ?? ($name ?: $email);
$_SESSION['role'] = $localUser['tipo_de_user'] ?? 'cliente';

header('Location: Landing.php');
exit;
