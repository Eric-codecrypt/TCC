<?php
session_start();
// Conexão com o banco de dados
$pdo = include __DIR__ . '/../Config.php';

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

// Handler para assinatura de plano (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $response = ['success' => false, 'message' => ''];

    try {
        if (!isset($_SESSION['user_id'])) {
            $response['message'] = 'Você precisa estar logado para assinar um plano.';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        }

        $user_id = (int)$_SESSION['user_id'];
        $planId = isset($_POST['plan_id']) ? (int)$_POST['plan_id'] : 0;

        if ($planId <= 0) {
            $response['message'] = 'Plano inválido.';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Buscar informações do plano
        $stmt = $pdo->prepare('SELECT id, nome_plano, valor_mensal FROM planos WHERE id = ?');
        $stmt->execute([$planId]);
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$plan) {
            $response['message'] = 'Plano não encontrado.';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Transação: atualizar plano do usuário e criar uma mensalidade pendente
        $pdo->beginTransaction();

        // Atualiza o plano do usuário
        $stmtUp = $pdo->prepare('UPDATE users SET plano_id = ? WHERE id = ?');
        $stmtUp->execute([$planId, $user_id]);

        // Calcula a data de vencimento (em 30 dias)
        $dueDate = (new DateTime('now'))->modify('+30 days')->format('Y-m-d');

        // Cria a mensalidade pendente
        $stmtIns = $pdo->prepare("INSERT INTO mensalidades (user_id, data_vencimento, valor_cobrado, status_pagamento, data_pagamento) VALUES (?, ?, ?, 'Pendente', NULL)");
        $stmtIns->execute([$user_id, $dueDate, $plan['valor_mensal']]);

        $pdo->commit();

        // Resposta de sucesso
        $transactionId = 'TX' . strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
        $response = [
            'success' => true,
            'planName' => $plan['nome_plano'],
            'price' => 'R$ ' . number_format((float)$plan['valor_mensal'], 2, ',', '.'),
            'transactionId' => $transactionId,
        ];
    } catch (Throwable $e) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $response['message'] = 'Erro ao processar a assinatura. Tente novamente mais tarde.' . $e;
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// Prepara estrutura para o JS
$plansJs = [];
$imgs =[
    "",
    "bronze.png",
    "ouro.png",
    "diamond.png"
];
foreach ($planos as $pl) {
    $id = (string)$pl['id'];
    $name = $pl['nome_plano'];
    $priceStr = 'R$ ' . number_format((float)$pl['valor_mensal'], 2, ',', '.') . '/mês';
    $plansJs[$id] = [
        'name' => $name,
        'price' => $priceStr,
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia MoveOn - Planos e Pagamento</title>
    <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body>

    <?php include __DIR__."/header.php";?>    

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
                <?php endforeach; ?>
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
            <h2>Você tem certeza?</h2>
            <p>Tem certeza que quer assinar este plano? <br>
            se você clicar em sim, voce pode paga-lo a partir da sua página de usuário (você pode cancelar o plano depois)</p>

            <button class="btn" onclick="confirmAssinar()">Assinar plano</button>
            <button class="btn" onclick="cancelAssinar()">Não assinar</button>
        </div>
    </section>
    <!-- essa section é escondida como um "modal", ao clicar em "pagar agora" ele vai aparecer -->
    <section id="success-sect" class="hidden">
        <div class="success-message">
            <div class="success-icon">✓</div>
            <h2>Pagamento Realizado com Sucesso!</h2>
            <p>Obrigado por assinar o <span id="success-plan-name"></span>.</p>
            <p>Para pagar o plano, volte para a página de usuário e vá para a página de mensalidades</p>
            <button class="btn" onclick=" window.location.replace('dashboard.php')"">Voltar para a página de usuário</button>
        </div>
    </section>
    <form action="#" id="hidden-form" class="hidden">
        <input type="hidden" name="plan" value="0" id="inputhiddenplan">
    </form>
</div>

    <?php include __DIR__."/footer.php";?>

<script>
    // Dados dos planos vindos do banco
    const plans = <?= json_encode($plansJs, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;

    let selectedPlan = null;

    // Função para selecionar um plano
    function selectPlan(planId) {
        selectedPlan = String(planId);

        if (!plans[selectedPlan]) {
            alert('Plano inválido ou indisponível.');
            return;
        }

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