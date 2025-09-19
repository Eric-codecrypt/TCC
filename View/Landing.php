  <!-- Script -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="bootstrap.js"> </script>
<?php if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOVEON</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/carousel.css">
    <link rel="stylesheet" type="text/css" href="font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>


<body>

    <?php include __DIR__."/header.php";?>    

    <div class="redline">
        <br>
    </div>
    <br><br><br>
    <Main>
        <div class="inic">
            <div class="content">
                <p class="subtitle">Não pare.</p>
                <p class="title">Move on</p><br><br><br><br>
            </div><br><br><br><br>
            <div class="btn-inic">


                <a href="Plans.php"><button class="btn1">Conheça nossos planos</button></a><br>
                <a href=""><button class="btn2">Reserve sua aula experimental</button></a>
            </div>
        </div><br><br>

        <div class="sobre">
            <div class="sobre-img">
                <img src="IMG/foto-sobre.png" alt="" class="imgsbr">
            </div>

            <div class="sobre-conten">
                <div class="titulo">
                    <p class="title-sobre"> Move On</p>
                    <p class="subt-sobre"> Fitness</p>
                </div><br><br>

                <div class="texto">

                
                    <br><br>
                    <p>Na Move On Fitness, nossa missão é ser o seu ponto de apoio na jornada rumo à sua melhor versão.
                        Acreditamos que a evolução é um processo contínuo e que, com o suporte certo, você pode alcançar
                        seus objetivos. Mais que uma academia, somos um ambiente seguro e acolhedor, onde o
                        profissionalismo de nossa equipe se une a uma estrutura de ponta para garantir que seu treino
                        seja sempre eficiente e motivador.</p><br>

                    <p> Nosso compromisso com a excelência se reflete em tudo que fazemos. Contamos com instrutores
                        experientes, planos de treino personalizados e equipamentos de última geração, tudo para que
                        você se sinta no controle de sua evolução. Na Move On Fitness, não é apenas sobre mover-se, é
                        sobre evoluir, dia após dia.</p>
                </div>
            </div>

        </div>
        <div class="redline">
        <br>
    </div>

    
    <br><br><br><br>


<div id="carousel" class="carousel slide" data-ride="carousel">

    <!--   Bullets do carrossel, se aumentar uma imagem, aumente um li e acrescento o próximo número -->
      <ol class="carousel-indicators">
        <li data-target="#carousel" data-slide-to="0" class="active"></li>
        <li data-target="#carousel" data-slide-to="1"></li>
        <li data-target="#carousel" data-slide-to="2"></li>
      </ol>
    
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="IMG/carrosel1.png">
        </div>
    
        <div class="carousel-item">
          <img src="IMG/carrosel2.png">
        </div>
    
        <div class="carousel-item">
          <img src="IMG/carrosel3.png">
        </div>
        
    <!--   Controladores | Botões -->
      <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only"> Previous </span>
      </a>
      <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only"> Next </span>
      </a> 
    
      </div> <!-- Fecha elementos dentro do carrossel -->
      

    </div> <!-- Fim do carrossel -->
    <br><br><br><br>

    <div class="banner">
        <a href=""> <img src="IMG/bannermove.jpg" alt="" class="web-banner"></a>

    </div><br><br><br><br>
</Main>
    <?php include __DIR__."/footer.php";?>
</body>

</html>
