<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOVEON</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>

<script>
    function menuShow() {
        let menuMobile = document.querySelector('.mobile-menu');
        menuMobile.classList.toggle('active');
    }
</script>

<body>

    <header>
        <nav class="nav-bar">
            <div class="logo">
                <a href=""> <img src="IMG/logo.svg" alt="" class="logo-img"></a>

            </div>

            <div class="nav-list">
                <ul>
                    <li class="nav-item"> <a href="" class="nav-link">HOME</a></li>
                    <li>
                        <p>|</p>
                    </li>
                    <li class="nav-item"> <a href="" class="nav-link">Planos</a></li>
                    <li>
                        <p>|</p>
                    </li>
                    <li class="nav-item"> <a href="" class="nav-link">Dúvidas</a></li>
                    <li>
                        <p>|</p>
                    </li>
                    <li class="nav-item"> <a href="" class="nav-link">Treinos</a></li>
                    <li>
                        <p>|</p>
                    </li>
                    <li class="nav-item"> <a href="" class="nav-link">Pagamento</a></li>

                </ul>
            </div>

            <div class="landing-button">
                <button class="login-button"><a href=""> Log in</a></button>
                <button class="sign-button"><a href=""> Sign up</a></button>
            </div>

            <!-- Ícone do menu hamburger (visível só no mobile) -->
            <div class="mobile-menu-icon">
                <button onclick="menuShow()"><i class="fa-solid fa-bars"></i></button>
            </div>


        </nav>

        <!-- Menu mobile -->
        <div class="mobile-menu">
            <ul>
                <li class="nav-item"><a href="" class="nav-link">HOME</a></li>
                <li class="nav-item"><a href="" class="nav-link">Planos</a></li>
                <li class="nav-item"><a href="" class="nav-link">Dúvidas</a></li>
                <li class="nav-item"><a href="" class="nav-link">Treinos</a></li>
                <li class="nav-item"><a href="" class="nav-link">Pagamento</a></li>
            </ul>
            <div class="mobile-login">
                <button class="login-button"><a href="">Log in</a></button>
                <button class="sign-button"><a href="">Sign up</a></button>
            </div>
        </div>


    </header>
    <br><br><br>
    <Main>
        <div class="inic">
            <div class="content">
                <p class="subtitle">Não pare.</p>
                <p class="title">Move on</p><br>
            </div><br><br><br>
            <div class="btn-inic">


                <button class="btn1"><a href="">Conheça nossos planos</a></button><br>
                <button class="btn2"><a href="">Reserve sua aula experimental</a></button>
            </div>
        </div><br><br>

        <div class="sobre">
            <div class="sobre-img">
                <img src="IMG/foto-sobre.png" alt="">
            </div>

            <div class="sobre-conten">
                <p> Move On</p>
            </div>

        </div>
    </Main>


</body>

</html>