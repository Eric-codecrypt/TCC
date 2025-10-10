<?php

if(!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])){
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    header("LOcation: ". __FILE__);
}

?>
<header>
        <nav class="nav-bar">
            <div class="logo">
                <a href=""> 
                    <?php include __DIR__."/IMG/Move-On-Logo-square.svg";?>
                </a>    
            </div>

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
                    <li class="nav-item"> <a href="" class="nav-link">Pagamento</a></li>

                </ul>
            </div>
            <?php if(!isset($_SESSION['user_id'])):?>
                <div class="landing-button">
                    <button class="login-button"><a href="LoginAccount.php"> Log in</a></button>
                    <button class="sign-button"><a href="CreateAccount.php"> Sign up</a></button>
                </div>
            <?php else:?>
                <a href='UserView.php' style="color:white"> (PLACEHOLDER) tá logado</a>
            <?php endif;?>
            <!-- Ícone do menu hamburger (visível só no mobile) -->
            <div class="mobile-menu-icon">
                <button onclick="menuShow()"><i class="fa-solid fa-bars"></i></button>
            </div>


        </nav>

        <!-- Menu mobile -->
        <div class="mobile-menu">
            <ul>
                <li class="nav-item"><a href="landing.php" class="nav-link">HOME</a></li>
                <li class="nav-item"><a href="plans.php" class="nav-link">Planos</a></li>
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

<script>
    function menuShow() {
        let menuMobile = document.querySelector('.mobile-menu');
        menuMobile.classList.toggle('active');
    }
</script>