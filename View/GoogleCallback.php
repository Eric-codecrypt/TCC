<?php
session_start();
$pdo = require __DIR__ . '/../Config.php';
require_once __DIR__ . '/../Controller/UserController.php';

$Controller = new UserController($pdo);

// Valida parâmetros obrigatórios
if (!isset($_GET['code']) || !isset($_GET['state'])) {
    http_response_code(400);
    echo 'Parâmetros inválidos.';
    exit;
}

if (empty($_SESSION['oauth2state']) || $_GET['state'] !== $_SESSION['oauth2state']) {
    http_response_code(400);
    echo 'State inválido. Tente novamente.';
    exit;
}
// State só é usado uma vez
unset($_SESSION['oauth2state']);

if (empty($googleClientId) || empty($googleClientSecret) || empty($googleRedirectUri)) {
    http_response_code(500);
    echo 'Google OAuth não configurado. Defina GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET e GOOGLE_REDIRECT_URI.';
    exit;
}

$code = $_GET['code'];

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
    echo 'Falha ao obter token do Google: ' . htmlspecialchars($curlErr ?: $response);
    exit;
}

$tokenData = json_decode($response, true);
$accessToken = $tokenData['access_token'] ?? null;

if (!$accessToken) {
    http_response_code(500);
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
    echo 'Falha ao obter dados do usuário do Google: ' . htmlspecialchars($curlErr ?: $userInfoResponse);
    exit;
}

$userInfo = json_decode($userInfoResponse, true);
$email = $userInfo['email'] ?? '';
$name = $userInfo['name'] ?? ($userInfo['given_name'] ?? '');

if (!$email) {
    http_response_code(500);
    echo 'Não foi possível obter o e-mail do Google.';
    exit;
}

// Cria ou obtém usuário localmente
$user = $Controller->createOrGetFromGoogle($name, $email);

if (!$user || !isset($user['id'])) {
    http_response_code(500);
    echo 'Erro ao criar/obter usuário local.';
    exit;
}

// Inicia sessão
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'] ?? ($name ?: $email);
$_SESSION['role'] = $user['tipo_de_user'] ?? 'cliente';

header('Location: Dashboard.php');
exit;
