<?php


require_once __DIR__ . "/../../Services/ProdutoServices/ProdutoService.php";

class ProdutoController {

    protected $produtoService;

    public function __construct()
    {
        $this->produtoService = new ProdutoService();
    }

    public function listarProdutos(){
    echo json_encode($this->produtoService->listarProdutos());
    }

    public function criarProduto(){
    $produtoDados = json_decode(file_get_contents("php://input"), true) ?? null;
    echo json_encode($this->produtoService->criarProduto($produtoDados));
    }

      public function atualizarProduto(){
    $produtoDados = json_decode(file_get_contents("php://input"), true) ?? null;
    $produtoId = $_GET['produto_id'];
    echo json_encode($this->produtoService->atualizarProduto($produtoDados, $produtoId));
    }

    public function deletarProduto(){
    $produtoId = $_GET['produto_id'];

    echo json_encode($this->produtoService->deletarProduto($produtoId));
    }
}
