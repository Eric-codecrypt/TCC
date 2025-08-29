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
                $errors[] = "Erro ao registrar o usuário. O e-mail ou usuário pode já estar cadastrados.";
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
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
body {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    background-color: #000000;
    color: #ffffff;
    overflow-x: hidden;
    font-family: 'Poppins', sans-serif;
}
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.login-box {
    width: 400px;
    height: 500px;
    padding: 40px;
    background-color:#6d6d6d;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
    text-align:left;
}

h2 {
    font-size: 1.1em;

}

form {
    display: flex;
    flex-direction: column;
}

input {
    background-color:#838383;
    border: none;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 15px;
    color: #ffffff;
    font-size: 16px;
}
select{
    background-color:#DAD0D0;
    border: none;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 15px;
    font-size: 16px;
}

input::placeholder {
    color: #383636;
}

.login-btn {
    background: linear-gradient(90deg,rgba(198, 72, 72, 1) 0%, rgba(150, 0, 3, 1) 100%);
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

.fb-login-btn {
    background-color:#e6e2e2;
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

.fb-login-btn:hover {
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

.main{
    display: flex;
    flex-direction: row;
    min-width: 100vw;
    min-height: 100vh;
}
.img {
    min-width: 35vw;
    height: 100vh;
    background-image:  url(IMG/homi.png);
    background-size: cover;
    background-position: 0% 90%;
    background-repeat: no-repeat;
}


@media (max-width: 992px){
    .img{
        min-width: 0;
        width: 0;
    }
}

.checkbox{
    display: flex;
    flex-direction: row;
}

.checkbox input{
    background-color: #9e0f11;
}
.half{

}
.goog{
    width: 10%;
    margin: 10px;
}
.goog2{
    display: flex;
    justify-content: center;
}

    </style>
        <div class="main half">

    <div class="login-container">   
        <div class="login-box">
            <h1>Cadastar</h1>
            <h2>Bem-vindo, Crie uma conta e desfrute.</h2>
            <form action="#" method="post">
                <input type="text" id="NomeCompleto" name="NomeCompleto" placeholder="Nome" required>
                <input type="email" id="email" name="email"  placeholder="Email" required>
                <input type="password" id="password" name="password" placeholder="Senha" required>
                <img src="img/or.png" alt="">
                <div class="goog2"><img src="img/goog.png" class="goog" alt=""></div>
                <button type="submit" class="login-btn">Cadastar</button>
            </form>
            <p class="signup-text"><a href="LoginAccount.php">Voltar para o login </a></p>
            </div>
                </div>
        <div class="img">
    </div>
        </div>

</body>
</html>
