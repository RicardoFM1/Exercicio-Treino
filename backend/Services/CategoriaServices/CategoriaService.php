<?php

require_once __DIR__ . "/../../Connections/ConnectionCategoria/dbCategoria.php";

class CategoriaService
{

    protected $categoriaDb;

    public function __construct()
    {
        $this->categoriaDb = dbCategoriaConnection();
    }

    // Utilitários

    public function buscarCategoriaPorId($categoriaId)
    {
        if (empty($categoriaId)) {
            http_response_code(400);
            return [
                'success' => false,
                'message' => 'Id da categoria não informado'
            ];
        }

        $categoriaFind = $this->categoriaDb->prepare("SELECT * FROM categorias WHERE id = :id");
        $categoriaFind->execute([':id' => $categoriaId]);

        $categoria = $categoriaFind->fetch();

        if (isset($categoria) && $categoria === false) {
            http_response_code(404);
            return [
                'success' => false,
                'message' => 'Categoria inexistente'
            ];
        } else {
            return [
                'success' => true,
                'dados' => $categoria
            ];
        }
    }

    public function listarCategorias()
    {
        $stmt = $this->categoriaDb->query("SELECT * FROM categorias");
        $categorias = $stmt->fetchAll();

        return [
            'success' => true,
            'dados' => $categorias
        ];
    }



    public function criarCategoria($categoriaDados)
    {

        $stmt = $this->categoriaDb->prepare("INSERT INTO categorias (titulo) VALUES (:titulo)");

        $stmt->execute([
            ':titulo' => $categoriaDados['titulo']
        ]);

        http_response_code(201);
        return [
            'success' => true,
            'message' => 'Categoria criada com sucesso'
        ];
    }



    public function atualizarCategoria($categoriaDados, $categoriaId)
    {
        if (empty($categoriaDados) || empty($categoriaId)) {
            http_response_code(400);
            return [
                'success' => false,
                'message' => 'Id ou dados da categoria inexistentes/inválidos'
            ];
        }

        $statusFiltrado = '';
        if ($categoriaDados['status'] === 'inativo' || $categoriaDados['status'] === 'ativo') {
            $statusFiltrado .= $categoriaDados['status'];
        } else {
            $statusFiltrado .= 'ativo';
        }


        $categoria = $this->buscarCategoriaPorId($categoriaId);

        if (isset($categoria['success']) && $categoria['success'] === false) {
            http_response_code(404);
            echo json_encode($categoria);
            exit();
        }

        $stmt = $this->categoriaDb->prepare("UPDATE categorias set titulo = :titulo, status = :status WHERE id = :id");

        $stmt->execute([
            ':titulo' => $categoriaDados['titulo'],
            ':status' => $statusFiltrado,
            ':id' => $categoriaId
        ]);

        http_response_code(201);
        return [
            'success' => true,
            'message' => 'Categoria atualizada com sucesso'
        ];
    }

    public function deletarCategoria($categoriaId)
    {
        if (empty($categoriaId)) {
            return [
                'success' => false,
                'message' => 'Id da categoria não informado'
            ];
        }

        $categoria = $this->buscarCategoriaPorId($categoriaId);

        if (isset($categoria['success']) && $categoria['success'] === false) {
            http_response_code(404);
            echo json_encode($categoria);
            exit();
        }

        $stmt = $this->categoriaDb->prepare("DELETE FROM categorias WHERE id = :id");
        $stmt->execute([':id' => $categoriaId]);
        http_response_code(200);
        return [
            'success' => true,
            'message' => 'Categoria deletada com sucesso'
        ];
    }
}
