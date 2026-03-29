<?php

function dbProdutoConnection() {
    try{
    return new PDO("mysql:host=" . $_ENV['DBPRODUTO_HOST'] . ";dbname=" . $_ENV['DBPRODUTO_NAME'],
    $_ENV['DBPRODUTO_USER'], $_ENV['DBPRODUTO_PASS'], 
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    }catch(PDOException $e){
        die("Erro ao conectar ao banco do produto" . $e);
    }
}