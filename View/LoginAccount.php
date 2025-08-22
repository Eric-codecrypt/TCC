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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 10px;
            font-size: 28px;
        }

        p {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
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

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #444;
        }

        input[type="email"],
        input[type="password"] {
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
            transition: border-color 0.3s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
        }

        .password-container {
            position: relative;
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

        button[type="submit"] {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            color: #666;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 500px) {
            .login-container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <h1>Entrar no Sistema</h1>
    <p>Faça login para acessar o painel de controle.</p>

    <?php if (!empty($error_message)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label for="password">Senha:</label>
        <div class="password-container">
            <input type="password" id="password" name="password" required>
            <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
        </div>

        <button type="submit">Entrar</button>
    </form>

    <div class="register-link">
        Não tem uma conta? <a href="CreateAccount.php">Registre-se aqui</a>.
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