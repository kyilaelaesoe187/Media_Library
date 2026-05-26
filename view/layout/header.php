<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle ?? 'Media Library') ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>

<body>

    <div class="page-container">
        <div class="content">

            <header class="header">
                <div class="wrapper">

                    <!-- LOGO -->
                    <h1 class="logo">
                        <a href="<?= BASE_URL ?>/Public/index.php">
                            <img src="<?= BASE_URL ?>/img/Brand-title.png" alt="Media Library">
                        </a>
                    </h1>

                    <!-- NAVIGATION -->

                    <ul class="nav">

                        <!-- BOOKS -->
                        <li class="<?= (
                                        ($_GET['page'] ?? '') === 'catalog'
                                        &&
                                        ($_GET['cat'] ?? '') === 'books'
                                    ) ? 'on' : '' ?>">

                            <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=books">
                                <img src="<?= BASE_URL ?>/img/book.png">
                                Books
                            </a>

                        </li>

                        <!-- MOVIES -->
                        <li class="<?= (
                                        ($_GET['page'] ?? '') === 'catalog'
                                        &&
                                        ($_GET['cat'] ?? '') === 'movies'
                                    ) ? 'on' : '' ?>">

                            <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=movies">
                                <img src="<?= BASE_URL ?>/img/movie.png">
                                Movies
                            </a>

                        </li>

                        <!-- MUSIC -->
                        <li class="<?= (
                                        ($_GET['page'] ?? '') === 'catalog'
                                        &&
                                        ($_GET['cat'] ?? '') === 'music'
                                    ) ? 'on' : '' ?>">

                            <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=music">
                                <img src="<?= BASE_URL ?>/img/music.png">
                                Music
                            </a>

                        </li>

                        <!-- SUGGEST -->
                        <li class="<?= (
                                        ($_GET['page'] ?? '') === 'suggest'
                                    ) ? 'on' : '' ?>">

                            <a href="<?= BASE_URL ?>/Public/index.php?page=suggest">
                                <img src="<?= BASE_URL ?>/img/suggestion.png">
                                Suggest
                            </a>

                        </li>

                        <!-- AUTH -->
                        <?php if (!empty($_SESSION['user'])): ?>

                            <li class="user">

                                <a href="<?= BASE_URL ?>/Public/index.php?page=logout">

                                    Logout (
                                    <?= htmlspecialchars($_SESSION['user']['username']) ?>
                                    )

                                </a>

                            </li>

                        <?php else: ?>

                            <!-- LOGIN -->
                            <li class="<?= (
                                            ($_GET['page'] ?? '') === 'login'
                                        ) ? 'on' : '' ?>">

                                <a href="<?= BASE_URL ?>/Public/index.php?page=login">
                                    Login
                                </a>

                            </li>

                            <!-- REGISTER -->
                            <li class="<?= (
                                            ($_GET['page'] ?? '') === 'register'
                                        ) ? 'on' : '' ?>">

                                <a href="<?= BASE_URL ?>/Public/index.php?page=register">
                                    Register
                                </a>

                            </li>

                        <?php endif; ?>


                    </ul>

                </div>
            </header>

            <!-- SEARCH BAR -->
            <?php if (empty($hideSearch)): ?>
                <div class="search">
                    <div class="wrapper">
                        <form method="get" action="<?= BASE_URL ?>/Public/index.php">
                            <input type="hidden" name="page" value="catalog">

                            <?php if (!empty($section)): ?>
                                <input type="hidden" name="cat" value="<?= htmlspecialchars($section) ?>">
                            <?php endif; ?>

                            <label for="s">Search:</label>
                            <input type="text" name="s" id="s" value="<?= htmlspecialchars($_GET['s'] ?? '') ?>">
                            <input type="submit" value="Go">
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <main id="content">