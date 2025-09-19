<?php
session_start();
include_once '../Controller/UserController.php';
include_once '../Config.php';

// Verificar se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$Controller = new UserController($pdo);
$user_id = $_SESSION['user_id'];

// Buscar dados do usuário
$user = $Controller->findById($user_id);

if (!$user) {
    echo "Usuário não encontrado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visão do Usuário</title>
</head>
<body>
<h1>Bem-vindo, <?php echo htmlspecialchars($user['username']); ?>!</h1>
<ul>
    <li><strong>Usuário:</strong> <?php echo htmlspecialchars($user['username']); ?></li>
    <li><strong>E-mail:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
</ul>
<!-- modal para alterar informações -->
<h3>-informações adicionais-</h3>
<h4>CPF:</h4><input type="text" name="cpf" value="<?php echo htmlspecialchars($user['cpf']); ?>"><br>
<h4>Telefone:</h4><input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"
<a href="UpdateAccount.php">Atualizar Conta</a><br>
<a href="DeleteAccount.php">Excluir Conta</a><br>
<a href="Dashboard.php">Ir para o Dashboard</a><br>
<a href="MensalidadeView.php">Mensalidades</a>
<a href="LeaveAccount.php">Sair</a>
</body>
</html>
