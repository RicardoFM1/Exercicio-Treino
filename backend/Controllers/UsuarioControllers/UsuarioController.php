<?php


require_once __DIR__ . "/../../Services/UsuarioServices/UsuarioService.php";

class UsuarioController {

    protected $usuarioService;

    public function __construct()
    {
        $this->usuarioService = new UsuarioService();
    }

    public function listarUsuarios(){
    echo json_encode($this->usuarioService->listarUsuarios());
    }

    public function criarUsuario(){
    $userDados = json_decode(file_get_contents("php://input"), true) ?? null;
    echo json_encode($this->usuarioService->criarUsuario($userDados));
    }

    public function loginUsuario(){
    $userDados = json_decode(file_get_contents("php://input"), true) ?? null;
    echo json_encode($this->usuarioService->loginUsuario($userDados));
    }

    public function logoutUsuario (){
    echo json_encode($this->usuarioService->logoutUsuario());
    }
}
