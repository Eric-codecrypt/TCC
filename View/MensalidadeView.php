<?php
// Inclui o arquivo de configuração para obter a conexão PDO
$pdo = include __DIR__ . '/../Config.php';

// Inclui o controller, que é o cérebro da nossa aplicação
require_once __DIR__ . '\..\Controller\MensalidadeController.php';

// Cria uma instância do controller com a conexão PDO
$controller = new MensalidadeController($pdo);

// --- LÓGICA DE PROCESSAMENTO DE AÇÕES ---
// Verifica se uma ação foi enviada via URL (ex: ?action=pay&id=5)
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Se a ação for 'pay', chama o método pay do controller
    if ($action === 'pay' && isset($_GET['id'])) {
        $mensalidadeId = intval($_GET['id']); // Converte o id para um inteiro por segurança
        $controller->pay($mensalidadeId);
    }
}

// --- LÓGICA DE BUSCA DE DADOS PARA EXIBIÇÃO ---
// Decide quais dados buscar com base em um filtro na URL (ex: ?filter=overdue)
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$mensalidades = [];

if ($filter === 'overdue') {
    $tituloDaPagina = "Mensalidades Atrasadas";
    $mensalidades = $controller->listOverdue();
} else {
    $tituloDaPagina = "Histórico de Mensalidades";
    $mensalidades = $controller->listAll();
}

// Lógica para exibir mensagens de status
$statusMessage = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'paid_success') {
        $statusMessage = '<div class="message success">Mensalidade marcada como paga com sucesso!</div>';
    } elseif ($_GET['status'] === 'paid_error') {
        $statusMessage = '<div class="message error">Ocorreu um erro ao processar o pagamento.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloDaPagina) ?> - Academia MoveOn</title>
    <style>
        /* Estilos Gerais */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        /* Container Principal */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        /* Cabeçalho e Navegação */
        header {
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }

        nav a {
            text-decoration: none;
            color: #3498db;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        nav a:hover {
            background-color: #ecf0f1;
        }

        /* Mensagens de Status */
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 500;
            border: 1px solid;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        /* Tabela de Dados */
        .mensalidade-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .mensalidade-table th, .mensalidade-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .mensalidade-table thead {
            background-color: #f2f2f2;
            font-size: 14px;
            text-transform: uppercase;
            color: #555;
        }

        /* Estilos dinâmicos para o status */
        .status-pago { background-color: #e8f5e9; } /* Verde claro */
        .status-pendente { background-color: #fff9c4; } /* Amarelo claro */
        .status-atrasado { background-color: #ffcdd2; } /* Vermelho claro */

        /* Botão de Ação */
        .action-button {
            display: inline-block;
            padding: 6px 12px;
            background-color: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .action-button:hover {
            background-color: #229954;
        }
        .no-results {
            text-align: center;
            padding: 40px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1><?= htmlspecialchars($tituloDaPagina) ?></h1>
        <nav>
            <a href="mensalidadeView.php?filter=all">Ver Todas</a>
            <a href="mensalidadeView.php?filter=overdue">Ver Apenas Atrasadas</a>
        </nav>
    </header>

    <main>
        <?= $statusMessage ?>

        <table class="mensalidade-table">
            <thead>
            <tr>
                <th>id</th>
                <th>Aluno</th>
                <th>Vencimento</th>
                <th>Valor (R$)</th>
                <th>Status</th>
                <th>Ação</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($mensalidades)): ?>
                <tr>
                    <td colspan="6" class="no-results">Nenhuma mensalidade encontrada.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($mensalidades as $mensalidade): ?>
                    <tr class="status-<?= strtolower(htmlspecialchars($mensalidade['status_pagamento'])) ?>">
                        <td><?= htmlspecialchars($mensalidade['id']) ?></td>
                        <td><?= htmlspecialchars($mensalidade['nome_completo']) ?></td>
                        <td><?= date('d/m/Y', strtotime($mensalidade['data_vencimento'])) ?></td>
                        <td><?= number_format($mensalidade['valor_cobrado'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($mensalidade['status_pagamento']) ?></td>
                        <td>
                            <?php if ($mensalidade['status_pagamento'] !== 'Pago'): ?>
                                <a href="mensalidadeView.php?action=pay&id=<?= $mensalidade['id'] ?>"
                                   class="action-button"
                                   onclick="return confirm('Tem certeza que deseja marcar esta mensalidade como PAGA?')">
                                    Marcar como Pago
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>

</body>
</html>