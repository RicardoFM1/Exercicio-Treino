<?php

use Dotenv\Dotenv;

require_once __DIR__ . "/../../Connections/ConnectionUsuario/dbUsuario.php";

require_once __DIR__ . "/../../vendor/autoload.php";


$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

function seedUsuarios()
{
    $usuarioDb = dbUsuarioConnection();

    $usuarios = [
        [
            "nome" => "Ricardo Moura",
            "email" => "ricardo@admin.com",
            "senha" => "12345678",
            "role" => "admin"
        ],
        [
            "nome" => "Ana Souza",
            "email" => "ana@admin.com",
            "senha" => "12345678",
            "role" => "admin"
        ],
        [
            "nome" => "Carlos Silva",
            "email" => "carlos@email.com",
            "senha" => "12345678",
            "role" => "usuario"
        ],
        [
            "nome" => "Mariana Lima",
            "email" => "mariana@email.com",
            "senha" => "12345678",
            "role" => "usuario"
        ],
        [
            "nome" => "João Pedro",
            "email" => "joao@email.com",
            "senha" => "12345678",
            "role" => "usuario"
        ],
        [
            "nome" => "Fernanda Alves",
            "email" => "fernanda@email.com",
            "senha" => "12345678",
            "role" => "usuario"
        ],
        [
            "nome" => "Lucas Rocha",
            "email" => "lucas@email.com",
            "senha" => "12345678",
            "role" => "usuario"
        ],
        [
            "nome" => "Beatriz Costa",
            "email" => "beatriz@email.com",
            "senha" => "12345678",
            "role" => "usuario"
        ],
        [
            "nome" => "Gabriel Martins",
            "email" => "gabriel@email.com",
            "senha" => "12345678",
            "role" => "usuario"
        ],
        [
            "nome" => "Juliana Pereira",
            "email" => "juliana@email.com",
            "senha" => "12345678",
            "role" => "usuario"
        ]
    ];

    $emailJaExistente = $usuarioDb->prepare("SELECT id FROM usuarios WHERE email = :email");
    $usuariosJaExistentes = [];

    foreach ($usuarios as $usuario) {

        $emailJaExistente->execute([
            ':email' => $usuario['email']
        ]);
        $usuarioFind = $emailJaExistente->fetch();

        if (!empty($usuarioFind)) {
            $usuariosJaExistentes[] = ['nome' => $usuario['nome'], 'email' => $usuario['email']];
        }
    }
 

    if (!empty($usuariosJaExistentes)) {
        echo "Alguns usuários já existem no banco, dentre eles estão: \n";
        echo json_encode($usuariosJaExistentes, JSON_PRETTY_PRINT);
        exit();
    }

    $stmt = $usuarioDb->prepare("INSERT INTO usuarios (nome, email, senha, role) 
    VALUES (:nome, :email, :senha, :role)");


    foreach ($usuarios as $usuario) {

        $stmt->execute([
            ':nome' => $usuario['nome'],
            ':email' => $usuario['email'],
            ':senha' => password_hash($usuario['senha'], PASSWORD_DEFAULT),
            ':role' => $usuario['role']
        ]);
    }
    echo 'Usuarios criados com sucesso';
}

seedUsuarios();
