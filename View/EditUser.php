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

$cellddd = str_contains($user['celular'], '9')+1;
$cell = substr($user['celular'],0,$cellddd) . "-" . substr($user['celular'],$cellddd,100);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil de <?php echo htmlspecialchars($primeiro_nome); ?> - Move On Fitness </title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/basics.css">
    <link rel="stylesheet" type="text/css" href="font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>

<body>
    <?php include __DIR__ . "/header.php"; ?>
        <form class="user-sect flex-column gap30" form method="POST" action="../user-actions/process_edit_pfp.php" enctype="multipart/form-data">
            <?php if(isset($_COOKIE['edit_perfil_error_code'])):?>    
                <h1><?=$_COOKIE['edit_perfil_error_code']?></h1>
            <?php endif;?>
            <div class="flex-row gap10 flex-row flex-wrap-at-900 justify-center">
                <div class="pfp user-view">
                        <?php if (isset($user)): ?>
                            <img src="IMG/pfps/<?= $nome_arquivo_fotoperfil ?>">
                        <?php endif; ?>
                        <label for="foto_perfil">
                            <img src="IMG/mudarFotoPerfil.png" alt="">
                        </label>
                        <input type="file" name="foto_perfil" id="foto_perfil" onchange="this.form.submit()">
                </div>
                <div class="grow-100">
                    <div class="height-100po info-text flex-column gap10">
                        <div>
                            <p>Nome:</p>
                            <input type="text" name="nome_completo" value="<?=$nome_completo?>">
                        </div>
                        <div class="flex-row justify-between gap30 info-text-small wrap">
                            <div>
                                <p>CPF:</p>
                                <input type="text" name="CPF" value="<?=$user['CPF']?>">
                            </div>
                            <div>
                                <p>Email:</p>
                                <input type="text" name="email" value="<?=$user['email']?>">
                            </div>
                        </div>
                        <div class="flex-row justify-between gap30 info-text-small">
                                <div>
                                    <p>Celular:</p>
                                    <input type="text" name="celular" value="<?=$user['celular']?>">
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-row wrap">
                    <button class="dia segunda user-view" type="submit">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i><p>Confirmar atualizações</p>
                    </button>
                    <a class="dia terca user-view" href="plans.php">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i><p>Voltar para a página de usuário</p>
                    </a>
            </div>
        </form>
    <?php include __DIR__ . "/footer.php"; ?>
</body>

</html>