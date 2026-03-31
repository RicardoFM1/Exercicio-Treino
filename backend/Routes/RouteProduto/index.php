<?php

use Dotenv\Dotenv;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../Controllers/ProdutoControllers/ProdutoController.php";
require_once __DIR__ . '/../../Middlewares/authMiddleware.php';

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();


$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$middleware = new MiddlewareUsuario();
$validarAutenticacao = $middleware::verificarMiddlewareUsuario('Produto');

if($requestPath === "/produtos"){
    $produtoController = new ProdutoController();
    if($requestMethod === "GET"){
        $produtoController->listarProdutos();
    }

    if($requestMethod === "POST"){
        $produtoController->criarProduto();
    }

    if($requestMethod === "PUT"){
        $produtoController->atualizarProduto();
    }

    if($requestMethod === "DELETE"){
        $produtoController->deletarProduto();
    }
}


