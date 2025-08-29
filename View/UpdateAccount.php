<?php
include_once '../Controller/UserController.php';
include_once '../Config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: LoginAccount.php");
    exit;
}

$Controller = new UserController($pdo);
$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $errors = [];

    // Verificar se os campos foram preenchidos corretamente
    if (empty($username)) {
        $errors[] = "O nome de usuário é obrigatório.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Você deve fornecer um e-mail válido.";
    }
    if (!empty($password) && strlen($password) < 6) {
        $errors[] = "A senha deve ser opcional, mas ter pelo menos 6 caracteres se fornecida.";
    }

    if (empty($errors)) {
        try {
            // Atualizar usuário
            $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;
            $updated = $Controller->update($user_id, $username, $email, $hashedPassword);

            if ($updated) {
                $message = "Dados atualizados com sucesso!";
            } else {
                $errors[] = "Erro ao atualizar os dados. Verifique se o e-mail já está cadastrado.";
            }
        } catch (Exception $e) {
            $errors[] = "Erro inesperado: " . $e->getMessage();
        }
    }
}

$user = $Controller->findById($user_id); // Buscando dados do usuário atual para exibição
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Conta</title>
</head>
<body>
<h1>Atualizar Conta</h1>
<?php if (!empty($errors)): ?>
    <div>
        <?php foreach ($errors as $error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($message)): ?>
    <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form action="" method="POST">
    <label for="username">Usuário:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required><br>

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required><br>

    <label for="password">Nova Senha (opcional):</label>
    <input type="password" id="password" name="password"><br>

    <button type="submit">Salvar alterações</button>
</form>
</body>
</html>
