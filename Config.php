<?php

$host = "localhost";
$db_name = "TCC";
$username = "root";
$password = "";
$pdo="";

// Google OAuth 2.0 configuration (preencha com suas credenciais)
// Dica: durante o desenvolvimento local use a URL abaixo como redirect:
//   http://localhost/TCC/View/GoogleCallback.php
$googleClientId = getenv('GOOGLE_CLIENT_ID') ?: '289594229636-alc2vporaua4evt0b4bkm7v3a7e0cm5f.apps.googleusercontent.com';
$googleClientSecret = getenv('GOOGLE_CLIENT_SECRET') ?: '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';
$googleRedirectUri = getenv('GOOGLE_REDIRECT_URI') ?: 'http://localhost/TCC/View/GoogleCallback.php';

try {
    $pdo = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo "Erro de conexão: " . $exception->getMessage();
}
return $pdo;
