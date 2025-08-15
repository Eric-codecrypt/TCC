<?php
session_start();
require_once __DIR__ . '/../Config.php';
require_once __DIR__ . '/../Controller/UserController.php';

$Controller = new UserController($pdo); // Instancia o controlador
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error_message = "Todos os campos são obrigatórios.";
    } else {
        // Buscar usuário no banco de dados e verificar a senha
        $user = $Controller->login(null, $email, $password);

        if ($user) {
            // Informações do usuário encontradas e senha verificada
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Salva o nível de acesso (admin ou user)

            if ($_SESSION['role'] !== 'admin') {
                header("Location: Dashboard.php");
                exit;
            }

            header("Location: Dashboard.php");
            exit;
        } else {
            $error_message = "E-mail ou senha incorretos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<h1>Entrar no Sistema</h1>
<p>Faça login para acessar o painel de controle.</p>

<?php if (!empty($error_message)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<form action="" method="POST">
    <label for="email">E-mail:</label><br>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required><br><br>

    <label for="password">Senha:</label><br>
    <div class="password-container">
        <input type="password" id="password" name="password" required>
        <button type="button" class="toggle-password" onclick="togglePassword()">•</button>
    </div>
    <br><br>

    <button type="submit">Entrar</button>
</form>

<p>Não tem uma conta? <a href="CreateAccount.php">Registre-se aqui</a>.</p>

<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const passwordToggle = document.querySelector('.toggle-password');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordToggle.textContent = '–'; // Mostra "–" quando a senha está visível
        } else {
            passwordField.type = 'password';
            passwordToggle.textContent = '•'; // Mostra "•" quando a senha está oculta
        }
    }
</script>
</body>
</html>