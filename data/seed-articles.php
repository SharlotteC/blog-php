<?php 

$articles = json_decode(file_get_contents('./articles.json'), true);

$dns = 'mysql:host=localhost:3306;dbname=blog';
$user = 'root';
$pwd = '';

$pdo = new PDO($dns, $user, $pwd);

$statement = $pdo -> prepare('
    INSERT INTO article (title, category, content, image) VALUES (:title, :category, :content, :image)
');

foreach ($articles as $article) {

    $statement->bindValue(':title', $article['title']);
    $statement->bindValue(':category', $article['category']);
    $statement->bindValue(':content', $article['content']);
    $statement->bindValue(':image', $article['image']);
    $statement->execute();
};