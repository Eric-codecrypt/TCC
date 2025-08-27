<!DOCTYPE html>
<!-- AQUI VAI UM HTML PARA MOSTRAR OS PLANOS, TRABALHO PRO FRONTEND -->

<!--siga as classes que eu fiz para o css pfvr, eu dividi cada coisinha então só precisa seguir -->
..
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia MoveOn - Planos e Pagamento</title>
<body>
<header>
    <div class="container">
        <h1>Academia MoveOn</h1>
        <p>Escolha o plano ideal para você</p>
    </div>
</header>

<div class="container">
    <section id="plans-section">
        <h2>Nossos Planos</h2>
        <div class="plans-container">
            <div class="plan-card">
                <h3>Plano Básico</h3>
                <div class="price">R$ 79,90/mês</div>
                <ul class="features">
                    <li>Acesso à academia</li>
                    <li>Área de musculação</li>
                    <li>Área de cardio</li>
                    <li>Vestiários</li>
                </ul>
                <button class="btn" onclick="selectPlan('basic')">Selecionar Plano</button>
            </div>

            <div class="plan-card">
                <h3>Plano Premium</h3>
                <div class="price">R$ 119,90/mês</div>
                <ul class="features">
                    <li>Tudo do plano básico</li>
                    <li>Aulas em grupo</li>
                    <li>Avaliação física</li>
                    <li>Área de relaxamento</li>
                </ul>
                <button class="btn" onclick="selectPlan('premium')">Selecionar Plano</button>
            </div>

            <div class="plan-card">
                <h3>Plano VIP</h3>
                <div class="price">R$ 159,90/mês</div>
                <ul class="features">
                    <li>Tudo do plano premium</li>
                    <li>Personal trainer</li>
                    <li>Massagem</li>
                    <li>Estacionamento gratuito</li>
                </ul>
                <button class="btn" onclick="selectPlan('vip')">Selecionar Plano</button>
            </div>
        </div>
    </section>

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

    <section id="success-section" class="hidden">
        <div class="success-message">
            <div class="success-icon">✓</div>
            <h2>Pagamento Realizado com Sucesso!</h2>
            <p>Obrigado por assinar o <span id="success-plan-name"></span>.</p>
            <p>ID da Transação: <span id="transaction-id"></span></p>
            <button class="btn" onclick="window.location.reload()">Voltar ao Início</button>
        </div>
    </section>
</div>


<script>
    // Dados dos planos
    const plans = {
        basic: {
            name: "Plano Básico",
            price: "R$ 79,90/mês"
        },
        premium: {
            name: "Plano Premium",
            price: "R$ 119,90/mês"
        },
        vip: {
            name: "Plano VIP",
            price: "R$ 159,90/mês"
        }
    };

    let selectedPlan = null;

    // Função para selecionar um plano
    function selectPlan(planId) {
        selectedPlan = planId;

        // Atualizar a seção de pagamento com as informações do plano
        document.getElementById('plan-name').textContent = plans[planId].name;
        document.getElementById('plan-price').textContent = plans[planId].price;

        // Mostrar a seção de pagamento e esconder a seção de planos
        document.getElementById('plans-section').classList.add('hidden');
        document.getElementById('payment-section').classList.remove('hidden');
    }

    // Manipular o envio do formulário de pagamento
    document.getElementById('payment-form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Simular processamento do pagamento
        processPayment();
    });

    // Função para processar o pagamento (simulação)
    function processPayment() {
        // Simular uma requisição à API de pagamento
        setTimeout(() => {
            // Gerar um ID de transação fictício
            const transactionId = 'TX' + Math.random().toString(36).substr(2, 9).toUpperCase();

            // Atualizar a seção de sucesso
            document.getElementById('success-plan-name').textContent = plans[selectedPlan].name;
            document.getElementById('transaction-id').textContent = transactionId;

            // Mostrar a seção de sucesso e esconder a seção de pagamento
            document.getElementById('payment-section').classList.add('hidden');
            document.getElementById('success-section').classList.remove('hidden');
        }, 2000);
    }
</script>
</body>
</html>