<?php


require_once __DIR__ . "/../../Services/ProdutoServices/ProdutoService.php";

class ProdutoController
{

    protected $produtoService;

    public function __construct()
    {
        $this->produtoService = new ProdutoService();
    }

    public function listarProdutos()
    {
        echo json_encode($this->produtoService->listarProdutos());
    }

    public function criarProduto()
    {
        $produtoDados = json_decode(file_get_contents("php://input"), true) ?? null;

        $tokenJWT = null;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $tokenJWT = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['AUTHORIZATION'])) {
            $tokenJWT = $_SERVER['AUTHORIZATION'];
        }
        echo json_encode($this->produtoService->criarProduto($produtoDados, $tokenJWT));
    }

    public function atualizarProduto()
    {
        $produtoDados = json_decode(file_get_contents("php://input"), true) ?? null;
        $produtoId = $_GET['produto_id'];
        $tokenJWT = null;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $tokenJWT = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['AUTHORIZATION'])) {
            $tokenJWT = $_SERVER['AUTHORIZATION'];
        }

        echo json_encode($this->produtoService->atualizarProduto($produtoDados, $produtoId, $tokenJWT));
    }

    public function deletarProduto()
    {
        $produtoId = $_GET['produto_id'];
        $tokenJWT = null;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $tokenJWT = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['AUTHORIZATION'])) {
            $tokenJWT = $_SERVER['AUTHORIZATION'];
        }

        echo json_encode($this->produtoService->deletarProduto($produtoId, $tokenJWT));
    }
}
