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
</head>
<body>

<style>
body {
  font-family: Arial, sans-serif;
  background: #f8f9fa;
  text-align: center;
  justify-content: center;
}

.dias {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
  margin-top: 40px;
  max-width: 600px;
  margin: auto;
}

.dia {
  width: 180px;
  height: 180px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  border-radius: 20px;
  color: #fff;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.2s;
}

.dia img {
  width: 40px;
  margin-bottom: 10px;
}

.dia:hover {
  transform: scale(1.1);
}

.segunda, .quarta, .sexta {
  background: #e60000;
}

.terca, .quinta, .sabado, .domingo {
  background: #111;
}

/* Modal */
.modal {
  display: none; 
  position: fixed;
  z-index: 1000;
  padding-top: 100px;
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%;
  background: rgba(0,0,0,0.7);
}

.modal-content {
  background: #fff;
  margin: auto;
  padding: 20px;
  border-radius: 15px;
  width: 60%;
  text-align: center;
  position: relative;
}

.modal-content img {
  width: 250px;
  margin: 15px 0;
}

.close {
  color: #aaa;
  font-size: 28px;
  font-weight: bold;
  position: absolute;
  top: 15px;
  right: 20px;
  cursor: pointer;
}

.close:hover {
  color: #000;
}

.serie {
  color: red;
  font-weight: bold;
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