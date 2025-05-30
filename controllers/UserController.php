<?php
namespace controllers;

use service\UserService;
use generic\MysqlSingleton; // Usar o Singleton para obter a conexão

class UserController {
    private $userService;

    public function __construct() {
        // A conexão é obtida diretamente pelo UserService via Singleton
        $db = MysqlSingleton::getInstance()->conexao;
        $this->userService = new UserService($db);
        // A sessão já deve ter sido iniciada no index.php
    }

    public function register() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Corpo da requisição JSON inválido.");
            }
            $result = $this->userService->register($data);
            http_response_code(201);
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(400); // 400 Bad Request para erros de validação/negócio
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function login() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Corpo da requisição JSON inválido.");
            }
            $user = $this->userService->login($data['email'], $data['senha']);
            $_SESSION['user'] = $user; // Armazena informações do usuário na sessão
            http_response_code(200);
            echo json_encode(["message" => "Login realizado com sucesso", "user" => $user]);
        } catch (\Exception $e) {
            http_response_code(401); // 401 Unauthorized para falha de login
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function logout() {
        session_destroy(); // Destrói a sessão
        http_response_code(200);
        echo json_encode(["message" => "Logout realizado com sucesso"]);
    }

    public function checkAuth() {
        if (isset($_SESSION['user'])) {
            http_response_code(200);
            echo json_encode(["authenticated" => true, "user" => $_SESSION['user']]);
        } else {
            http_response_code(401);
            echo json_encode(["authenticated" => false, "error" => "Usuário não autenticado"]);
        }
    }
}
?>