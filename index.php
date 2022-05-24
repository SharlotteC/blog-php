<?php
    $filename = __DIR__.'/data/articles.json';
    $articles = [];
    $categories = [];
    $selectedCat = "";
    
    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $selectedCat = $_GET['cat'] ?? '';

    if(file_exists($filename)) {
        $articles = json_decode(file_get_contents($filename), true) ?? [];
        $catmap = array_map(fn ($a) => $a['category'], $articles);
        
        // je cree un tableau associatif qui a pour cles les vcategories et pour valeur le nombre d'articles
        $categories = array_reduce($catmap, function($acc, $cat ){
            if(isset($acc[$cat])){
                $acc[$cat]++;
            } else {
                $acc[$cat] = 1;
            }
            return $acc;
        }, []);

        // je cree un tableau associatif qui a pour cles les categories et pour valeur tous les articles concernant la categorie
        $articlesPerCategories = array_reduce($articles, function($acc, $article){
            if(isset($acc[$article['category']])) {
                $acc[$article['category']] = [...$acc[$article['category']], $article];
            } else {
                $acc[$article['category']] = [$article];
            }
            return $acc;
        }, []);
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
            <div class="newsfeed-container">
                <ul class="category-container">
                    <li class="<?= $selectedCat  ? '' : 'cat-active' ?>"><a href="/">
                        Tous les articles<span class="small">(<?= count($articles) ?>)</span>
                            </a></li>
                            <?php  foreach($categories as $catName => $catNum) : ?>
                                <li class="<?= $selectedCat === $catName ? 'cat-active' : '' ?>"><a href="/?cat=<?= $catName ?>">
                                <?= $catName ?><span class="small">(<?= $catNum ?>)</span>
                            </a></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="feed-container">
                        <?php if(!$selectedCat) : ?>
                            <?php foreach($categories as $cat => $num) :?>
                                    <h2 class="p-10"><?= $cat ?></h2>
                                    <div class="articles-container">
                                    <?php foreach($articlesPerCategories[$cat] as $article) : ?>
                                        <div class="article block">
                                            <div class="overflow">
                                                <div class="img-container" style= "background-image : url(<?= $article['image'] ?>);"></div>
                                            </div>
                                                <h3><?= $article['title'] ?></h3>
                                            </div>
                                        <?php endforeach; ?> 
                                    </div>
                            <?php endforeach ?>
                        <?php else :  ?>
                            <h2><?= $selectedCat ?></h2>
                            <div class="articles-container">
                                <?php foreach($articlesPerCategories[$selectedCat] as $article) : ?>
                                    <div class="article block">
                                        <div class="overflow">
                                            <div class="img-container" style= "background-image : url(<?= $article['image'] ?>);"></div>
                                        </div>
                                            <h3><?= $article['title'] ?></h3>
                                        </div>
                                    <?php endforeach; ?> 
                                </div>
                        <?php endif;?>
                    </div>
                </div>            
            </div>
        <?php require_once 'includes/footer.php'?>
    </div>
</body>
</html>
