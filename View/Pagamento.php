<!DOCTYPE html>
<html lang="pt-br">
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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sliding Button com Conteúdo</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

    <style>
        .togcontaig {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        /* ----- TOGGLE ----- */
        .toggle-container {
            display: flex;
            background: #fff;
            border: 2px solid #600;
            border-radius: 50px;
            overflow: hidden;
            width: 400px;
            position: relative;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .option {
            flex: 1;
            text-align: center;
            padding: 10px 0;
            font-weight: bold;
            z-index: 2;
            transition: color 0.3s ease;
            color: #600;
            user-select: none;
        }

        .active {
            color: #fff;
        }

        .slider {
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            background: #600;
            border-radius: 50px;
            transition: left 0.3s ease;
            z-index: 1;
        }

        /* ----- CONTEÚDO PRINCIPAL ----- */
        .content {
            display: none;
        }

        .content.active-content {
            display: block;
        }

        .content-thing {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            border: 2px solid #600;
            margin-bottom: 10px;
            width: 500px;
        }

        .content-thing .full {}

        .contaiconten {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .imagecnfg {
            width: 50px;
        }

        .imagecnfg2 {
            width: 72px;
        }

        .flexcon {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .texttg {
            font-size: 22px;
        }

        .infor {
            text-align: left;
        }

        .infor p {
            font-size: 13px;
        }

        .prec2 {
            font-weight: 700;
            font-size: 20px;
        }

        hr {
            border: none;
            height: 1px;
            background-color: #680e0eff;
        }

        /* ----- BOTÕES DE DETALHES ----- */
        .dropbtn {
            border: none;
            background-color: white;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            padding: 8px 0;
        }

        /* ----- CONTEÚDO EXPANDIDO ----- */
        .details {
            display: none;
            margin-top: 15px;
            padding: 15px;
            border: 2px solid #600;
            border-radius: 10px;
            background: #fff;
            animation: fadeIn 0.3s ease;
        }

        .details.show {
            display: block;
        }


        .infor2 {
            text-align: center;
        }

        .infor2 h3 {
            font-size: 25px;
            text-align: center;
        }

        .prec2 {
            font-weight: 700;
            font-size: 25px;
        }

        .prec {
            font-weight: 700;
        }

        .sobrepg {
            text-align: left;
        }

        .sobreflex {
            display: flex;
            justify-content: space-between;
        }

        .topic {
            text-align: left;
        }

        .topicansw {
            text-align: right;
        }

        .topicansw p {
            font-weight: 700;
        }

        .textimgsb {
            text-align: left;
        }


        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <?php include __DIR__ . "/header.php"; ?> <br><br><br>

    <div class="togcontaig">
        <!-- Toggle -->
        <div class="toggle-container" id="toggle">
            <div class="slider" id="slider"></div>
            <div class="option active" id="open">
                <p class="texttg">⚠ Em aberto</p>
            </div>
            <div class="option" id="paid">
                <p class="texttg">$ Pagos</p>
            </div>
        </div>
    </div>

    <div class="contaiconten">
        <!-- Conteúdo a pagar -->
        <div id="open-content" class="content active-content">
            <!-- HTML do pagamento fechado -->
            <div class="content-thing" id="closedopen1">
                <div class="flexcon">
                    <div class="image">
                        <img src="IMG/warning.png" alt="" class="imagecnfg">
                    </div>
                    <div class="infor">
                        <h3>Mensalidade em aberto</h3><br>
                        <p>Vencimento: 20/03/2025</p>
                        <p>Código: 22222</p>
                    </div>
                    <div class="prec">
                        <p>R$59,90</p>
                    </div>
                </div><br>

                <hr class="line"><br>

                <button class="dropbtn" onclick="toggleDetails('open1')">
                    <i class="fa-solid fa-caret-down"></i>
                    <p>Realizar pagamento</p>
                </button>
            </div>
            <!-- HTML do pagamento aberto -->
            <div class="content-thing full" id="fullopen1" style="display:none">
                <div class="contentfull">
                    <button class="dropbtn" onclick="toggleDetails('open1')">
                        <i class="fa-solid fa-caret-up" style="font-size:2em; height:0;"></i>
                    </button>
                    <div class="image">
                        <img src="IMG/warning.png" alt="" class="imagecnfg2">
                    </div>
                    <div class="infor2">
                        <h3>Valor a pagar</h3>
                    </div>
                    <div class="prec2">
                        <p>R$59,90</p>
                    </div><br>

                    <div class="sobrepg">
                        <h4>Sobre o pagamento</h4><br>
                        <div class="sobreflex">

                            <div class="topic">
                                <p>Data do vencimento</p>
                                <p>Horário</p>
                            </div>

                            <div class="topicansw">
                                <p>Terça, 20/03/2025</p>
                                <p>23:59h</p>
                            </div>

                        </div>
                    </div><br>

                    <hr class="line"><br>

                    <div class="sobrepg">
                        <h4>Quem Recebera</h4><br>
                        <div class="sobreflex">

                            <div class="topic">
                                <p>Nome</p>

                            </div>

                            <div class="topicansw">
                                <p>Move on Fitness cia</p>

                            </div>

                        </div>
                    </div><br>
                    <hr class="line"><br>

                    <div class="sobrepgimg">
                        <div class="textimgsb">
                            <h4>Quem Recebera</h4><br>
                        </div>
                        <div class="imgsb">
                            <img src="IMG/qrcode.png" alt="">
                        </div>
                    </div>

                </div><br>


            </div>
        </div>
        <!-- Conteúdo Pagos -->
        <div id="paid-content" class="content">
            <!-- HTML do pagamento fehchado -->
            <div id="closedpaid1" style="none" class="content-thing">
                <div class="flexcon">
                    <div class="image">
                        <img src="IMG/check.png" alt="" class="imagecnfg">
                    </div>
                    <div class="infor">
                        <h3>Mensalidade paga</h3><br>
                        <p>Pagamento: 20/03/2025</p>
                        <p>Código: 2222</p>
                    </div>
                    <div class="prec">
                        <p>R$59,90</p>
                    </div>
                </div><br>

                <hr class="line"><br>

                <button class="dropbtn" onclick="toggleDetails('paid1')">
                    <i class="fa-solid fa-caret-down"></i>
                    <p>Visualizar detalhes</p>
                </button>
            </div>
            <!-- HTML do pagamento aberto -->
            <div id="fullpaid1" style="display:none;" class="content-thing full">
                <button class="dropbtn" onclick="toggleDetails('paid1')">
                    <i class="fa-solid fa-caret-up" style="font-size:2em; height:0;"></i>
                </button>
                <div class="image">
                    <img src="IMG/check.png" alt="" class="imagecnfg">
                </div>
                <div class="infor2">
                    <h3>Valor pago</h3>
                </div>
                <div class="prec2">
                    <p>R$59,90</p>
                </div>
                <br>


                <div class="sobrepg">
                    <h4>Sobre o pagamento</h4><br>
                    <div class="sobreflex">

                        <div class="topic">
                            <p>Data do vencimento</p>
                            <p>Horário</p>
                        </div>

                        <div class="topicansw">
                            <p>Terça, 20/03/2025</p>
                            <p>15:32h</p>
                        </div>

                    </div>
                </div><br>

                <hr class="line"><br>

                <div class="sobrepg">
                    <h4>Quem Recebera</h4><br>
                    <div class="sobreflex">

                        <div class="topic">
                            <p>Nome</p>

                        </div>

                        <div class="topicansw">
                            <p>Move on Fitness cia</p>

                        </div>

                    </div>
                </div><br>

                <hr class="line"><br>

                <div class="sobrepg">
                    <h4>Quem Pagou</h4><br>
                    <div class="sobreflex">

                        <div class="topic">
                            <p>Nome</p>

                        </div>

                        <div class="topicansw">
                            <p>Filipe Mendes </p>

                        </div>

                    </div>
                </div><br>

                <hr class="line"><br>

            </div>
        </div>
    </div>

    <br><br><br><br>

    <?php include __DIR__ . "/footer.php"; ?>


    <script>
        const toggle = document.getElementById('toggle');
        const slider = document.getElementById('slider');
        const open = document.getElementById('open');
        const paid = document.getElementById('paid');

        const openContent = document.getElementById('open-content');
        const paidContent = document.getElementById('paid-content');

        let active = "open";

        toggle.addEventListener("click", () => {
            if (active === "open") {
                slider.style.left = "50%";
                open.classList.remove("active");
                paid.classList.add("active");
                openContent.classList.remove("active-content");
                paidContent.classList.add("active-content");
                active = "paid";
            } else {
                slider.style.left = "0";
                paid.classList.remove("active");
                open.classList.add("active");
                paidContent.classList.remove("active-content");
                openContent.classList.add("active-content");
                active = "open";
            }
        });

        function toggleDetails(name) {
            const closed = document.getElementById("closed" + name)
            const full = document.getElementById("full" + name)

            if (closed.style.display == "none") {
                closed.style.display = ""
                full.style.display = "none"
            } else {
                closed.style.display = "none"
                full.style.display = ""
            }
        }
    </script>
</body>

</html>