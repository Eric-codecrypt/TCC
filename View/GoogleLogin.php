<?php
session_start();
require_once __DIR__ . '/../Config.php';

// Verifica se as credenciais do Google estão configuradas
if (empty($googleClientId) || empty($googleClientSecret) || empty($googleRedirectUri)) {
    http_response_code(500);
    echo 'Google OAuth não configurado. Defina GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET e GOOGLE_REDIRECT_URI.';
    exit;
}

// Ação opcional do fluxo (ex.: reautenticação para exclusão da conta)
$action = isset($_GET['action']) ? trim($_GET['action']) : '';
if ($action) {
    $_SESSION['oauth_action'] = $action;
}

// Gera e armazena o parâmetro state para proteção CSRF
$state = bin2hex(random_bytes(16));
$_SESSION['oauth2state'] = $state;

// Prompt diferente para reautenticação: pedir escolha de conta e consentimento
$prompt = ($action === 'reauth_delete') ? 'select_account consent' : 'select_account';

$params = [
    'client_id' => $googleClientId,
    'redirect_uri' => $googleRedirectUri,
    'response_type' => 'code',
    'scope' => 'openid email profile',
    'state' => $state,
    'access_type' => 'offline',
    'prompt' => $prompt,
];

$authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);

header('Location: ' . $authUrl);
exit;

