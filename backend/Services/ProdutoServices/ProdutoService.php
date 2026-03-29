<?php

require_once __DIR__ . "/../../Connections/ConnectionProduto/dbProduto.php";
require_once __DIR__ . "/../../Services/CategoriaServices/CategoriaService.php";

class ProdutoService
{

    protected $produtoDb;

    public function __construct()
    {
        $this->produtoDb = dbProdutoConnection();
    }

    // Utilitários

    public function buscarProdutoPorId($produtoId)
    {
        if (empty($produtoId)) {
            http_response_code(400);
            return [
                'success' => false,
                'message' => 'Id do produto não informado'
            ];
        }



        $produtoFind = $this->produtoDb->prepare("SELECT * FROM produtos WHERE id = :id");
        $produtoFind->execute([':id' => $produtoId]);

        $produto = $produtoFind->fetch();

        if (empty($produto)) {
            http_response_code(404);
            return [
                'success' => false,
                'message' => 'Produto inexistente'
            ];
        } else {
            return [
                'success' => true,
                'dados' => $produto
            ];
        }
    }

    public function listarProdutos()
    {
        $stmt = $this->produtoDb->query("SELECT * FROM produtos");
        $produtos = $stmt->fetchAll();

        return [
            'success' => true,
            'dados' => $produtos
        ];
    }



    public function criarProduto($produtoDados)
    {
        if (empty($produtoDados)) {
            http_response_code(400);
            return [
                'success' => false,
                'message' => 'Dados do produto inválidos ou inexistentes'
            ];
        }

        $stmt = $this->produtoDb->prepare("INSERT INTO produtos (titulo, categoria_id)
        VALUES (:titulo, :categoria_id)");

        $categoriaService = new CategoriaService();
        $categoria = $categoriaService->buscarCategoriaPorId($produtoDados['categoria_id']);

        if (isset($categoria) && $categoria['success'] === false) {
            echo json_encode($categoria);
            exit();
        }

        $stmt->execute([
            ':titulo' => $produtoDados['titulo'],
            ':categoria_id' => $produtoDados['categoria_id']
        ]);

        http_response_code(201);
        return [
            'success' => true,
            'message' => 'Produto criado com sucesso'
        ];
    }



    public function atualizarProduto($produtoDados, $produtoId)
    {
        if (empty($produtoDados) || empty($produtoId)) {
            http_response_code(400);
            return [
                'success' => false,
                'message' => 'Dados do produto ou id inválidos ou inexistentes'
            ];
        }

        $statusFiltrado = '';
        if ($produtoDados['status'] === 'inativo' || $produtoDados['status'] === 'ativo') {
           $statusFiltrado .= $produtoDados['status'];
        } else {
            $statusFiltrado .= 'ativo';
        }

        $produto = $this->buscarProdutoPorId($produtoId);

        if (isset($produto['success']) && $produto['success'] === false) {
            http_response_code(404);
            echo json_encode($produto);
            exit();
        }

        $stmt = $this->produtoDb->prepare("UPDATE produtos set titulo = :titulo, categoria_id = :categoria_id,
        status = :status WHERE id = :id");

        $categoriaService = new CategoriaService();
        $categoria = $categoriaService->buscarCategoriaPorId($produtoDados['categoria_id']);

        if (isset($categoria) && $categoria['success'] === false) {
            echo json_encode($categoria);
            exit();
        }

        $stmt->execute([
            ':titulo' => $produtoDados['titulo'],
            ':categoria_id' => $produtoDados['categoria_id'],
            ':status' => $statusFiltrado,
            ':id' => $produtoId
        ]);

        http_response_code(201);
        return [
            'success' => true,
            'message' => 'Produto atualizado com sucesso'
        ];
    }

    public function deletarProduto($produtoId)
    {
        if (empty($produtoId)) {
            return [
                'success' => false,
                'message' => 'Id do produto não informado'
            ];
        }

        $produto = $this->buscarProdutoPorId($produtoId);

        if (isset($produto['success']) && $produto['success'] === false) {
            http_response_code(404);
            echo json_encode($produto);
            exit();
        }

        $stmt = $this->produtoDb->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->execute([':id' => $produtoId]);
        http_response_code(200);
        return [
            'success' => true,
            'message' => 'Produto deletado com sucesso'
        ];
    }
}
