<?php
session_start();
include_once '../Controller/UserController.php';
include_once '../Config.php';

// 🔒 Verifica login
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginAccount.php");
    exit;
}

$Controller = new UserController($pdo);
$user = $Controller->findById($_SESSION['user_id']);
if (!$user) {
    echo "<h2 style='text-align:center;margin-top:50px;'>Erro ao carregar usuário.</h2>";
    exit;
}

$nome_arquivo_fotoperfil = $Controller->getFotoPerfil($user['nome_arquivo_fotoperfil'], __DIR__);

// 📌 Se Trainer criar aula
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_aula'])) {
    if ($user['tipo_de_user'] == 'trainer') {
        $data = $_POST['data'];
        $descricao = $_POST['descricao'];
        $comeco = $_POST['horario_comeco'];
        $termino = $_POST['horario_termino'];

        $stmt = $pdo->prepare("INSERT INTO aulas (id_trainer, data,horario_comeco,horario_termino, descricao) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user['id'], $data, $comeco, $termino, $descricao]);
    }
    header("Location: Aulas.php");
    exit;
}

// 📌 Se Admin deletar aula
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_aula'])) {
    if ($user['tipo_de_user'] == 'admin') {
        $id_aula = $_POST['id_aula'];
        $stmt = $pdo->prepare("DELETE FROM aulas WHERE id = ?");
        $stmt->execute([$id_aula]);
    }
    header("Location: Aulas.php");
    exit;
}

// 📌 Carregar aulas conforme tipo de usuário
if ($user['tipo_de_user'] == 'cliente') {

    if (!$user['trainer_id']) {
        $aulas = [];
    } else {
        $stmt = $pdo->prepare("SELECT aulas.*, users.nome_completo, users.nome_arquivo_fotoperfil
                               FROM aulas 
                               INNER JOIN users ON users.id = aulas.id_trainer 
                               WHERE id_trainer = ?");
        $stmt->execute([$user['trainer_id']]);
        $aulas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} elseif ($user['tipo_de_user'] == 'trainer') {

    $stmt = $pdo->prepare("SELECT aulas.*, users.nome_completo, users.nome_arquivo_fotoperfil
                           FROM aulas
                           INNER JOIN users ON users.id = aulas.id_trainer
                           WHERE id_trainer = ?");
    $stmt->execute([$user['id']]);
    $aulas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} elseif ($user['tipo_de_user'] == 'admin') {

    $stmt = $pdo->query("SELECT aulas.*, users.nome_completo, users.nome_arquivo_fotoperfil
                         FROM aulas
                         INNER JOIN users ON users.id = aulas.id_trainer
                         ORDER BY data DESC");
    $aulas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulas - Move On Fitness</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/basics.css">
    <link rel="stylesheet" type="text/css" href="font-awesome/css/all.min.css" />
    <style>
        body { background: #f5f5f5; }
        .admin-container { max-width: 1200px; margin: 50px auto; background: white; border-radius: 15px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: center; border-bottom: 1px solid #ddd; }
        th { background: #111; color: white; }
        tr:hover { background: #f1f1f1; }
        .btn { padding: 6px 10px; border-radius: 8px; border: none; cursor: pointer; }
        .btn-del { background:#c00; color:white; }
        .btn-add { background:#28a745; color:white; padding:8px 14px; margin-top:10px; }
        .btn-del:hover { background:#900; }
    </style>
</head>
<body>

<?php include __DIR__ . "/header.php"; ?>

<div class="admin-container">

    <h1 style="text-align:center;">Aulas</h1>

    <?php if ($user['tipo_de_user'] == 'cliente' && !$user['trainer_id']): ?>
        <p style="text-align:center; font-size:1.2rem; color:#c00; margin:20px 0;">
            ⚠️ Você ainda não possui um trainer vinculado.  
            Fale com a administração para ser direcionado a um personal trainer.
        </p>
    <?php endif; ?>


    <!-- FORMULÁRIO PARA TRAINER ADICIONAR AULAS -->
    <?php if ($user['tipo_de_user'] == 'trainer'): ?>

    <form method="POST" class="flex-column" style="gap:10px; width:300px; margin: 0 auto">
        <br>
        <h2 class="textalign-center">Criar Aula</h2>
        <div>
        <span for="data">Dia da aula:</span>
        <input type="date" name="data" id="data" required>
        </div>
        <div>
        <span for="horario_comeco">Horário de começo da aula:</span>
        <input type="time" name="horario_comeco" id="horario_comeco" required>
        </div>
        <div>
        <span for="horario_termino">Horário de término da aula:</span>
        <input type="time" name="horario_termino" id="horario_termino" required>
        </div>
        <textarea name="descricao" placeholder="Descrição da aula" required></textarea>
        <button type="submit" name="add_aula" class="btn">Adicionar Aula</button>
        <br>
    </form>
    <hr>
    <?php endif; ?>

    <h2 style="margin-top:20px;">Lista de Aulas</h2>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Trainer</th>
                <?php if ($user['tipo_de_user'] == 'admin'): ?>
                    <th>Ações</th>
                <?php endif; ?>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($aulas)): ?>
                <tr><td colspan="4">Nenhuma aula encontrada.</td></tr>
            <?php else: ?>
                <?php foreach ($aulas as $a): ?>
                <tr>
                    <td><?=date('d/m/Y', strtotime($a['data']))?>; das <?=$a['horario_comeco']?> às <?=$a['horario_termino']?> </td>
                    <td><?=htmlspecialchars($a['descricao'])?></td>
                    <td class="flex-row justify-center align-center gap30"><?=htmlspecialchars($a['nome_completo'])?> <img src='IMG/pfps/<?=$a['nome_arquivo_fotoperfil']?>' width='75' height='75' style="border-radius:100%"/></td>

                    <?php if ($user['tipo_de_user'] == 'admin'): ?>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_aula" value="<?=$a['id']?>">
                            <button type="submit" name="delete_aula" class="btn btn-del"
                                onclick="return confirm('Deseja excluir esta aula?')">
                                🗑 Excluir
                            </button>
                        </form>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?php include __DIR__ . "/footer.php"; ?>

</body>
</html>
