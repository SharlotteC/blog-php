<?php 
$filename = __DIR__."/data/articles.json";
$articles = [];
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if(!$id) {
    header('Location: /');
} else {
    if(file_exists($filename)){
        $articles = json_decode(file_get_contents($filename), true) ?? [];
        $articleIdx = array_search($id, array_column($articles, 'id'));
        $article = $articles[$articleIdx];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once'includes/head.php'?>
    <link rel="stylesheet" href="public/css/show-article.css">
    <title>Article</title>
</head>
<body>
    <div class="container">
        <?php require_once 'includes/header.php'?>
        <div class="content">
            <div class="article-container">
                <a href="/" class="article-back"> Retour à la liste des articles </a>
                <div class="article-cover-img" style="background-image: url(<?= $article['image'] ?>);" ></div>
                <h1 class="article-title"><?= $article['title'] ?></h1>
                <div class="separator"></div>
                <p class="article-content"><?= $article['content'] ?></p>
            </div>
        </div>
        <?php require_once 'includes/footer.php'?>
    </div>
</body>
</html>