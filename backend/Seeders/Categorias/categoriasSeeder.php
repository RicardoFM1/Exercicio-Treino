<?php

use Dotenv\Dotenv;

require_once __DIR__ . "/../../Connections/ConnectionCategoria/dbCategoria.php";

require_once __DIR__ . "/../../vendor/autoload.php";


$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

function seedCategorias()
{
    $categoriaDb = dbCategoriaConnection();

    $dados = [
        ["titulo" => "Alimentação"],
        ["titulo" => "Transporte"],
        ["titulo" => "Moradia"],
        ["titulo" => "Saúde"],
        ["titulo" => "Educação"],
        ["titulo" => "Lazer"],
        ["titulo" => "Tecnologia"],
        ["titulo" => "Roupas"],
        ["titulo" => "Investimentos"],
        ["titulo" => "Salário"],
        ["titulo" => "Freelance"],
        ["titulo" => "Impostos"],
        ["titulo" => "Assinaturas"],
        ["titulo" => "Doações"],
        ["titulo" => "Viagens"],
        ["titulo" => "Manutenção"],
        ["titulo" => "Beleza"],
        ["titulo" => "Pets"],
        ["titulo" => "Presentes"],
        ["titulo" => "Emergências"],
        ["titulo" => "Academia"],
        ["titulo" => "Streaming"],
        ["titulo" => "Combustível"],
        ["titulo" => "Supermercado"],
        ["titulo" => "Farmácia"],
        ["titulo" => "Restaurantes"],
        ["titulo" => "Cursos"],
        ["titulo" => "Jogos"],
        ["titulo" => "Hobbies"],
        ["titulo" => "Outros"]
    ];

    $stmt = $categoriaDb->prepare("INSERT INTO categorias (titulo) VALUES (:titulo)");

    foreach($dados as $dado){

    $stmt->execute([
        ':titulo' => $dado['titulo']
    ]);

    
    
    }
    echo 'Categorias criadas com sucesso';
}
seedCategorias();