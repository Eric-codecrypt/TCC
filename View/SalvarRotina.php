<?php
session_start();
include_once '../Config.php';
include_once '../Controller/UserController.php';

// Verifica se o treinador está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginAccount.php");
    exit;
}

$trainerId = $_SESSION['user_id'];

// Verifica se todos os dados necessários foram enviados
if (!isset($_POST['aluno_id'], $_POST['rotina_json'])) {
    echo "<script>alert('Dados incompletos!'); history.back();</script>";
    exit;
}

$alunoId = $_POST['aluno_id'];
$rotinaJson = $_POST['rotina_json'];

// Verifica se o aluno realmente pertence ao treinador logado
$stmt = $pdo->prepare("SELECT id, nome_completo FROM users WHERE id = ? AND trainer_id = ?");
$stmt->execute([$alunoId, $trainerId]);
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$aluno) {
    echo "<script>alert('Este aluno não está vinculado a você.'); history.back();</script>";
    exit;
}

// Tenta salvar a rotina
try {
    $stmt = $pdo->prepare("UPDATE users SET rotina_treinamento = ? WHERE id = ?");
    $stmt->execute([$rotinaJson, $alunoId]);

    echo "<script>
        alert('Rotina salva com sucesso para {$aluno['nome_completo']}!');
        window.location.href = 'CriarRotinaTreino.php';
    </script>";
    exit;

} catch (Exception $e) {
    echo "<script>
        alert('Erro ao salvar rotina: " . addslashes($e->getMessage()) . "');
        history.back();
    </script>";
    exit;
}
?>
