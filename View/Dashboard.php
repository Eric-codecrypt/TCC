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
</ul>

























































</body>
</html>
