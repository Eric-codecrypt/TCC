<?php
session_start();
include_once __DIR__.'/../Controller/UserController.php';
include_once __DIR__.'/../config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: landing.php");
}


if(!empty($_POST)){
    $cell = $_POST['cell'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $idade = $_POST['idade'] ?? '';
    $altura = $_POST['altura'] ?? '';
    $peso = $_POST['peso'] ?? '';
    $objetivos = $_POST['objetivos'] ?? [];
    $objetivo_outro = $_POST['objetivo_outro'] ?? '';
    $disponibilidade = $_POST['disponibilidade'] ?? '';
    $ja_treinou = $_POST['ja_treinou'] ?? '';
    $tempo_experiencia = $_POST['tempo_experiencia'] ?? '';
    $lesao = $_POST['lesao'] ?? '';
    $qual_lesao = $_POST['qual_lesao'] ?? '';
    $medicamento = $_POST['medicamento'] ?? '';
    $qual_medicamento = $_POST['qual_medicamento'] ?? '';

    // Montar parágrafo com quebras de linha
    $paragrafo = "Idade: $idade anos<br>";
    $paragrafo .= "Altura: $altura m<br>";
    $paragrafo .= "Peso: $peso kg<br>";

    if (!empty($objetivos)) {
        $paragrafo .= "Objetivos: " . implode(', ', $objetivos) . "<br>";
    }
    if (!empty($objetivo_outro)) {
        $paragrafo .= "Outro objetivo informado: $objetivo_outro<br>";
    }
    if (!empty($disponibilidade)) {
        $paragrafo .= "Disponibilidade: $disponibilidade por semana<br>";
    }

    // Histórico
    if ($ja_treinou === 'sim') {
        $paragrafo .= "Já treinou antes<br>";
        if (!empty($tempo_experiencia)) {
            $paragrafo .= "Tempo de experiência: $tempo_experiencia<br>";
        }
    } else if ($ja_treinou === 'nao') {
        $paragrafo .= "Nunca treinou antes<br>";
    }

    if ($lesao === 'sim') {
        $paragrafo .= "Possui lesão ou limitação física<br>";
        if (!empty($qual_lesao)) {
            $paragrafo .= "Detalhe da lesão: $qual_lesao<br>";
        }
    } else if ($lesao === 'nao') {
        $paragrafo .= "Não possui lesão ou limitação física<br>";
    }

    // Medicamentos
    if ($medicamento === 'sim') {
        $paragrafo .= "Usa medicamentos atualmente<br>";
        if (!empty($qual_medicamento)) {
            $paragrafo .= "Medicamentos informados: $qual_medicamento<br>";
        }
    } else if ($medicamento === 'nao') {
        $paragrafo .= "Não usa medicamentos atualmente<br>";
    }

    $Controller = new UserController($pdo);
    $Controller->updatePersonalInfo($_SESSION['user_id'],$cpf,$cell,$paragrafo);
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

    <form class="treino-personalizado" method="POST">
        <h1>Formulário de Avaliação Inicial - Treino Personalizado</h1>

        <h3>Dados Pessoais</h3>

        <div class="input-container">
            <label for="nome"><p>Nome:</p></label>
            <input type="text" id="nome" name="nome" required style="width:300px">
        </div>
        
        <div class="input-container">
            <label for="cell"><p>Celular:</p></label>
            <input type="tel" id="cell" name="cell" required style="width:200px">
        </div>
        
        <div class="input-container">
            <label for="cpf"><p>CPF:</p></label>
            <input type="name" id="cpf" name="cpf" required style="width:200px">
        </div>

        <div class="input-container">
            <label for="idade"><p>Idade:</p></label>
            <input type="number" id="idade" name="idade" required style="width:50px">
            <p>anos</p>
        </div>

        <div class="input-container">
            <label for="altura"><p>Altura:</p></label>
            <input type="number" id="altura" name="altura" step="0.01" required style="width:100px">
            <p>metros</p>
        </div>

        <div class="input-container">
            <label for="peso"><p>Peso:</p></label>
            <input type="number" id="peso" name="peso" step="0.01" min="0" required style="width:75px">
            <p>kg</p>
        </div>

        <h3>Objetivos</h3>

        <div class="input-container">
            <input type="checkbox" id="Emagrecimento" name="objetivos[]" value="Emagrecimento">
            <label for="Emagrecimento"><p>Emagrecimento</p></label>
        </div>
        <div class="input-container">
            <input type="checkbox" id="Ganho de massa muscular" name="objetivos[]" value="Ganho de massa muscular">
            <label for="Ganho de massa muscular"><p>Ganho de massa muscular</p></label>
        </div>
        <div class="input-container">
            <input type="checkbox" id="Condicionamento fisíco geral" name="objetivos[]" value="Condicionamento fisíco geral">
            <label for="Condicionamento fisíco geral"><p>Condicionamento físico geral</p></label>
        </div>
        <div class="input-container">
            <input type="checkbox" id="Definição muscular" name="objetivos[]" value="Definição muscular">
            <label for="Definição muscular"><p>Definição muscular</p></label>
        </div>
        <div class="input-container">
            <input type="checkbox" id="Resistência" name="objetivos[]" value="Resistência">
            <label for="Resistência"><p>Aumento de resistência</p></label>
        </div>
        <div class="input-container">
            <input type="checkbox" id="Reabilitação/saúde" name="objetivos[]" value="Reabilitação/saúde">
            <label for="Reabilitação/saúde"><p>Reabilitação/saúde</p></label>
        </div>

        <div class="input-container">
            <label for="objetivo_outro"><p>Outro:</p></label>
            <input type="text" id="objetivo_outro" name="objetivo_outro">
        </div>

        <h3>Disponibilidade</h3>

        <div class="input-container">
            <input type="radio" id="disp2x" name="disponibilidade" value="2x" required>
            <label for="disp2x"><p>2x por semana</p></label>
        </div>
        <div class="input-container">
            <input type="radio" id="disp3x" name="disponibilidade" value="3x" required>
            <label for="disp3x"><p>3x por semana</p></label>
        </div>
        <div class="input-container">
            <input type="radio" id="disp4x" name="disponibilidade" value="4x" required>
            <label for="disp4x"><p>4x por semana</p></label>
        </div>
        <div class="input-container">
            <input type="radio" id="disp5x" name="disponibilidade" value="5x" required>
            <label for="disp5x"><p>5x por semana</p></label>
        </div>
        <div class="input-container">
            <input type="radio" id="disp6x" name="disponibilidade" value="6x" required>
            <label for="disp6x"><p>6x por semana</p></label>
        </div>

        <h3>Histórico</h3>

        <div class="input-container">
            <p>Já treinou antes?</p>
            <input type="radio" id="ja_treinou_sim" name="ja_treinou" value="sim" required>
            <label for="ja_treinou_sim"><p>Sim</p></label>
            <input type="radio" id="ja_treinou_nao" name="ja_treinou" value="nao" required>
            <label for="ja_treinou_nao"><p>Não</p></label>
        </div>

        <div class="input-container">
            <label for="tempo_experiencia"><p>Se sim, quanto tempo de experiência?</p></label>
            <input type="text" id="tempo_experiencia" name="tempo_experiencia">
        </div>

        <div class="input-container">
            <p>Possui alguma lesão ou limitação física?</p>
            <input type="radio" id="lesao_sim" name="lesao" value="sim" required>
            <label for="lesao_sim"><p>Sim</p></label>
            <input type="radio" id="lesao_nao" name="lesao" value="nao" required>
            <label for="lesao_nao"><p>Não</p></label>
        </div>

        <div class="input-container">
            <label for="qual_lesao"><p>Se sim, qual?</p></label>
            <input type="text" id="qual_lesao" name="qual_lesao">
        </div>

        <div class="input-container">
            <p>Usa medicamentos atualmente?</p>
            <input type="radio" id="medicamento_sim" name="medicamento" value="sim" required>
            <label for="medicamento_sim"><p>Sim</p></label>
            <input type="radio" id="medicamento_nao" name="medicamento" value="nao" required>
            <label for="medicamento_nao"><p>Não</p></label>
        </div>

        <div class="input-container">
            <label for="qual_medicamento"><p>Se sim, qual?</p></label>
            <input type="text" id="qual_medicamento" name="qual_medicamento">
        </div>

        <button type="submit">
            Enviar fomulário
        </button>
    </form>



    <?php include __DIR__."/footer.php";?>    

</body>
</html>