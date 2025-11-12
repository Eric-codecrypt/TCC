<?php
session_start();
include_once '../Controller/UserController.php';
include_once '../Config.php';

// Verificar login
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginAccount.php");
    exit;
}

$Controller = new UserController($pdo);
$user = $Controller->findById($_SESSION['user_id']);
if (!$user) {
    echo "Usuário não encontrado.";
    exit;
}
$nome_arquivo_fotoperfil = $Controller->getFotoPerfil($user['nome_arquivo_fotoperfil'], __DIR__);

// Buscar alunos do treinador logado
$stmt = $pdo->prepare("SELECT id, nome_completo, email FROM users WHERE trainer_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar exercícios disponíveis
$stmt = $pdo->query("SELECT * FROM exercicios");
$exercicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montar Rotina - Move On Fitness</title>
    <link rel="shortcut icon" href="IMG/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/basics.css">
    <link rel="stylesheet" type="text/css" href="font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <style>
        .rotina-container { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .dia-rotina { background: #fff; border-radius: 20px; padding: 15px; width: 300px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .dia-rotina h2 { text-align: center; margin-bottom: 10px; }
        .exercicio-item { display: flex; align-items: center; gap: 10px; border: 1px solid #ccc; border-radius: 10px; padding: 5px 10px; margin-bottom: 10px; flex-wrap: wrap; }
        .exercicio-item img { width: 100px; height: 100px; border-radius: 10px; object-fit: contain; }
        .lista-exercicios { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 20px; }
        .exercicio-card { width: 160px; border-radius: 15px; background: #fff; box-shadow: 0 1px 5px rgba(0,0,0,0.1); cursor: pointer; transition: transform 0.2s; }
        .exercicio-card:hover { transform: scale(1.05); }
        .exercicio-card img { width: 100%; height: 100px; object-fit: contain; border-radius: 15px 15px 0 0; }
        .exercicio-card p { text-align: center; padding: 5px; font-weight: 600; }
        .btn-salvar { margin-top: 30px; background: #111; color: white; padding: 12px 25px; border: none; border-radius: 10px; cursor: pointer; font-size: 1rem; transition: background 0.3s; }
        .btn-salvar:hover { background: #333; }
        .select-aluno { margin: 20px auto; display: block; padding: 10px; border-radius: 10px; border: 1px solid #ccc; width: 250px; font-size: 1rem; }
        /* Modal */
        .modal-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0,0,0,0.6); display: none; justify-content: center; align-items: center; z-index: 1000; }
        .modal { background: white; padding: 30px; border-radius: 20px; width: 90%; max-width: 400px; text-align: center; display:flex; flex-direction: column; height:fit-content; top:50%; left:50%; transform: translateX(-50%) translateY(-50%); }
        .modal h3 { margin-bottom: 15px; }
        .modal label { display: block; margin-top: 10px; font-weight: 600; }
        .modal select, .modal input { margin-top: 5px; padding: 8px; width: 100%; border: 1px solid #ccc; border-radius: 8px; }
        .modal-btns { margin-top: 20px; display: flex; gap: 10px; justify-content: center; }
        .modal-btns button { padding: 8px 20px; border: none; border-radius: 8px; cursor: pointer; }
        .btn-confirm { background: #111; color: white; }
        .btn-cancel { background: #ccc; }
    </style>
</head>
<body>
<?php include __DIR__ . "/header.php"; ?>

<section class="user-sect flex-column gap30 align-center">
    <h1>Monte a rotina semanal para seu aluno</h1>
    <p>Escolha um aluno e adicione exercícios em cada dia com séries e repetições.</p>

    <?php if (count($alunos) > 0): ?>
        <form id="formRotina" method="POST" action="SalvarRotina.php">
            <label for="alunoSelect"><b>Selecione o aluno:</b></label>
            <select name="aluno_id" id="alunoSelect" class="select-aluno" required>
                <option value="">-- Escolha um aluno --</option>
                <?php foreach ($alunos as $aluno): ?>
                    <option value="<?=$aluno['id']?>"><?=$aluno['nome_completo']?> (<?=$aluno['email']?>)</option>
                <?php endforeach; ?>
            </select>

            <div class="rotina-container">
                <?php 
                $dias = ['Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'];
                foreach ($dias as $dia): ?>
                    <div class="dia-rotina" data-dia="<?=$dia?>">
                        <h2><?=$dia?></h2>
                        <div class="flex-row justify-center">
                            <label>Dia:</label>
                            <input type="text" class="tipo-dia" placeholder="Ex: Braço, Perna, Descanso..." style="width:200px; appearance:none; border:none; border-bottom:1px solid black;"/>
                        </div>
                        <div class="exercicios-do-dia"></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <h2>Exercícios disponíveis</h2>
            <div class="lista-exercicios">
                <?php foreach ($exercicios as $ex): ?>
                    <div class="exercicio-card" 
                        data-id="<?=$ex['id']?>" 
                        data-nome="<?=htmlspecialchars($ex['name'])?>" 
                        data-thumb="IMG/exercicios/thumb/<?=$ex['thumb']?>">
                        <img src="IMG/exercicios/thumb/<?=$ex['thumb']?>" alt="<?=$ex['name']?>">
                        <p><?=$ex['name']?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <input type="hidden" name="rotina_json" id="rotina_json">
            <button type="submit" class="btn-salvar">Salvar Rotina</button>
        </form>
    <?php else: ?>
        <p>Você ainda não tem alunos cadastrados sob sua responsabilidade.</p>
    <?php endif; ?>
</section>

<!-- Modal de adicionar exercício -->
<div class="modal-bg" id="modal-bg">
    <div class="modal">
        <h3 id="modal-titulo">Adicionar exercício</h3>
        <label for="diaSelect">Dia da semana:</label>
        <select id="diaSelect">
            <option value="">Selecione...</option>
            <?php foreach ($dias as $dia): ?>
                <option value="<?=$dia?>"><?=$dia?></option>
            <?php endforeach; ?>
        </select>

        <label for="seriesInput">Séries:</label>
        <input type="number" id="seriesInput" min="1" placeholder="Ex: 4">

        <label for="repsInput">Repetições:</label>
        <input type="number" id="repsInput" min="1" placeholder="Ex: 12">

        <div class="modal-btns">
            <button type="button" class="btn-confirm" id="btnConfirm">Adicionar</button>
            <button type="button" class="btn-cancel" id="btnCancel">Cancelar</button>
        </div>
    </div>
</div>

<?php include __DIR__ . "/footer.php"; ?>

<script>
const cards = document.querySelectorAll('.exercicio-card');
const form = document.getElementById('formRotina');
const inputJson = document.getElementById('rotina_json');
const modalBg = document.getElementById('modal-bg');
const diaSelect = document.getElementById('diaSelect');
const seriesInput = document.getElementById('seriesInput');
const repsInput = document.getElementById('repsInput');
const btnConfirm = document.getElementById('btnConfirm');
const btnCancel = document.getElementById('btnCancel');
let exercicioSelecionado = null;

const rotina = {
    Segunda: [], Terça: [], Quarta: [], Quinta: [], Sexta: [], Sábado: [], Domingo: []
};

cards.forEach(card => {
    card.addEventListener('click', () => {
        exercicioSelecionado = {
            id: card.dataset.id,
            nome: card.dataset.nome,
            thumb: card.dataset.thumb
        };
        modalBg.style.display = 'flex';
    });
});

btnCancel.onclick = () => {
    modalBg.style.display = 'none';
    diaSelect.value = "";
    seriesInput.value = "";
    repsInput.value = "";
};

btnConfirm.onclick = () => {
    const dia = diaSelect.value;
    const series = parseInt(seriesInput.value);
    const reps = parseInt(repsInput.value);

    if (!dia || !series || !reps) {
        alert("Preencha todos os campos!");
        return;
    }

    const novo = {
        ...exercicioSelecionado,
        series: series,
        repeticoes: reps
    };

    rotina[dia].push(novo);
    renderDia(dia);
    modalBg.style.display = 'none';
    diaSelect.value = "";
    seriesInput.value = "";
    repsInput.value = "";
};

function renderDia(dia) {
    const divDia = document.querySelector(`.dia-rotina[data-dia="${dia}"] .exercicios-do-dia`);
    divDia.innerHTML = "";
    rotina[dia].forEach(ex => {
        const item = document.createElement("div");
        item.classList.add("exercicio-item");
        item.innerHTML = `
            <img src="${ex.thumb}" alt="${ex.nome}">
            <span>${ex.nome}</span>
            <span>${ex.series}x${ex.repeticoes}</span>
            <i class="fa-solid fa-trash" style="cursor:pointer;color:#c00" title="Remover"></i>
        `;
        item.querySelector(".fa-trash").onclick = () => {
            rotina[dia] = rotina[dia].filter(e => e.id !== ex.id);
            renderDia(dia);
        };
        divDia.appendChild(item);
    });
}

form.addEventListener("submit", e => {
    e.preventDefault();
    const aluno = document.getElementById("alunoSelect").value;
    if (!aluno) {
        alert("Selecione um aluno antes de salvar a rotina!");
        return;
    }
    inputJson.value = JSON.stringify(rotina);
    form.submit();
});
</script>
</body>
</html>
