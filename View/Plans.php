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
</head>
<body>

    <header>
        <nav class="nav-bar">
            <div class="logo">
                <a href=""> <img src="IMG/logo.svg" alt="" class="logo-img"></a>

            </div>

            <div class="nav-list">
                <ul>
                    <li class="nav-item"> <a href="" class="nav-link">HOME</a></li>
                    <li>
                        <p>|</p>
                    </li>
                    <li class="nav-item"> <a href="" class="nav-link">Planos</a></li>
                    <li>
                        <p>|</p>
                    </li>
                    <li class="nav-item"> <a href="" class="nav-link">Dúvidas</a></li>
                    <li>
                        <p>|</p>
                    </li>
                    <li class="nav-item"> <a href="" class="nav-link">Treinos</a></li>
                    <li>
                        <p>|</p>
                    </li>
                    <li class="nav-item"> <a href="" class="nav-link">Pagamento</a></li>

                </ul>
            </div>

            <!-- Ícone do menu hamburger (visível só no mobile) -->
            <div class="mobile-menu-icon">
                <button onclick="menuShow()"><i class="fa-solid fa-bars"></i></button>
            </div>


        </nav>

        <!-- Menu mobile -->
        <div class="mobile-menu">
            <ul>
                <li class="nav-item"><a href="" class="nav-link">HOME</a></li>
                <li class="nav-item"><a href="" class="nav-link">Planos</a></li>
                <li class="nav-item"><a href="" class="nav-link">Dúvidas</a></li>
                <li class="nav-item"><a href="" class="nav-link">Treinos</a></li>
                <li class="nav-item"><a href="" class="nav-link">Pagamento</a></li>
            </ul>
        </div>


    </header>

<div class="container">
    <section id="plans-section">
        <h2>Nossos Planos</h2>
        <div class="pla3ns-container">
            <?php if (!empty($planos)): ?>
                <?php foreach ($planos as $pl): ?>
                    <div class="plan-card">
                        <h3><?= htmlspecialchars($pl['nome_plano']) ?></h3>
                        <p><?= htmlspecialchars($pl['descricao'])?></p>
                        <div class="pricedesconto">R$ <?= number_format((float)$pl['valor_plano_antes_desconto'], 2, ',', '.') ?>/mês</div>
                        <div class="price">R$ <?= number_format((float)$pl['valor_mensal'], 2, ',', '.') ?>/mês</div>

                        <button class="btn" onclick="selectPlan('<?= (int)$pl['id'] ?>')">Selecionar Plano</button>
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
    <section id="payment-section" class="hidden">
        <h2>Pagamento</h2>
        <div class="payment-form">
            <h3 id="selected-plan-title">Plano Selecionado: <span id="plan-name"></span></h3>
            <p id="plan-price" class="price"></p>

            <form id="payment-form">
                <div class="form-group">
                    <label for="card-number">Número do Cartão</label>
                    <input type="text" id="card-number" placeholder="1234 5678 9012 3456" required>
                </div>

                <div class="form-group">
                    <label for="card-name">Nome no Cartão</label>
                    <input type="text" id="card-name" placeholder="João Silva" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="card-expiry">Validade</label>
                        <input type="text" id="card-expiry" placeholder="MM/AA" required>
                    </div>

                    <div class="form-group">
                        <label for="card-cvv">CVV</label>
                        <input type="text" id="card-cvv" placeholder="123" required>
                    </div>
                </div>

                <button type="submit" class="btn">Pagar Agora</button>
            </form>
        </div>
    </section>
    <!-- essa section é escondida como um "modal", ao clicar em "pagar agora" ele vai aparecer -->
    <section id="success-section" class="hidden">
        <div class="success-message">
            <div class="success-icon">✓</div>
            <h2>Pagamento Realizado com Sucesso!</h2>
            <p>Obrigado por assinar o <span id="success-plan-name"></span>.</p>
            <p>id da Transação: <span id="transaction-id"></span></p>
            <button class="btn" onclick="window.location.reload()">Voltar ao Início</button>
        </div>
    </section>
</div>

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

        // Atualizar a seção de pagamento com as informações do plano
        document.getElementById('plan-name').textContent = plans[selectedPlan].name;
        document.getElementById('plan-price').textContent = plans[selectedPlan].price;

        // Mostrar a seção de pagamento e esconder a seção de planos
        document.getElementById('plans-section').classList.add('hidden');
        document.getElementById('payment-section').classList.remove('hidden');
    }

    // Manipular o envio do formulário de pagamento
    document.getElementById('payment-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!selectedPlan || !plans[selectedPlan]) {
            alert('Selecione um plano válido.');
            return;
        }

        const formData = new URLSearchParams();
        formData.append('plan_id', selectedPlan);

        try {
            const res = await fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            });
            const data = await res.json();
            if (!data.success) {
                alert(data.message || 'Não foi possível processar a assinatura.');
                return;
            }
            console.log(document.getElementById('transaction-id'));
            // Atualizar a seção de sucesso com dados do servidor
            document.getElementById('success-plan-name').textContent = data.planName;
            document.getElementById('transaction-id').textContent = data.transactionId;

            // Mostrar a seção de sucesso e esconder a seção de pagamento
            document.getElementById('payment-section').classList.add('hidden');
            document.getElementById('success-section').classList.remove('hidden');
        } catch (err) {
            alert(err);
        }
    });
</script>
</body>
</html>