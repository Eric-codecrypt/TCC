  <!-- Script -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="bootstrap.js"> </script>
<?php 
  
  if (session_status() === PHP_SESSION_NONE){
    session_start();
  }

  include_once '../Controller/UserController.php';
  include_once '../Config.php';
  if(isset($_SESSION['user_id']) && $_SESSION['user_id']){    
    $Controller = new UserController($pdo);
    $user_id = $_SESSION['user_id'];

    // Buscar dados do usuário
    $user = $Controller->findById($user_id);
    $nome_arquivo_fotoperfil = $Controller->getFotoPerfil($user['nome_arquivo_fotoperfil'], __DIR__);
  }?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOVEON</title>
    <link rel="shortcut icon" href="IMG/favicon.png" type="image/x-icon">
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

        <div class="cookie-backdrop" id="cookieBackdrop">
            <div class="cookie-modal" id="cookieModal">
                <h3>🍪 Preferencias de Cookie</h3>
                <p>
                    Usamos cookies para personalizar sua experiência e analisar o tráfego. Ao clicar em "Aceitar todos", você concorda
                    com o nosso uso de cookies. Você pode personalizar suas preferências clicando em "Gerenciar configurações".
                </p>
                <div class="cookie-buttons">
                    <button class="accept" onclick="acceptCookies()">Aceitar tudo</button>
                    <button class="settings" onclick="openSettings()">Gerenciar configurações</button>

                </div>
            </div>
        </div>
        <!-- Segunda Modal: Configurações Detalhadas -->
        <div class="cookie-settings-modal" id="cookieSettingsModal">
            <div class="cookie-modal">
                <h3>🍪 Configurações de cookies</h3>
                <form id="cookieSettingsForm">
                    <label>
                        <input type="checkbox" checked disabled>
                        <strong>
                            Cookies necessários</strong> (sempre ativo)
                    </label>
                    <br><br>
                    <label>
                        <input type="checkbox" id="performanceCookies" checked>
                        Cookies de desempenho
                    </label>
                    <br><br>
                    <label>
                        <input type="checkbox" id="marketingCookies">
                        Cookies de marketing
                    </label>
                </form>
                <div class="cookie-buttons">
                    <button class="accept" onclick="saveSettings()">Salve as configurações</button>
                    <button class="settings" onclick="closeSettings()">Voltar</button>
                </div>
            </div>
        </div>
        <script src="script.js"></script>
    
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
    
        <div class="carousel-item top-pos">
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
