<?php 

$filename = __DIR__.'/data/articles.json';
$article = [];

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';


if(!$id) {
    header('Location: /');
} else {
    if(file_exists($filename)){
        $articles = json_decode(file_get_contents($filename), true) ?? [];
        $articleIdx = array_search($id, array_column($article, 'id'));
        array_splice($articles, $articleIdx, 1);
        file_put_contents($filename, json_encode($articles));
        header('Location: /');
    }
}
