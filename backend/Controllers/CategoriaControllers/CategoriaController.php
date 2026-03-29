<?php


require_once __DIR__ . "/../../Services/CategoriaServices/CategoriaService.php";

class CategoriaController {

    protected $categoriaService;

    public function __construct()
    {
        $this->categoriaService = new CategoriaService();
    }

    public function listarCategorias(){
    echo json_encode($this->categoriaService->listarCategorias());
    }

    public function criarCategoria(){
    $categoriaDados = json_decode(file_get_contents("php://input"), true) ?? null;
    echo json_encode($this->categoriaService->criarCategoria($categoriaDados));
    }

      public function atualizarCategoria(){
    $categoriaDados = json_decode(file_get_contents("php://input"), true) ?? null;
    $categoriaId = $_GET['categoria_id'];
    echo json_encode($this->categoriaService->atualizarCategoria($categoriaDados, $categoriaId));
    }

    public function deletarCategoria(){
    $categoriaId = $_GET['categoria_id'];
    echo json_encode($this->categoriaService->deletarCategoria($categoriaId));
    }
}
