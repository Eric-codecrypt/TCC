<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
<?php include __DIR__ . "/header.php"; ?> <br><br><br>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Treinos da Semana</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

</head>
<body>

<style>
body {
  font-family: Arial, sans-serif;
  background: #f8f9fa;
  text-align: center;
  justify-content: center;
}
</style>

  <h1>Treinos da Semana</h1>

  <div class="dias">
    <div class="dia segunda" data-dia="segunda">
      Segunda<br><br><img src="IMG/costas.png" alt="">
    </div>
    <div class="dia terca" data-dia="terca">
      Terça<br><br><img src="IMG/perna.png" alt="">
    </div>
    <div class="dia quarta" data-dia="quarta">
      Quarta<br><br><img src="IMG/peito.png" alt="">
    </div>
    <div class="dia quinta" data-dia="quinta">
      Quinta<br><br><img src="IMG/biceps.png" alt="">
    </div>
    <div class="dia sexta" data-dia="sexta">
      Sexta<br><br><img src="IMG/ombro.png" alt="">
    </div>
    <div class="dia sabado" data-dia="sabado">
      Sábado<br><br><img src="IMG/descanso.png" alt="">
    </div>
    <div class="dia domingo" data-dia="domingo">
      Domingo<br><br><img src="IMG/descanso.png" alt="">
    </div>
  </div>

  <!-- Modal -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2 id="modal-title"></h2>
      <img id="modal-img" src="" alt="">
      <p id="modal-desc"></p>
      <p id="modal-serie" class="serie"></p>
    </div>
  </div>

  <br>
  <br>

  <script src="script.js"></script>
  <script src="script.js"></script>
    <?php include __DIR__ . "/footer.php"; ?>
</body>
</html>
<script>// Dados dos treinos
const treinos = {
  segunda: {
    titulo: "Pulley frontal",
    img: "IMG/exercicios/gif/pulley_frontal.gif",
    desc: "Fortalece e define os músculos das costas (grande dorsal, bíceps, trapézio e deltóide posterior).",
    serie: "Fazer 3 séries de 12 repetições"
  },
  terca: {
    titulo: "leg press 45 graus",
    img: "IMG/exercicios/gif/leg_press_45_graus.gig",
    desc: "Exercício para pernas, glúteos e quadríceps, ajudando na força e postura.",
    serie: "Fazer 4 séries de 10 repetições"
  },
  quarta: {
    titulo: "Supino reto",
    img: "IMG/exercicios/gif/supino_reto_com_barra.gif",
    desc: "Trabalha peitoral, tríceps e deltóide anterior.",
    serie: "Fazer 3 séries de 12 repetições"
  },
  quinta: {
    titulo: "Rosca bíceps",
    img: "IMG/exercicios/gif/rosca_direta_barra_reta.gif",
    desc: "Fortalece bíceps e melhora a definição dos braços.",
    serie: "Fazer 3 séries de 15 repetições"
  },
  sexta: {
    titulo: "Elevação lateral",
    img: "IMG/exercicios/gif/elevacao_lateral_com_halteres_sentado.gif",
    desc: "Trabalha deltóides e trapézio, ajudando a dar forma aos ombros.",
    serie: "Fazer 4 séries de 12 repetições"
  },
  sabado: {
    titulo: "Dia de descanso",
    img: "IMG/descanso.png",
    desc: "O descanso é fundamental para a recuperação muscular e evitar lesões.",
    serie: ""
  },
  domingo: {
    titulo: "Dia de descanso",
    img: "IMG/descanso.png",
    desc: "Recupere suas energias e prepare-se para a próxima semana de treinos!",
    serie: ""
  }
};

// Seleciona modal
const modal = document.getElementById("modal");
const modalTitle = document.getElementById("modal-title");
const modalImg = document.getElementById("modal-img");
const modalDesc = document.getElementById("modal-desc");
const modalSerie = document.getElementById("modal-serie");
const closeBtn = document.querySelector(".close");

// Abre modal ao clicar em um dia
document.querySelectorAll(".dia").forEach(dia => {
  dia.addEventListener("click", () => {
    const diaSemana = dia.getAttribute("data-dia");
    const treino = treinos[diaSemana];

    modalTitle.textContent = treino.titulo;
    modalImg.src = treino.img;
    modalDesc.textContent = treino.desc;
    modalSerie.textContent = treino.serie;

    modal.style.display = "block";
  });
});

// Fecha modal
closeBtn.onclick = function() {
  modal.style.display = "none";
};

// Fecha ao clicar fora do modal
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};
</script>