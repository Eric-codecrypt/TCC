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
$nome_arquivo_fotoperfil = $Controller->getFotoPerfil($user['nome_arquivo_fotoperfil'], __DIR__);

if (!$user) {
    echo "Usuário não encontrado.";
    exit;
}

// http://localhost/tcc/View/UserView.php?viewtrainer
// http://localhost/tcc/View/UserView.php?viewastrainer=1
// http://localhost/tcc/View/UserView.php?viewadmin=1

$user_viewid = $_SESSION['user_id'];

if(!empty($_GET)){
    if(isset($_GET['viewtrainer'])){
        if($user['trainer_id'] != NULL && $user['tipo_de_user'] == 'cliente'){
            $user_viewid = $user['trainer_id'];
        }else{
            header('Location: UserView.php');
        }
    }

    if(isset($_GET['viewastrainer'])){
        $user_view = $Controller->findById($_GET['viewastrainer']);

        if($user_view['trainer_id'] == $user['id'] && $user['tipo_de_user'] == 'trainer'){
            $user_viewid = $_GET['viewastrainer'];
        }else{
            header('Location: UserView.php');
        }
    }

    if(isset($_GET['viewadmin'])){
        if($user['tipo_de_user'] == 'admin'){
            $user_viewid = $_GET['viewadmin'];
        }else{
            header('Location: UserView.php');
        }
    }

    if($user_viewid == $user_id){
    header('Location: UserView.php');
}
}


$user_view = $Controller->findById($user_viewid);

if($user_view == false){
    header('Location: UserView.php');
}

$nome_completo = $user_view['nome_completo'];
$primeiro_nome = strtok($nome_completo, " ");


$cellddd = str_contains($user_view['celular'], '9')+1;
$cell = substr($user_view['celular'],0,$cellddd) . "-" . substr($user_view['celular'],$cellddd,100);

$plano_id = $user_view['plano_id'];
if(isset($user_view['plano_id'])){
    $stmt = $pdo->query("SELECT * FROM planos WHERE id = $plano_id");
    $plano = $stmt->fetch(PDO::FETCH_ASSOC);
}
$mensalidade_id = $user_view['mensalidade_id'];
if(isset($user_view['mensalidade_id'])){
    $stmt = $pdo->query("SELECT * FROM mensalidades WHERE id = $mensalidade_id");
    $mensalidade = $stmt->fetch(PDO::FETCH_ASSOC);

    $status_plano = 'Ativo';
    if($mensalidade['status_pagamento'] == 'Pendente' OR $mensalidade['status_pagamento'] == 'Atrasado'){
        $status_plano = 'Inativo';
    }
}

$nome_arquivo_fotoperfiluser = $Controller->getFotoPerfil($user_view['nome_arquivo_fotoperfil'], __DIR__);


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
                <div class="pfp user-view" >
                        <?php if (isset($user_view)): ?>
                            <img src="IMG/pfps/<?= $nome_arquivo_fotoperfiluser ?>">
                        <?php endif; ?>
                </div>
                <div class="grow-100">
                    <div class="height-100po info-text flex-column gap10">
                        <div>
                            <p>Nome:</p>
                            <h3><?=$nome_completo?></h3>
                        </div>
                        <div class="flex-row justify-between gap30 info-text-small wrap">
                            <?php if(($user_view['CPF'] != null OR $user_view['CPF'] != '') &&( $user_view['id'] == $user['id'] OR $user['tipo_de_user'] == 'admin')):?>
                                <div>
                                    <p>CPF:</p>
                                    <h3><?=$user_view['CPF']?></h3>
                                </div>
                            <?php endif;?>
                            <div>
                                <p>Email:</p>
                                <h3><?=$user_view['email']?></h3>
                            </div>
                        </div>
                        <div class="flex-row justify-between gap30 info-text-small">
                            <?php if($user_view['celular'] != null OR $user_view['celular'] != ''):?>
                                <div>
                                    <p>Celular:</p>
                                    <h3><?=$cell?></h3>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="flex-row justify-between gap30 info-text-small">
                            <?php if($user_view['tipo_de_user'] == 'cliente' && ($plano_id != null OR $plano_id != '')):?>
                                <div>
                                    <p>Plano:</p>
                                    <h3><?=$plano['nome_plano']?> (<?=$status_plano?>)</h3>
                                </div>
                            <?php elseif($user['tipo_de_user'] != 'cliente'):?>
                                <div>
                                    <p>Nível de Acesso:</p>
                                    <h3><?=$user_view['tipo_de_user']?></h3>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-column">
                <?php if($user['id'] == $user_view['id']):?>
                <div class="flex-row wrap">
                    <a class="dia segunda user-view" href="EditUser.php">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i><p>Atualizar conta</p>
                    </a>
                    <?php if($user_view['tipo_de_user'] == 'cliente'):?>
                        <?php if($plano_id != null OR $plano_id != ''):?>
                            <a class="dia terca user-view" href="Pagamento.php">
                                <img src="IMG/biceps.png" alt=""><p>Pagamento e Configurações do Plano</p>
                            </a>
                        <?php else:?>
                            <a class="dia terca user-view" href="plans.php">
                                <img src="IMG/biceps.png" alt=""><p>Planos</p>
                            </a>
                        <?php endif;?>
                    <?php endif;?>
                    <?php if($user_view['tipo_de_user'] == 'admin'):?>
                        <a class="dia terca user-view" href="AdminUsuarios.php">
                            <i class="fa-solid fa-circle-user"></i><p>Administração de usuários</p>
                        </a>
                    <?php elseif($user_view['tipo_de_user'] == 'trainer'):?>
                        <a class="dia terca user-view" href="AdminUsuarios.php">
                            <i class="fa-solid fa-circle-user"></i><p>Ver Alunos</p>
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
                <?php if(($user_view['tipo_de_user'] == 'trainer') OR($user_view['rotina_treinamento'] != null && $status_plano == "Ativo")):?>
                <div class="flex-row wrap">
                    <?php if($user_view['tipo_de_user'] == 'trainer'):?>
                        <a class="dia segunda user-view" href="CriarRotinaTreino.php">
                            <i class="fa-solid fa-circle-user"></i><p>Criar Rotinas para clientes</p>
                        </a>
                    <?php endif;?>
                    <?php if($user_view['rotina_treinamento'] != null && $status_plano == "Ativo"):?>
                        <a class="dia terca user-view" href="treinos.php">
                            <img src="IMG/biceps.png" alt=""><p>Ver rotina de treino</p>
                        </a>
                    <?php endif;?>

                </div>
                <?php endif;?>
                <div class="flex-row wrap">
                <?php if($user_view['trainer_id'] != null && $status_plano == "Ativo"):?>
                        <a class="dia segunda user-view" href="UserView.php?viewtrainer">
                            <i class="fa-solid fa-circle-user"></i><p>Ver perfil do seu personal trainer</p>
                        </a>
                    <?php endif;?>
                <?php endif;?>
                <?php if($user_view['tipo_de_user'] == 'trainer'):?>
                    <a class="dia terca user-view" href="Aulas.php">
                        <i class="fa-solid fa-circle-user"></i><p>Agendar Aulas para clientes</p>
                    </a>
                <?php elseif($user_view['tipo_de_user'] == 'cliente' && $user_view['trainer_id'] != NULL ):?>
                    <a class="dia terca user-view" href="Aulas.php">
                        <img src="IMG/biceps.png" alt=""><p>Ver Aulas Agendadas por seu Trainer</p>
                    </a>
                <?php endif;?>
                </div>

                <div class="width-100po flex-row">
                <?php if($user_view['info_treinamento'] != NULL):?>
                    <div class="dia sabado nohover grow-100 flex-column height-unset padding30 align-start">
                        <h1 class="width-100po">Informações do Formulário</h1>
                        <p class="textalign-left weight-low">
                            <?=$user_view['info_treinamento'];?>
                        </p>
                    </div>
                <?php elseif(($user_view['tipo_de_user'] == 'cliente' && $user_view['mensalidade_id'] != NULL && $status_plano == "Ativo") && $user_id == $user_viewid):?>
                    <section id="warning-sect" style="z-index:10">
                        <div class="success-message">
                                            <h2>Preencha esse questionário</h2>
                                <p style="text-align: justify;">Olá! Seja bem vindo(a) a Move On Fitness, esperamos que você goste de nossos serviços.
                                para continuar, você terá que preencher um formulário simples que envolve seus objetivos com os exercícios, 
                                depois disso, um trainer será designado a você e fará uma rotina de treinos personalizada para você.</p>


                                <a href="questionarioTreino.php" style="color:white; text-decoration: none;"><button class="btn">Responder Formulário</button></a>
                        </div>
                    </section>
                <?php endif;?>
                </div>
            </div>
        </section>
    <?php include __DIR__ . "/footer.php"; ?>
</body>

</html>