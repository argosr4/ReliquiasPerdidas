<?php
namespace Controllers;

use Services\UserService;
use Generic\MysqlSingleton;
use Exception;

class UserController {
    private $userService;

    public function __construct() {
        // Instancia o serviço com a conexão do Singleton
        $this->userService = new UserService(MysqlSingleton::getInstance()->conexao);
    }

    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function register() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Corpo da requisição JSON inválido.");
            }
            $result = $this->userService->register($data);
            $this->sendResponse(201, $result);
        } catch (Exception $e) {
            $this->sendResponse(400, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }

    public function login() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Corpo da requisição JSON inválido.");
            }
            // ALTERAÇÃO: O serviço agora retorna um token JWT
            $result = $this->userService->login($data['email'], $data['senha']);
            $this->sendResponse(200, $result);
        } catch (Exception $e) {
            // Falha no login retorna 401 Unauthorized
            $this->sendResponse(401, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }

    public function logout() {
        // ALTERAÇÃO: Logout em JWT é feito no cliente, o endpoint apenas informa.
        $this->sendResponse(200, ["message" => "Logout bem-sucedido. O cliente deve remover o token."]);
    }
}