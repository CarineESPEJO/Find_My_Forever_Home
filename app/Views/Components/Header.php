<header>
    <nav>

        <div><a href="/index.php">
                <img src="/assets/images/logo_big.PNG">
                <b>Find My dream Home</b>
            </a>
        </div>
        <ul>
            <li><a href="/catalogs.php?type=House">Houses</a></li>
            <li><a href="/catalogs.php?type=Apartment">Apartments</a></li>
            <?php if (!empty($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true): ?>
                <li><a href="/catalogs.php?type=Favorite">Favoris</a></li>
            <?php endif; ?>
            <?php if (!empty($_SESSION["userRole"]) && ($_SESSION["userRole"] === "admin" || $_SESSION["userRole"] === "agent")) : ?>
                <li class="login"><a href="/formsLayout.php?form=AddListing">Add</a></li>
            <?php endif; ?>
            <?php if (!empty($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true): ?>
                <li class="login"><a href="/logout.php">Log out</a></li>
            <?php else: ?>
                <li class="login"><a href="/formsLayout.php?form=login">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>