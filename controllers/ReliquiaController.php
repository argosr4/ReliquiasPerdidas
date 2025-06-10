<?php
namespace Controllers;

use Services\ReliquiaService;
use Generic\MysqlSingleton;
use Middleware\Auth; // NOVO: Importa o middleware de autenticação
use Exception;

class ReliquiaController {
    private $reliquiaService;

    public function __construct($db = null) {
        $db = $db ?? MysqlSingleton::getInstance()->conexao;
        $this->reliquiaService = new ReliquiaService($db);
    }

    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // --- ROTAS PÚBLICAS (GET) ---
    public function getAllReliquias() {
        try {
            $reliquias = $this->reliquiaService->obterTodasReliquias();
            $this->sendResponse(200, $reliquias);
        } catch (Exception $e) {
            $this->sendResponse(500, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }

    public function getReliquia($id) {
        try {
            $reliquia = $this->reliquiaService->obterReliquia($id);
            $this->sendResponse(200, $reliquia);
        } catch (Exception $e) {
            $this->sendResponse(404, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }

    // --- ROTAS PROTEGIDAS (POST, PUT, DELETE) ---
    public function createReliquia() {
        try {
            Auth::validateToken(); // ALTERAÇÃO: Protege a rota
            $input = json_decode(file_get_contents('php://input'), true);
            $result = $this->reliquiaService->criarReliquia($input);
            $this->sendResponse(201, $result);
        } catch (Exception $e) {
            $code = $e->getMessage() === "Acesso não autorizado" ? 401 : 400;
            $this->sendResponse($code, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }

    public function updateReliquia($id) {
        try {
            Auth::validateToken(); // ALTERAÇÃO: Protege a rota
            $input = json_decode(file_get_contents('php://input'), true);
            $result = $this->reliquiaService->atualizarReliquia($id, $input);
            $this->sendResponse(200, $result);
        } catch (Exception $e) {
            $code = $e->getMessage() === "Acesso não autorizado" ? 401 : 400;
            $this->sendResponse($code, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }

    public function deleteReliquia($id) {
        try {
            Auth::validateToken(); // ALTERAÇÃO: Protege a rota
            $result = $this->reliquiaService->deletarReliquia($id);
            $this->sendResponse(200, $result);
        } catch (Exception $e) {
            $code = $e->getMessage() === "Acesso não autorizado" ? 401 : 400;
            $this->sendResponse($code, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }
}