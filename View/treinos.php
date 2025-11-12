<?php
session_start();
include_once '../Controller/UserController.php';
include_once '../Config.php';

// Verificar se o usuário está logado
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

// Buscar JSON de rotina
$rotinaJson = $user['rotina_treinamento'] ?? null;
$rotina = $rotinaJson ? json_decode($rotinaJson, true) : [];

$diasSemana = ['Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Treinos da Semana - Move On Fitness</title>
  <link rel="shortcut icon" href="IMG/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/basics.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f9fa;
      text-align: center;
    }
    .dias {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 40px;
    }
    .dia {
      width: 160px;
      border-radius: 15px;
      padding: 15px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: transform 0.2s;
    }
    .dia:hover { transform: scale(1.05); }
    .dia img {
      width: 100px;
      height: 100px;
      object-fit: contain;
    }
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
      padding:0
    }
    .modal-content {
      background: white;
      padding: 25px;
      border-radius: 15px;
      width: 90%;
      max-width: 400px;
      position: relative;
      text-align: center;
      animation: showModal 0.3s ease;
      max-height: 500px;
      overflow: auto;
    }
    .modal-content img { width: 150px; height: 150px; object-fit: contain; margin: 10px 0; }
    .close {
      position: absolute;
      right: 15px;
      top: 10px;
      font-size: 22px;
      cursor: pointer;
      color: #555;
    }
    .serie { font-weight: bold; margin-top: 10px; }
    @keyframes showModal { from {opacity:0; transform: scale(0.9);} to {opacity:1; transform: scale(1);} }
  </style>
</head>
<body>
  <?php include __DIR__ . "/header.php"; ?>
  <br><br><br>

  <h1>Treinos da Semana</h1>
  <p>Veja os exercícios que seu treinador preparou para você.</p>

  <div class="dias">
    <?php foreach ($diasSemana as $dia): 
      $exercicios = $rotina[$dia] ?? [];
      $imgDia = "IMG/descanso.png";
      $textoDia = "Descanso";

      // mostra thumb do primeiro exercício, se existir
      if (!empty($exercicios)) {
        $imgDia = $exercicios[0]['thumb'];
        $textoDia = $dia;
      }
    ?>
      <div class="dia <?=strtolower(htmlspecialchars($dia))?>" data-dia="<?=htmlspecialchars($dia)?>">
        <strong><?=$textoDia?></strong><br><br>
        <img src="<?=$imgDia?>" alt="<?=$dia?>">
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Modal -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2 id="modal-title"></h2>
      <h4 id="modal-title-small"></h4>
      <p id="modal-desc"></p>
      <p id="modal-serie" class="serie"></p>
    </div>
  </div>
      <br>
  <?php include __DIR__ . "/footer.php"; ?>

  <script>
    // Rotina PHP → JS
    const rotina = <?= json_encode($rotina, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>;

    const modal = document.getElementById("modal");
    const modalTitle = document.getElementById("modal-title");
    const modalTitletwo = document.getElementById("modal-title-small");
    const modalDesc = document.getElementById("modal-desc");
    const modalSerie = document.getElementById("modal-serie");
    const closeBtn = document.querySelector(".close");

    document.querySelectorAll(".dia").forEach(diaEl => {
      diaEl.addEventListener("click", () => {
        const dia = diaEl.getAttribute("data-dia");
        
        const exercicios = rotina[dia] || [];

        if (exercicios.length === 0) {
          modalTitle.textContent = "Dia de descanso";
          modalImg.src = "IMG/descanso.png";
          modalDesc.textContent = "Aproveite para se recuperar!";
          modalSerie.textContent = "";
          modal.style.display = "flex";
          return;
        }
        function thumbParaGif(caminho) {
          return caminho.replace("exercicios/thumb/", "exercicios/gif/").replace(/\.[^.]+$/, ".gif");
        }
        // monta descrição
        let htmlDesc = "";
        exercicios.forEach(ex => {
          htmlDesc += `
            <div style="margin:15px 0;border-bottom:1px solid #eee;padding-bottom:10px;">
              <img src="${thumbParaGif(ex.thumb)}" alt="${ex.nome}" style="width:250px;height:250px;object-fit:contain"><br>
              <strong>${ex.nome}</strong><br>
              ${ex.series} séries de ${ex.repeticoes} repetições
            </div>
          `;
        });

        modalTitle.textContent = dia;
        modalTitletwo.textContent = dia;
        modalDesc.innerHTML = htmlDesc;
        modalSerie.textContent = "";
        modal.style.display = "flex";
      });
    });

    closeBtn.onclick = () => modal.style.display = "none";
    window.onclick = (e) => { if (e.target == modal) modal.style.display = "none"; };
  </script>
</body>
</html>
