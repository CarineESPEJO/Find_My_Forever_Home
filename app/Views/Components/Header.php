<header>
    <nav>

        <div><a href="/index.php">
                <img src="/assets/images/logo_big.PNG">
                <b>Find My dream Home</b>
            </a>
        </div>
        <?php if (!empty($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true): ?>
            <p>Bonjour <?php echo $_SESSION["userEmail"]; ?> </p>
        <?php endif; ?>
        <ul>
            <li><a href="/catalogs.php?type=House">Houses</a></li>
            <li><a href="/catalogs.php?type=Apartment">Apartments</a></li>
            <li><a href="/catalogs.php?type=Search">Rechercher</a></li>
            <?php if (!empty($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true): ?>
                <li><a href="/catalogs.php?type=Favorite">Favoris</a></li>
            <?php endif; ?>
            <?php if (!empty($_SESSION["userRole"]) && ($_SESSION["userRole"] === "admin" || $_SESSION["userRole"] === "agent")) : ?>
                <li class="login"><a href="/formsLayout.php?form=AddListing">Ajouter</a></li>
            <?php endif; ?>
            <?php if (!empty($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true): ?>
                <li class="login"><a href="/logout.php">Deconnexion</a></li>
            <?php else: ?>
                <li class="login"><a href="/formsLayout.php?form=login">Connexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>