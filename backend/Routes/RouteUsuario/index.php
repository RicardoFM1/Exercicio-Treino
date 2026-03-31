<?php

use Dotenv\Dotenv;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../Controllers/UsuarioControllers/UsuarioController.php";

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();


$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

if($requestPath === "/usuarios"){
    $usuarioController = new UsuarioController();
    if($requestMethod === "GET"){
        $usuarioController->listarUsuarios();
    }

    if($requestMethod === "POST"){
        $usuarioController->criarUsuario();
    }
}

if($requestPath === "/usuarios/login"){
    $usuarioController = new UsuarioController();

    if($requestMethod === "POST"){
        $usuarioController->loginUsuario();
        
    }
}



