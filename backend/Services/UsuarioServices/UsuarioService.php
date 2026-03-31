<?php

use Firebase\JWT\JWT;

require_once __DIR__ . "/../../Connections/ConnectionUsuario/dbUsuario.php";

class UsuarioService {

    protected $usuarioDb;

    public function __construct()
    {
        $this->usuarioDb = dbUsuarioConnection();
    }

    // Utilitários 

    public function buscarUsuarioPorEmail ($usuarioEmail) {
    if (empty($usuarioEmail)) {
        http_response_code(400);
        return [
            'success' => false,
            'message' => 'Email do usuário não informado'
        ];
    }

    $usuarioFind = $this->usuarioDb->prepare("SELECT id, nome, email, senha, role FROM usuarios WHERE email = :email");
    $usuarioFind->execute([':email' => $usuarioEmail]);

    $usuario = $usuarioFind->fetch();

    if(isset($usuario) && $usuario === false){
        http_response_code(404);
        return [
            'success' => false,
            'message' => 'Credenciais inválidas'
        ];
      
    }else{
        return [
            'success' => true,
            'dados' => $usuario
        ];
    }
    }

    public function listarUsuarios(){
        $stmt = $this->usuarioDb->query("SELECT id, nome, email, role FROM usuarios");
        $usuarios = $stmt->fetchAll();

        return [
            'success' => true,
            'dados' => $usuarios
        ];
    }

    public function criarUsuario($userData){

        $emailJaExistente = $this->buscarUsuarioPorEmail($userData['email']);

        if(isset($emailJaExistente['success']) && $emailJaExistente['success'] === true){
            http_response_code(401);
            echo json_encode(['message' => 'Email já existente']);
            exit();
        }


        $stmt = $this->usuarioDb->prepare("INSERT INTO usuarios (nome, email, senha)
        VALUES (:nome, :email, :senha)");
        

        $stmt->execute([':nome' => $userData['nome'], ':email' => $userData['email'],
        ':senha' => password_hash($userData['senha'], PASSWORD_DEFAULT)]);

        http_response_code(201);
        return [
            'success' => true,
            'message' => 'Usuario criado com sucesso'
        ];
    }

    public function loginUsuario($userData){

        $usuario = $this->buscarUsuarioPorEmail($userData['email']);

        if(isset($usuario['success']) && $usuario['success'] === false){
            echo json_encode($usuario);
            exit();
        }
        
        $senhaValida = password_verify($userData['senha'], $usuario['dados']['senha']);
        
        if(!$senhaValida){
            http_response_code(401);
            return [
                'success' => false,
                'message' => 'Credenciais inválidas'
            ];
        }
        $payload = [
            'exp' => time() + 3600,
            'data' => [
                'usuario_id' => $usuario['dados']['id'],
                'email' => $usuario['dados']['email'],
                'role' => $usuario['dados']['role']
            ] 
        ];

        $secretKey = $_ENV['JWT_SECRET_KEY'];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        http_response_code(200);
        return [
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'token' => $jwt
        ];

    }

    

}
