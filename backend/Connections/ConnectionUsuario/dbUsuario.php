<?php

function dbUsuarioConnection() {
    try{
    return new PDO("mysql:host=" . $_ENV['DBUSUARIO_HOST'] . ";dbname=" . $_ENV['DBUSUARIO_NAME'],
    $_ENV['DBUSUARIO_USER'], $_ENV['DBUSUARIO_PASS'], 
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    }catch(PDOException $e){
        die("Erro ao conectar ao banco do usuário" . $e);
    }
}