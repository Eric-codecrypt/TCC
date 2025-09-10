<header>
        <nav class="nav-bar">
            <div class="logo">
                <a href=""> 
                    <?php include __DIR__."/IMG/Move-On-Logo-square.svg";?>
                </a>    
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
                <button class="login-button"><a href="LoginAccount.php"> Log in</a></button>
                <button class="sign-button"><a href="CreateAccount.php"> Sign up</a></button>
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
            <?php if (!isset($_SESSION['user_id'])):?>
            <div class="mobile-login">
                <button class="login-button"><a href="">Log in</a></button>
                <button class="sign-button"><a href="">Sign up</a></button>
            </div>
            <?php else:?>

            <?php endif;?>
        </div>


    </header>

<script>
    function menuShow() {
        let menuMobile = document.querySelector('.mobile-menu');
        menuMobile.classList.toggle('active');
    }
</script>