<?php

use Dotenv\Dotenv;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../Controllers/CategoriaControllers/CategoriaController.php";
require_once __DIR__ . "/../../Middlewares/authMiddleware.php";


$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();


$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];


$middleware = new MiddlewareUsuario();
$validarAutenticacao = $middleware::verificarMiddlewareUsuario('Categoria');

if($requestPath === "/categorias"){
    $categoriaController = new CategoriaController();
    if($requestMethod === "GET"){
        $categoriaController->listarCategorias();
    }

    if($requestMethod === "POST"){
        $categoriaController->criarCategoria();
    }

    if($requestMethod === "PUT"){
        $categoriaController->atualizarCategoria();
    }

    if($requestMethod === "DELETE"){
        $categoriaController->deletarCategoria();
    }
}


