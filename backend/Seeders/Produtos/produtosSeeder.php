<?php

use Dotenv\Dotenv;

require_once __DIR__ . "/../../Connections/ConnectionProduto/dbProduto.php";
require_once __DIR__ . "/../../Connections/ConnectionCategoria/dbCategoria.php";


require_once __DIR__ . "/../../vendor/autoload.php";


$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

function seedProdutos()
{
    $produtoDb = dbProdutoConnection();
    $categoriaDb = dbCategoriaConnection();


    $produtos = [
        ["titulo" => "Arroz 5kg", "categoria_id" => 1],
        ["titulo" => "Feijão 1kg", "categoria_id" => 1],
        ["titulo" => "Macarrão", "categoria_id" => 1],
        ["titulo" => "Óleo de soja", "categoria_id" => 1],
        ["titulo" => "Açúcar", "categoria_id" => 1],
        ["titulo" => "Sal", "categoria_id" => 1],
        ["titulo" => "Café", "categoria_id" => 1],
        ["titulo" => "Leite", "categoria_id" => 1],
        ["titulo" => "Pão", "categoria_id" => 1],
        ["titulo" => "Manteiga", "categoria_id" => 1],
        ["titulo" => "Queijo", "categoria_id" => 1],
        ["titulo" => "Presunto", "categoria_id" => 1],
        ["titulo" => "Frango", "categoria_id" => 1],
        ["titulo" => "Carne bovina", "categoria_id" => 1],
        ["titulo" => "Ovos", "categoria_id" => 1],
        ["titulo" => "Tomate", "categoria_id" => 10],
        ["titulo" => "Alface", "categoria_id" => 1],
        ["titulo" => "Batata", "categoria_id" => 1],
        ["titulo" => "Cenoura", "categoria_id" => 1],
        ["titulo" => "Banana", "categoria_id" => 1],
        ["titulo" => "Maçã", "categoria_id" => 1],
        ["titulo" => "Laranja", "categoria_id" => 1],
        ["titulo" => "Refrigerante", "categoria_id" => 1],
        ["titulo" => "Suco", "categoria_id" => 1],
        ["titulo" => "Biscoito", "categoria_id" => 1],
        ["titulo" => "Chocolate", "categoria_id" => 1],
        ["titulo" => "Iogurte", "categoria_id" => 1],
        ["titulo" => "Cereal", "categoria_id" => 1],
        ["titulo" => "Farinha de trigo", "categoria_id" => 1],
        ["titulo" => "Molho de tomate", "categoria_id" => 1]
    ];
    try {

        $findCategoriaExistente = $categoriaDb->prepare("SELECT id FROM categorias WHERE id = :id");
        $idsCategoriasNaoExistentes = [];

        foreach ($produtos as $produto) {

            $findCategoriaExistente->execute([':id' => $produto['categoria_id']]);
            $categoria = $findCategoriaExistente->fetch();
            if (empty($categoria)) {
                $idsCategoriasNaoExistentes[] = $produto['categoria_id'];
            }
        }

        if (!empty($idsCategoriasNaoExistentes)) {
            echo ("Alguns IDs de categorias não existem, dentre eles estão: \n");

            echo implode(",", $idsCategoriasNaoExistentes);
            exit();
        }

        $stmt = $produtoDb->prepare("INSERT INTO produtos (titulo, categoria_id) 
    VALUES (:titulo, :categoria_id)");


        foreach ($produtos as $produto) {

            $stmt->execute([
                ':titulo' => $produto['titulo'],
                ':categoria_id' => $produto['categoria_id']

            ]);
        }
        echo 'Produtos criados com sucesso';
    } catch (PDOException $e) {
        echo "Erro ao inserir Produtos";
    }
}

seedProdutos();
