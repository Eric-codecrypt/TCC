<?php
session_start();
include_once '../Controller/UserController.php';
include_once '../Config.php';

// 🔒 Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginAccount.php");
    exit;
}
// 🔒 Busca usuário logado
$Controller = new UserController($pdo);
$user = $Controller->findById($_SESSION['user_id']);
if (!$user || $user['tipo_de_user'] !== 'admin') {
    echo "<h2 style='text-align:center;margin-top:50px;'>Acesso negado. Somente administradores podem acessar esta página.</h2>";
    exit;
}

$nome_arquivo_fotoperfil = $Controller->getFotoPerfil($user['nome_arquivo_fotoperfil'], __DIR__);

// 🧠 Buscar todos os usuários
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 🧑‍🏫 Buscar todos os trainers para o select
$stmtTrainers = $pdo->query("SELECT id, nome_completo FROM users WHERE tipo_de_user = 'trainer'");
$trainers = $stmtTrainers->fetchAll(PDO::FETCH_ASSOC);

// 🧩 Atualizar tipo de usuário ou trainer_id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id = $_POST['id'];

    if ($_POST['action'] === 'alterar_tipo') {
        $novoTipo = $_POST['novo_tipo'];
        if ($novoTipo !== 'admin') { // segurança: admin não vira outro tipo
            $pdo->prepare("UPDATE users SET tipo_de_user = ? WHERE id = ?")->execute([$novoTipo, $id]);
        }

    } elseif ($_POST['action'] === 'alterar_trainer') {
        $novoTrainer = $_POST['novo_trainer'] ?: null;
        $pdo->prepare("UPDATE users SET trainer_id = ? WHERE id = ?")->execute([$novoTrainer, $id]);

    } elseif ($_POST['action'] === 'excluir') {
        // segurança: não excluir admins
        $stmt = $pdo->prepare("SELECT tipo_de_user FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $tipo = $stmt->fetchColumn();
        if ($tipo !== 'admin') {
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
        }
    }

    header("Location: AdminUsuarios.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Usuários - Move On Fitness</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/basics.css">
    <link rel="stylesheet" type="text/css" href="font-awesome/css/all.min.css" />
    <style>
        body { background: #f5f5f5; }
        .admin-container { max-width: 1300px; margin: 50px auto; background: white; border-radius: 15px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: center; border-bottom: 1px solid #ddd; vertical-align: middle; }
        th { background: #111; color: white; }
        tr:hover { background: #f1f1f1; }
        .foto { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; }
        .btn { padding: 6px 10px; border: none; border-radius: 8px; cursor: pointer; font-size: 0.9rem; }
        .btn-edit { background: #007bff; color: white; }
        .btn-del { background: #c00; color: white; }
        .btn-edit:hover { background: #0056b3; }
        .btn-del:hover { background: #900; }
        select { padding: 6px; border-radius: 8px; border: 1px solid #ccc; }
        h1 { text-align: center; margin-bottom: 15px; }
        .user-role-admin { color: #007bff; font-weight: 700; }
        .user-role-cliente { color: #28a745; font-weight: 700; }
        .user-role-trainer { color: #ff9800; font-weight: 700; }
    </style>
</head>
<body>
    <?php include __DIR__ . "/header.php"; ?>

    <div class="admin-container">
        <h1>Gerenciar Usuários</h1>
        <p style="text-align:center;">Apenas administradores podem alterar ou excluir usuários.</p>

        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Celular</th>
                    <th>CPF</th>
                    <th>Tipo</th>
                    <th>Trainer</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td>
                            <?php if (!empty($u['nome_arquivo_fotoperfil']) && file_exists(__DIR__.'/IMG/pfps/'.$u['nome_arquivo_fotoperfil'])): ?>
                                <img src="IMG/pfps/<?=$u['nome_arquivo_fotoperfil']?>" class="foto" alt="foto">
                            <?php else: ?>
                                <img src="IMG/PFPpadrao.png" class="foto" alt="foto">
                            <?php endif; ?>
                        </td>
                        <td><?=htmlspecialchars($u['nome_completo'])?></td>
                        <td><?=htmlspecialchars($u['email'])?></td>
                        <td><?=htmlspecialchars($u['celular'])?></td>
                        <td><?=htmlspecialchars($u['CPF'])?></td>
                        <td>
                            <span class="user-role-<?=$u['tipo_de_user']?>"><?=htmlspecialchars($u['tipo_de_user'])?></span>
                        </td>
                        <td>
                            <?php if ($u['tipo_de_user'] === 'cliente'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?=$u['id']?>">
                                    <input type="hidden" name="action" value="alterar_trainer">
                                    <select name="novo_trainer" onchange="this.form.submit()">
                                        <option value="">Sem Trainer</option>
                                        <?php foreach ($trainers as $t): ?>
                                            <option value="<?=$t['id']?>" <?=($u['trainer_id'] == $t['id']) ? 'selected' : ''?>>
                                                <?=htmlspecialchars($t['nome_completo'])?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td><?=date('d/m/Y H:i', strtotime($u['created_at']))?></td>
                        <td>
                            <?php if ($u['tipo_de_user'] !== 'admin'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?=$u['id']?>">
                                    <input type="hidden" name="action" value="alterar_tipo">
                                    <select name="novo_tipo">
                                        <option value="cliente" <?=$u['tipo_de_user']=='cliente'?'selected':''?>>Cliente</option>
                                        <option value="trainer" <?=$u['tipo_de_user']=='trainer'?'selected':''?>>Trainer</option>
                                    </select>
                                    <button type="submit" class="btn btn-edit" title="Alterar tipo">
                                        <h2 class="fa fa-sync">📝</h2>
                                    </button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?=$u['id']?>">
                                    <input type="hidden" name="action" value="excluir">
                                    <button type="submit" class="btn btn-del" onclick="return confirm('Tem certeza que deseja excluir este usuário?')" title="Excluir">
                                        <h2 class="fa fa-trash">🗑</h2>
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="color:#555;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include __DIR__ . "/footer.php"; ?>
</body>
</html>
