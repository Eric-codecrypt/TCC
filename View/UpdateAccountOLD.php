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
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <title>Atualizar Conta</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: #000000;
            color: #ffffff;
            overflow-x: hidden;
            font-family: sans-serif;
            min-height: 100vh;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .login-box {
            max-width: 400px;
            padding: 40px;
            background-color: #6d6d6d;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
            text-align: left;
            gap: 15px;
            display: flex;
            flex-direction: column;
            margin: 0 auto;
        }

        h2 {
            font-size: 1.1em;

        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input {
            background-color: #838383;
            border: none;
            border-radius: 5px;
            padding: 15px;
            color: #ffffff;
            font-size: 16px;
        }


        input::placeholder {
            color: #383636;
        }

        .account-btn {
            background: linear-gradient(135deg, rgba(198, 72, 72, 1) 0%, rgba(150, 0, 3, 1) 100%);
            border: none;
            padding: 15px;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 20px;
            transition: background-color 0.3s;
            border-radius: 15px;
        }

        .account-btn:hover {
            background-color: #1a65a3;
        }

        .fb-account-btn {
            background-color: #e6e2e2;
            width: 300px;
            border: 2px 2px solid white;
            border-radius: 30px;
            padding: 10px;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1em;
        }

        .fb-account-btn:hover {
            background-color: #334d84;
        }

        .signup-text {
            font-size: 18px;
            color: #ffffff;
            margin-top: 1px;
            text-align: center;
        }

        .signup-text a {
            color: #ffffff;
            text-decoration: none;
        }

        .signup-text a:hover {
            text-decoration: underline;
        }

        .main {
            display: flex;
            flex-direction: row;
            min-width: 100vw;
            min-height: 100vh;
        }

        .img {
            min-width: 35vw;
            height: 100vh;
            background-image: url(IMG/mulher.png);
            background-size: cover;
            background-position: 0% 90%;
            background-repeat: no-repeat;
        }

        input::placeholder {
            color: white;
        }



        @media (max-width: 992px) {
            .img {
                min-width: 0;
                width: 0;
            }
        }

        .checkbox {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 0.25rem
        }

        .checkbox input {
            background-color: #9e0f11;
            width: 20px;
            height: 20px;
            margin: 0 !important;
        }

        .half {}

        .goog {
            width: 10%;
            margin: 10px;
        }

        .goog2 {
            display: flex;
            justify-content: center;
        }

        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            border-left: 4px solid #c62828;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 14px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #777;
        }

        .password-container {
            position: relative;
        }
    </style>
</head>

<body>


    <div class="containexcl"><br><br><br>
        <div class="login-box">
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


                <div class="password-container">
                    <input type="text" style="width:100%" id="username" name="username" placeholder="Usuário"
                        value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                    <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
                               background: none; border: none; cursor: pointer; font-size: 16px;">
                        <i class="fa-regular fa-user" style=" font-size:20px;  color: #c5c5c5ff; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
                               background: none; border: none; "></i>
                    </button>
                </div>


                <div class="password-container">

                    <input type="email" style="width:100%" id="email" name="email" placeholder="E-mail"
                        value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required><br>
                    <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
                               background: none; border: none; cursor: pointer; font-size: 16px;">
                        <i class="fa-solid fa-envelope" style=" font-size:20px;  color: #c5c5c5ff; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
                               background: none; border: none; "></i>

                </div>


                <div class="password-container">
                    <input type="password" id="password" name="password" style="width:100%"
                        placeholder="Nova Senha (opcional)" required><br>
                    <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
                               background: none; border: none; cursor: pointer; font-size: 16px;">
                        <i class="fa-solid fa-eye" style="font-size:20px;  color: #c5c5c5ff;"></i>
                    </button>
                </div><br>
                <button type="submit" class="account-btn">Salvar alterações</button>

            </form>
            <p class="signup-text">Voltar para o <a href="CreateAccount.php">perfil</a></p>
        </div>
    </div>


</body>

</html>