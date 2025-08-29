<?php
include_once 'C:/Turma2/xampp/htdocs/Projeto-de-vida/backend/Controller/UserController.php';
include_once 'C:/Turma2/xampp/htdocs/Projeto-de-vida/config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: LoginAccount.php");
    exit;
}

$Controller = new UserController($pdo);
$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    // Buscar dados do usuário no banco
    $user = $Controller->findById($user_id); // Esse método precisa existir no seu UserController

    if ($user && password_verify($password, $user['password'])) {
        $deleted = $Controller->delete($user_id);
        if ($deleted) {
            session_destroy();
            header("Location: index.php?msg=account_deleted");
            exit;
        } else {
            $message = "Erro ao excluir a conta. Tente novamente.";
        }
    } else {
        $message = "Senha incorreta. Conta não excluída.";
    }
}
?>


<h2>Excluir Conta</h2>
<p>Para excluir sua conta, confirme sua senha. Esta ação é irreversível.</p>

<?php if ($message): ?>
    <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST">
    <label for="password">Senha:</label><br>
    <input type="password" name="password" id="password" required><br><br>
    <button type="submit">Excluir minha conta</button>
</form>