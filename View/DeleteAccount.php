<?php
session_start();

// Inicializa PDO corretamente e carrega controller
$pdo = require __DIR__ . '/../Config.php';
require_once __DIR__ . '/../Controller/UserController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: LoginAccount.php");
    exit;
}

$Controller = new UserController($pdo);
$user_id = $_SESSION['user_id'];
$message = "";
    $user = $Controller->findById($user_id);

// Exclusão via senha local (para quem não usa a opção de reautenticação Google)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    $user = $Controller->findById($user_id);

    if ($user && !empty($user['password']) && password_verify($password, $user['password'])) {
        $deleted = $Controller->delete($user_id);
        if ($deleted) {
            session_destroy();
            header("Location: ../index.php?msg=account_deleted");
            exit;
        } else {
            $message = "Erro ao excluir a conta. Tente novamente.";
        }
    } else {
        $message = "Senha incorreta. Conta não excluída.";
    }
}

// Mensagem de erro vinda por GET (ex.: erro na reautenticação com Google)
if (isset($_GET['error']) && $_GET['error'] !== '') {
    $message = $_GET['error'];
}

// Mapeia para a variável usada no HTML
$error_message = $message;
?>

<!DOCTYPE html>
<html lang="pt-b">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Conta</title>
    <link rel="shortcut icon" href="IMG/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
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
            border: 2px solid white;
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

        /* Botão de Login com Google */
        .google-login-btn {
            background-color: #ffffff;
            color: #3c4043;
            border: 1px solid #dadce0;
            padding: 10px 16px;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
        }

        .google-login-btn:hover {
            background-color: #f7f8f8;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

    </style>
</head>

<body>

    <div class="containexcl"><br><br><br>
        <div class="login-box">
                <h1>Excluir conta</h1>
            
                <?php if($user['google'] == 'Não'):?>    
                <h2 style="font-weight:200">Para excluir sua conta, confirme sua senha. Esta ação é irreversível.</h2>
             
                <?php if (!empty($error_message)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <form action="#" method="post">



                    <div class="password-container"> <input type="password" style="width:100%" id="password"
                            name="password" placeholder="Senha" required>
                        <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
                               background: none; border: none; cursor: pointer; font-size: 16px;">
                            <i class="fa-solid fa-eye" style="font-size:20px;  color: #c5c5c5ff;"></i>
                        </button>
                    </div>
                    <?php if (isset($errors)): ?>
                        <?php foreach ($errors as $er): ?>
                            <p><?= $er ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <button type="submit" class="account-btn">Excluir</button>
                </form>
                <?php else:?>
                <h2 style="font-weight:200">Para excluir sua conta, autentifique com sua conta do Google. Esta ação é irreversível.</h2>
                <a href="GoogleLogin.php?action=reauth_delete" class="google-login-btn" title="Confirmar com Google">
                    <img src="img/goog.png" class="goog" alt="Google logo" />
                    <span>Confirmar com Google</span>
                </a>
                <?php endif;?>
                <p class="signup-text"><a href="UserView.php">Voltar para o perfil</a></p>
            </div>
        </div>
    </div>


</body>

</html>