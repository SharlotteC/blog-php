

<header>
    <a class="logo" href="/">Blog App</a>
    <ul class="header-menu">
        <li class="<?= $_SERVER["REQUEST_URI"] === "/add-article.php" ? "active" : "" ?>">
            <a href="/add-article.php">Ecrire un article</a>
        </li>
    </ul>
</header>