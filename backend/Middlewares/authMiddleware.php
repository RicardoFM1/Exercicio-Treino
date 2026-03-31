<?php

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MiddlewareUsuario
{

    public static  function verificarMiddlewareUsuario($route)
    {
        $authHeader = null;
        $secretKey = $_ENV['JWT_SECRET_KEY'];


        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authHeader = trim($_SERVER['HTTP_AUTHORIZATION']);
        } elseif (isset($_SERVER['AUTHORIZATION'])) {
            $authHeader = trim($_SERVER['AUTHORIZATION']);
        }
        

        if (!$authHeader) {
            http_response_code(401);
            echo json_encode(
                [
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ]
            );
            exit;
        }


        $partesJwtHeader = explode(' ', $authHeader);
        if (count($partesJwtHeader) !== 2 || strcasecmp($partesJwtHeader[0], 'Bearer') !== 0) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Formato do token inválido, esperado: Bearer {token}'
            ]);
            exit;
        }

        $parteStringJwt = $partesJwtHeader[1];

        try {

            $jwtDecoded = JWT::decode($parteStringJwt, new Key($secretKey, 'HS256'));

            if ($jwtDecoded->data->role !== 'admin' && $route !== 'Produto') {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Usuario sem permissão'
                ]);
                exit;
            }
           

            http_response_code(200);
            return $jwtDecoded->data;
        } catch (ExpiredException $e) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Token expirado ou inválido'
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Token adulterado ou inválido'
            ]);
            exit;
        }
    }
}
