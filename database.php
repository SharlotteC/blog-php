<?php

$dns = 'mysql:host=localhost:3306;dbname=blog';
$user = 'root';
$pwd = '';

try{
    $pdo = new PDO($dns, $user, $pwd, [

        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    
    ]);
}catch (PDOException $e) {

    echo "ERROR!:". $e->getMessage();
}

return $pdo;