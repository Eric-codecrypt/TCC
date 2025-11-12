<?php
session_start();
// Conexão com o banco de dados
include_once __DIR__ . '\..\Config.php';
include_once __DIR__ . '\..\Controller\MensalidadeController.php';
include_once __DIR__ . '\..\Controller\UserController.php';

$planos = [];
try {
    if ($pdo) {
        $stmt = $pdo->query("SELECT * FROM planos ORDER BY valor_mensal ASC, id ASC");
        $planos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Throwable $e) {
    // Opcional: logar o erro
    $planos = [];
}

// Prepara estrutura para o JS
$imgs =[
    "",
    "bronze.png",
    "ouro.png",
    "diamond.png"
];

if(isset($_POST['simsimdeleta'])){
    $control = new UserController($pdo);
    $user = $control->findById($_SESSION['user_id']);

    if(isset($user['mensalidade_id'])){
        $mensalidadeController = new MensalidadeController($pdo);
        $mensalidadeController->cancelarPlano($user['mensalidade_id']);
    }
    
    header('Location: UserView.php');
}

if(isset($_POST['plan_id'])){
    $sql = "SELECT * FROM planos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['plan_id']]);
    $plano = $stmt->fetch(PDO::FETCH_ASSOC);

    $mensalidadeController = new MensalidadeController($pdo);

    $id_mensalidade = $mensalidadeController->newMensalidade($_SESSION['user_id'], $plano['valor_mensal']);

    $control = new UserController($pdo);
    $control->updatePlanoInfo($_SESSION['user_id'],$plano['id'],$id_mensalidade);

    header('Location: userview.php');
}


  if(isset($_SESSION['user_id']) && $_SESSION['user_id']){   
    $Controller = new UserController($pdo);
    $user_id = $_SESSION['user_id'];

    // Buscar dados do usuário
    $user = $Controller->findById($user_id);
    $nome_arquivo_fotoperfil = $Controller->getFotoPerfil($user['nome_arquivo_fotoperfil'], __DIR__);
  }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia MoveOn - Planos e Pagamento</title>
    <link rel="shortcut icon" href="IMG/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/basics.css">
        <link rel="stylesheet" type="text/css" href="font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body>

    <?php include __DIR__."/header.php";?>    
<?php if(isset($user['plano_id'])):?>
<div class="container">
    <section id="plans-sect">
        <h2>Nossos Planos</h2>
        <div class="pla3ns-container">
            <?php if (!empty($planos)): ?>
                <?php foreach ($planos as $pl): ?>
                    <div class="plan-card" onclick="selectPlan('<?= (int)$pl['id'] ?>')" id="plan<?=$pl['id']?>">
                        <h3><?= htmlspecialchars($pl['nome_plano']) ?></h3>
                        <div class="subcard">
                            <p><?= htmlspecialchars($pl['descricao'])?></p>
                            <img src="IMG/<?=$imgs[$pl["id"]]?>" alt="">
                        </div>
                        <div>
                            <div class="pricedesconto">R$ <?= number_format((float)$pl['valor_plano_antes_desconto'], 2, ',', '.') ?>/mês</div>
                            <div class="price">R$ <?= number_format((float)$pl['valor_mensal'], 2, ',', '.') ?>/mês</div>
                        </div>
                        <p class="adesao">
                            12 meses de fidelidade <br>
                            Adesão de R$ <?=number_format((float)$pl['valor_adesao'], 2, ',', '.')?>
                        </p>
                    </div>
                <?php endforeach;?>
            <?php else: ?>
                <div class="plan-card">
                    <h3>Nenhum plano disponível</h3>
                    <div class="price">-</div>
                    <ul class="features">
                        <li>Tente novamente mais tarde.</li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <!-- essa section é escondida quando seleciona um plano -->
    <section id="warning-sect" class="hidden">
        <div class="success-message">
            <?php if(isset($_SESSION['user_id'])):?>
                <h2>Você tem certeza?</h2>
                <p>Tem certeza que quer assinar este plano? <br>
                se você clicar em sim, voce pode paga-lo a partir da sua página de usuário (você pode cancelar o plano depois)</p>

                <button class="btn" onclick="confirmAssinar()">Assinar plano</button>
                <button class="btn" onclick="cancelAssinar()">Não assinar</button>
            <?php else:?>
                <h2>Você não pode fazer isto.</h2>
                <p>tens que fazer login para poder se assinar ao um plano.</p>
            <?php endif;?>
        </div>
    </section>
    <!-- essa section é escondida como um "modal", ao clicar em "pagar agora" ele vai aparecer -->
    <section id="success-sect" class="hidden">
        <div class="success-message">
            <div class="success-icon">✓</div>
            <h2>Pagamento Realizado com Sucesso!</h2>
            <p>Obrigado por assinar o <span id="success-plan-name"></span>.</p>
            <p>Para pagar o plano, volte para a página de usuário e vá para a página de mensalidades</p>
            <button class="btn" onclick="document.getElementById('hidden-form').submit()">Voltar para a página de usuário</button>
        </div>
    </section>
    <form action="#" id="hidden-form" class="hidden" method="post">
        <input type="hidden" name="plan_id" value="0" id="inputhiddenplan">
    </form>
</div>
<?php else:?>
    <form action="" method="post">
        <input type="hidden" name="simsimdeleta">
        <br>
        <p class="textalign-center">Para se inscrever em outros planos você tem que cancelar o seu plano de agora (Sem Reembolsos!)</p>
        <br>
        <div class="flex-row width-100po">
            <button type="submit" style="margin:auto; background-color:#B20000; border-radius: 1em; color:white; padding:10px; font-size:2em; border:none; cursor:pointer;">Cancelar Plano</button>
        </div>
        <br>
    </form>
<?php endif;?>
    <?php include __DIR__."/footer.php";?>

<script>
    // Função para selecionar um plano
    function selectPlan(planId) {

        document.getElementById('inputhiddenplan').value = planId;

        document.getElementById('warning-sect').classList.remove('hidden');
        document.getElementsByTagName('body')[0].classList.add('no-scroll');
    }

    function cancelAssinar(){
        document.getElementById('warning-sect').classList.add('hidden');
        document.getElementsByTagName('body')[0].classList.remove('no-scroll');
    }

    function confirmAssinar(){

        document.getElementById('warning-sect').classList.add('hidden');
        document.getElementById('success-sect').classList.remove('hidden');
        
        var id = document.getElementById('inputhiddenplan').value;

        var name = document.getElementById('plan'+id).children[0].innerHTML;
        document.getElementById('success-plan-name').innerHTML = name;
    }
    // Manipular o envio do formulário de pagamento
</script>


</body>
</html>