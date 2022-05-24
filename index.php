<?php
    $filename = __DIR__.'/data/articles.json';
    $articles = [];
    $categories = [];

    if(file_exists($filename)) {
        $articles = json_decode(file_get_contents($filename), true) ?? [];
        $categories = array_map(fn ($a) => $a["category"], $articles);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="public/css/index.css">
    <title>Blog App</title>
</head>
<body>
    <div class="container">
        <?php require_once 'includes/header.php'?>
        <div class="content">
            <div class="articles-container">
                <?php foreach($articles as $article) : ?>
                    <div class="article block">
                        <div class="overflow">
                            <div class="img-container" style= "background-image : url(<?= $article['image'] ?>);"></div>
                        </div>
                        <h2><?= $article['title'] ?></h2>
                    </div>
                <?php endforeach; ?> 
            </div>
        </div>
        <?php require_once 'includes/footer.php'?>
    </div>
</body>
</html>
