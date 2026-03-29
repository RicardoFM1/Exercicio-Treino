<?php

use Dotenv\Dotenv;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../Controllers/ProdutoControllers/ProdutoController.php";

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

if(!isset($_SESSION['usuario_id'])){
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não autenticado'
    ]);
    exit();
}

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


