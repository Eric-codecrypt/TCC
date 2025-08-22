<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginAccount.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Dashboard</title>
</head>
<body>
<h1>Painel de Controle</h1>
<p>Bem-vindo ao painel de controle!</p>

<ul>
    <li><a href="UserView.php">Ver Conta</a></li>
    <li><a href="UpdateAccount.php">Atualizar Conta</a></li>
    <li><a href="DeleteAccount.php">Excluir Conta</a></li>
    <li><a href="LeaveAccount.php">Sair</a></li>
    <li><a href="Plans.php">Planos</a></li>
</ul>

<div class="cookie-backdrop" id="cookieBackdrop">
    <div class="cookie-modal" id="cookieModal">
        <h3>🍪 Preferencias de Cookie</h3>
        <p>
            Usamos cookies para personalizar sua experiência e analisar o tráfego. Ao clicar em "Aceitar todos", você concorda
            com o nosso uso de cookies. Você pode personalizar suas preferências clicando em "Gerenciar configurações".
        </p>
        <div class="cookie-buttons">
            <button class="accept" onclick="acceptCookies()">Aceitar tudo</button>
            <button class="settings" onclick="openSettings()">Gerenciar configurações</button>

        </div>
    </div>
</div>
<!-- Segunda Modal: Configurações Detalhadas -->
<div class="cookie-settings-modal" id="cookieSettingsModal">
    <div class="cookie-modal">
        <h3>🍪 Configurações de cookies</h3>
        <form id="cookieSettingsForm">
            <label>
                <input type="checkbox" checked disabled>
                <strong>
                    Cookies necessários</strong> (sempre ativo)
            </label>
            <br><br>
            <label>
                <input type="checkbox" id="performanceCookies" checked>
                Cookies de desempenho
            </label>
            <br><br>
            <label>
                <input type="checkbox" id="marketingCookies">
                Cookies de marketing
            </label>
        </form>
        <div class="cookie-buttons">
            <button class="accept" onclick="saveSettings()">Salve as configurações</button>
            <button class="settings" onclick="closeSettings()">Voltar</button>
        </div>
    </div>
</div>
<script src="script.js"></script>


</body>
</html>
