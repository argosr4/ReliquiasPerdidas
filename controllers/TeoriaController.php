<?php
namespace Controllers;

use Services\TeoriaService;
use Generic\MysqlSingleton;
use Middleware\Auth; // NOVO: Importa o middleware de autenticação
use Exception;

class TeoriaController {
    private $teoriaService;

    public function __construct($db = null) {
        $db = $db ?? MysqlSingleton::getInstance()->conexao;
        $this->teoriaService = new TeoriaService($db);
    }
    
    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // --- ROTA PÚBLICA (GET) ---
    public function getTeoriasByReliquia($reliquia_id) {
        try {
            $teorias = $this->teoriaService->obterTeoriasPorReliquia($reliquia_id);
            $this->sendResponse(200, $teorias);
        } catch (Exception $e) {
            $this->sendResponse(400, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }

    // --- ROTAS PROTEGIDAS (POST, PUT, DELETE) ---
    public function createTeoria() {
        try {
            // ALTERAÇÃO: Valida o token e obtém os dados do usuário
            $userData = Auth::validateToken(); 
            
            $input = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Corpo da requisição JSON inválido.");
            }
            
            // Passa o ID do usuário (do token) para o serviço criar a teoria
            $result = $this->teoriaService->criarTeoria($input, $userData['id']);
            $this->sendResponse(201, $result);
        } catch (Exception $e) {
            $code = $e->getMessage() === "Acesso não autorizado" ? 401 : 400;
            $this->sendResponse($code, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }

    public function updateTeoria($id) {
        try {
            Auth::validateToken(); // ALTERAÇÃO: Protege a rota
            $input = json_decode(file_get_contents('php://input'), true);
            $result = $this->teoriaService->atualizarTeoria($id, $input);
            $this->sendResponse(200, $result);
        } catch (Exception $e) {
            $code = $e->getMessage() === "Acesso não autorizado" ? 401 : 400;
            $this->sendResponse($code, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }

    public function deleteTeoria($id) {
        try {
            Auth::validateToken(); // ALTERAÇÃO: Protege a rota
            $result = $this->teoriaService->deletarTeoria($id);
            $this->sendResponse(200, $result);
        } catch (Exception $e) {
            $code = $e->getMessage() === "Acesso não autorizado" ? 401 : 400;
            $this->sendResponse($code, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }
}