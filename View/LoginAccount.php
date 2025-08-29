<?php
session_start();
require_once __DIR__ . '/../Config.php';
require_once __DIR__ . '/../Controller/UserController.php';

$Controller = new UserController($pdo);
$error_message = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    var_dump($_POST);
    if (empty($email) || empty($password)) {
        $error_message = "Todos os campos são obrigatórios.";
    } else {
        // CORREÇÃO: Agora passando apenas email e password
        $user = $Controller->login($email, $password);

        if ($user && isset($user['id'])) {
            // Informações do usuário encontradas e senha verificada
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

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

.login-btn:hover {
    background-color: #1a65a3;
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
    background-image:  url(IMG/mulher.png);
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
    <div class="main half">
    <div class="img">
    </div>
    <div class="login-container">   
        <div class="login-box">
            <h1>Login</h1>
            <h2 style="font-weight:200">Bem-vindo de volta, faça login em sua conta</h2>
                <?php if (!empty($error_message)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
            <form action="#" method="post">
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email ou nome de usuário" required>
<<<<<<< HEAD
                
                <div class="password-container"><input type="password" name="senha" placeholder="Senha" required>
                <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button></div>
=======
                <input id="password" name="password" type="password" placeholder="Senha" required>
>>>>>>> 129b3ff3e90712ebd612fe7768818353398e4100
                <div class="checkbox">
                    <input type="checkbox" name="remember-password" id="remember-password"><p>Lembrar minha senha</p>
                </div> 
                <img src="img/or.png" alt="">
                <div class="goog2"><img src="img/goog.png" class="goog" alt=""></div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            <p class="signup-text">Não tem uma conta? <a href="CreateAccount.php">cadastre-se</a></p>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordField = document.getElementById('password');
        const passwordToggle = document.querySelector('.toggle-password');
        const emailField = document.getElementById('email');

        // Foco automático no campo de email
        if (emailField) {
            emailField.focus();

            // Prevenir o ícone de olho de receber foco ao navegar com Tab
            emailField.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' && !e.shiftKey) {
                    e.preventDefault();
                    passwordField.focus();
                }
            });
        }

        // Função para alternar a visibilidade da senha
        function togglePassword() {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordToggle.textContent = '◉'; // Olho fechado
                passwordToggle.setAttribute('aria-label', 'Ocultar senha');
                passwordToggle.classList.add('password-visible');
            } else {
                passwordField.type = 'password';
                passwordToggle.textContent = '◎'; // Olho aberto
                passwordToggle.setAttribute('aria-label', 'Mostrar senha');
                passwordToggle.classList.remove('password-visible');
            }
        }

        // Adicionar evento de clique ao botão de toggle
        if (passwordToggle) {
            passwordToggle.addEventListener('click', togglePassword);

            // Também permitir toggle com Enter/Space quando o botão estiver em foco
            passwordToggle.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    togglePassword();
                }
            });
        }

        // Navegação por teclado melhorada
        if (passwordField) {
            passwordField.addEventListener('keydown', function(e) {
                // Tab para ir para o botão de toggle
                if (e.key === 'Tab' && !e.shiftKey) {
                    e.preventDefault();
                    passwordToggle.focus();
                }
                // Shift+Tab para voltar para o email
                if (e.key === 'Tab' && e.shiftKey) {
                    e.preventDefault();
                    emailField.focus();
                }
            });
        }

        passwordToggle.addEventListener('keydown', function(e) {
            // Tab para ir para o botão de submit
            if (e.key === 'Tab' && !e.shiftKey) {
                e.preventDefault();
                document.querySelector('button[type="submit"]').focus();
            }
            // Shift+Tab para voltar para o campo de senha
            if (e.key === 'Tab' && e.shiftKey) {
                e.preventDefault();
                passwordField.focus();
            }
        });

        // Feedback visual para foco melhorado
        const style = document.createElement('style');
        style.textContent = `
            .toggle-password:focus {
                outline: 2px solid #667eea;
                outline-offset: 2px;
                border-radius: 3px;
            }

            .toggle-password.password-visible {
                color: #667eea;
            }

            .toggle-password:hover {
                color: #333;
                transform: scale(1.1);
                transition: transform 0.2s ease;
            }
        `;
        document.head.appendChild(style);

        // Prevenir submissão do formulário ao pressionar Enter no toggle
        passwordToggle.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.stopPropagation();
            }
        });

        // Focar no campo de email se houver erro (útil após tentativa falha)
        <?php if (!empty($error_message)): ?>
        if (emailField) {
            emailField.focus();
            emailField.select();
        }
        <?php endif; ?>
    });
</script>
</body>
</html>