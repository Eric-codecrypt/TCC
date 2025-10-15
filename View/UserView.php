<?php
session_start();
include_once '../Controller/UserController.php';
include_once '../Config.php';

// Verificar se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginAccount.php");
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

$nome_completo = $user['nome_completo'];
$primeiro_nome = strtok($nome_completo, " ");

$nome_arquivo_fotoperfil = $Controller->getFotoPerfil($user['nome_arquivo_fotoperfil'], __DIR__);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($primeiro_nome); ?> - Move On Fitness</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/carousel.css">
    <link rel="stylesheet" type="text/css" href="font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>

<body>
    <?php include __DIR__ . "/header.php"; ?>
    <div>
        <form class="pfp" method="POST" action="../user-actions/process_edit_pfp.php" enctype="multipart/form-data">
            <?php if (isset($user)): ?>
                <img src="IMG/pfps/<?= $nome_arquivo_fotoperfil ?>">
            <?php endif; ?>
            <label for="foto_perfil">
                <img src="IMG/mudarFotoPerfil.png" alt="">
            </label>
            <input type="file" name="foto_perfil" id="foto_perfil" onchange="this.form.submit()">
        </form>
    </div>
    <?php include __DIR__ . "/footer.php"; ?>
</body>

</html>