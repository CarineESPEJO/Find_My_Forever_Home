<header>
    <nav>

        <div><a href="/index.php">
                <img src="/assets/images/logo_big.png">
                <b>Find My dream Home</b>
            </a>
        </div>
        <ul>
            <li><a href="https://m.media-amazon.com/images/I/813kqvYoRfL.png">House</a></li>
            <li><a href="/views/pages/comments.php">Appartement</a></li>
            <?php if(!empty($_SESSION["userRole"]) && ($_SESSION["userRole"] === "admin" || $_SESSION["userRole"] === "agent")) : ?>
                <li class="login"><a href="/views/pages/add_annonce.php">Add</a></li>
            <?php endif; ?>
                <?php if (!empty($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true): ?>
                <li class="login"><a href="/views/pages/connexion/logout.php">Log out</a></li>
            <?php else: ?>
                <li class="login"><a href="/views/pages/connexion/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>