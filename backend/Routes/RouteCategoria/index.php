<?php

use Dotenv\Dotenv;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../Controllers/CategoriaControllers/CategoriaController.php";

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

if(isset($_SESSION['usuario_role']) && $_SESSION['usuario_role']  === 'usuario'){
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Usuario sem permissão'
    ]);
    exit();
}

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


