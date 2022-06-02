<?php

$pdo = require_once __DIR__.'/../database.php';

class ArticleDAO {

    
    private PDOStatement $statementCreateOne;
    private PDOStatement $statementReadOne;
    private PDOStatement $statementReadAll;
    private PDOStatement $statementUpdateOne;
    private PDOStatement $statementDeleteOne;

    function __construct(private PDO $pdo){
        $this->statementCreateOne = $this->pdo->prepare('    
        INSERT INTO article ( title, category, content, image) VALUES (:title, :category, :content, :image)
        ');
        $this->statementUpdateOne = $this->pdo->prepare('
        UPDATE article SET title=:title, category=:category, content=:content, image=:image WHERE id=:id
        
        ');
        $this->statementReadOne= $this->pdo->prepare('SELECT * FROM article WHERE id=:id');
        $this->statementReadAll= $this->pdo->prepare('SELECT * FROM article');
        $this->statementDeleteOne= $this->pdo->prepare('DELETE  FROM article WHERE id=:id');
    }
    

    function createOne(array $article) : array {
        $this->statementCreateOne->bindValue(':title', $article['title']);
        $this->statementCreateOne->bindValue(':category', $article['category']);
        $this->statementCreateOne->bindValue(':content', $article['content']);
        $this->statementCreateOne->bindValue(':image', $article['image']);
        $this->statementCreateOne->execute();
        return $this->getOne($this->pdo->lastInsertId());
        
    }

    function getAll(){
        $this->statementReadAll->execute();
        return $this->statementReadAll->fetchAll();
    }

    function getOne(int $id) : array {
        $this->statementReadOne->bindValue(':id', $id);
        $this->statementReadOne->execute();
        return $this->statementReadOne->fetch();
    }

    function deleteOne(int $id) : int {
        $this->statementDeleteOne->bindValue(':id', $id);
        $this->statementDeleteOne->execute();
        return $id;
    }

    function updateOne(array $article){
        $this->statementUpdateOne->bindValue(':title', $article['title']);
        $this->statementUpdateOne->bindValue(':category', $article['category']);
        $this->statementUpdateOne->bindValue(':content', $article['content']);
        $this->statementUpdateOne->bindValue(':image', $article['image']);
        $this->statementUpdateOne->bindValue(':id', $article['id']);
        $this->statementUpdateOne->execute();
        return $this->getOne($article['id']);
    }

}


return new ArticleDAO($pdo);
