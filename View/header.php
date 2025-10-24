<?php

if(!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])){
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    header("LOcation: ". __FILE__);
}

?>
<header>
        <nav class="nav-bar">
            <a class="logo" href="landing.php">
            </a>

            <div class="nav-list">
                <ul>
                    <li class="nav-item"> <a href="landing.php" class="nav-link">HOME</a></li>
                    <li>
                        <p>|</p>
                    </li>
                    <li class="nav-item"> <a href="plans.php" class="nav-link">Planos</a></li>
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
                    <li class="nav-item"> <a href="Pagamento.php" class="nav-link">Pagamento</a></li>

                </ul>
            </div>
            <div class="pfp-hamb">
                <?php if(!isset($_SESSION['user_id'])):?>
                    <div class="landing-button">
                        <button class="login-button"><a href="LoginAccount.php"> Log in</a></button>
                        <button class="sign-button"><a href="CreateAccount.php"> Sign up</a></button>
                    </div>
                <?php else:?>
                    <?php if(isset($nome_arquivo_fotoperfil)):?>
                        <a class="pfp mini" href="UserView.php">
                            <img src="IMG/pfps/<?=$nome_arquivo_fotoperfil?>">
                        </a>
                    <?php else:?>
                        <a class="pfp mini" href="UserView.php">
                            <img src="IMG/PFPpadrao.png">
                        </a>
                    <?php endif;?>
                <?php endif;?>
                        <!-- Ícone do menu hamburger (visível só no mobile) -->
                <div class="mobile-menu-icon">
                    <button onclick="menuShow()"><i class="fa-solid fa-bars"></i></button>
                </div>
            </div>

        </nav>

        <!-- Menu mobile -->
        <div class="mobile-menu">
            <ul>
                <li class="nav-item"><a href="landing.php" class="nav-link">HOME</a></li>
                <li class="nav-item"><a href="plans.php" class="nav-link">Planos</a></li>
                <li class="nav-item"><a href="" class="nav-link">Dúvidas</a></li>
                <li class="nav-item"><a href="" class="nav-link">Treinos</a></li>
                <li class="nav-item"><a href="Pagamento.php" class="nav-link">Pagamento</a></li>
            </ul>
            
                <div class="mobile-login">
                    <button class="login-button"><a href="">Log in</a></button>
                    <button class="sign-button"><a href="">Sign up</a></button>
                </div>
        </div>


    </header>

<script>
    function menuShow() {
        let menuMobile = document.querySelector('.mobile-menu');
        menuMobile.classList.toggle('active');
    }
</script>