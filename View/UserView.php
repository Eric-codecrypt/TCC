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

$plano_id = $user['plano_id'];
if(isset($user['plano_id'])){
    $stmt = $pdo->query("SELECT * FROM planos WHERE id = $plano_id");
    $plano = $stmt->fetch(PDO::FETCH_ASSOC);
}
$mensalidade_id = $user['mensalidade_id'];
if(isset($user['mensalidade_id'])){
    $stmt = $pdo->query("SELECT * FROM mensalidades WHERE id = $mensalidade_id");
    $mensalidade = $stmt->fetch(PDO::FETCH_ASSOC);

    $status_plano = 'Ativo';
    if($mensalidade['status_pagamento'] == 'Pendente' && $mensalidade['status_pagamento'] == 'Atrasado'){
        $status_plano = 'Inativo';
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($primeiro_nome); ?> - Move On Fitness</title>
    <link rel="shortcut icon" href="IMG/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/basics.css">
    <link rel="stylesheet" type="text/css" href="font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>

<body>
    <?php include __DIR__ . "/header.php"; ?>
        <section class="user-sect flex-column gap30">
            <div class="flex-row gap10 flex-row flex-wrap-at-760 justify-center">
                <!-- <form class="pfp user-view" method="POST" action="../user-actions/process_edit_pfp.php" enctype="multipart/form-data">
                        <?php if (isset($user)): ?>
                            <img src="IMG/pfps/<?= $nome_arquivo_fotoperfil ?>">
                        <?php endif; ?>
                        <label for="foto_perfil">
                            <img src="IMG/mudarFotoPerfil.png" alt="">
                        </label>
                        <input type="file" name="foto_perfil" id="foto_perfil" onchange="this.form.submit()">
                </form> -->
                <div class="pfp user-view" >
                        <?php if (isset($user)): ?>
                            <img src="IMG/pfps/<?= $nome_arquivo_fotoperfil ?>">
                        <?php endif; ?>
                </div>
                <div class="grow-100">
                    <div class="height-100po info-text flex-column gap10">
                        <div>
                            <p>Nome:</p>
                            <h3><?=$nome_completo?></h3>
                        </div>
                        <div class="flex-row justify-between gap30 info-text-small wrap">
                            <?php if($user['CPF'] != null OR $user['CPF'] != ''):?>
                                <div>
                                    <p>CPF:</p>
                                    <h3><?=$user['CPF']?></h3>
                                </div>
                            <?php endif;?>
                            <div>
                                <p>Email:</p>
                                <h3><?=$user['email']?></h3>
                            </div>
                        </div>
                        <div class="flex-row justify-between gap30 info-text-small">
                            <?php if($user['celular'] != null OR $user['celular'] != ''):?>
                                <div>
                                    <p>Celular:</p>
                                    <h3><?=$cell?></h3>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="flex-row justify-between gap30 info-text-small">
                            <?php if($plano_id != null OR $plano_id != ''):?>
                                <div>
                                    <p>Plano:</p>
                                    <h3><?=$plano['nome_plano']?> (<?=$status_plano?>)</h3>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-column">
                <div class="flex-row wrap">
                    <a class="dia segunda user-view" href="EditUser.php">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i><p>Atualizar conta</p>
                    </a>
                    <?php if($plano_id != null OR $plano_id != ''):?>
                        <a class="dia terca user-view" href="Pagamento.php">
                            <img src="IMG/biceps.png" alt=""><p>Pagamento e Configurações do Plano</p>
                        </a>
                    <?php else:?>
                        <a class="dia terca user-view" href="plans.php">
                            <img src="IMG/biceps.png" alt=""><p>Planos</p>
                        </a>
                    <?php endif;?>
                </div>
                <div class="flex-row wrap">
                    <a class="dia quinta user-view" href="DeleteAccount.php">
                        <i class="fa-solid fa-delete-left"></i><p>Excluir conta</p>
                    </a>
                    <a class="dia sexta user-view" href="LeaveAccount.php">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i><p>Sair da conta</p>
                    </a>
                </div>
                <div class="width-100po flex-row">
                <?php if($user['info_treinamento'] != NULL):?>
                    <div class="dia sabado nohover grow-100 flex-column height-unset padding30 align-start">
                        <h1 class="width-100po">Informações do Formulário</h1>
                        <p class="textalign-left weight-low">
                            <?=$user['info_treinamento'];?>
                        </p>
                    </div>
                <?php endif;?>
                </div>
            </div>
        </section>
    <?php include __DIR__ . "/footer.php"; ?>
</body>

</html>