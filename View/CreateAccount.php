<?php
include_once '../Controller/UserController.php';
include_once '../Config.php';

$Controller = new UserController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    $errors = [];

    // Validações
    if (empty($username)) {
        $errors[] = "O nome de usuário é obrigatório.";
    }

    if (empty($password) || strlen($password) < 6) {
        $errors[] = "A senha é obrigatória e deve ter pelo menos 6 caracteres.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Você deve fornecer um e-mail válido.";
    }

    if (empty($errors)) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash da senha
            $registered = $Controller->register($username, $email, $hashedPassword);

            if ($registered) {
                header("Location: LoginAccount.php?success=1"); // Redireciona após sucesso
                exit;
            } else {
                $errors[] = "Erro ao registrar o usuário. O e-mail pode já estar cadastrado.";
            }
        } catch (Exception $e) {
            $errors[] = "Erro inesperado: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
</head>
<body>
<h1>Criar Conta</h1>
<?php if (!empty($errors)): ?>
    <div>
        <?php foreach ($errors as $error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<form action="" method="POST">
    <label for="username">Usuário:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
    <br>
    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
    <br>
    <label for="password">Senha:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Cadastrar</button>
</form>
<a href="LoginAccount.php">Já tenho uma conta</a>
</body>
</html>
