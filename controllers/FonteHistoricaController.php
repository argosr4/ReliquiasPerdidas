<?php
namespace Controllers;

use Services\FonteHistoricaService;
use Generic\MysqlSingleton;
use Middleware\Auth; // NOVO: Importa o middleware de autenticação
use Exception;

class FonteHistoricaController {
    private $fonteService;

    public function __construct($db = null) {
        $db = $db ?? MysqlSingleton::getInstance()->conexao;
        $this->fonteService = new FonteHistoricaService($db);
    }

    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // --- ROTAS PÚBLICAS (GET) ---
    public function getFonte($id) {
        try {
            $fonte = $this->fonteService->obterFonte($id);
            $this->sendResponse(200, $fonte);
        } catch (Exception $e) {
            $this->sendResponse(404, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }
    
    public function getFontesByReliquia($reliquia_id) {
        try {
            $fontes = $this->fonteService->obterFontesPorReliquia($reliquia_id);
            $this->sendResponse(200, $fontes);
        } catch (Exception $e) {
            $this->sendResponse(400, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }
    
    // --- ROTAS PROTEGIDAS (POST, PUT, DELETE) ---
    public function createFonte() {
        try {
            Auth::validateToken(); // ALTERAÇÃO: Protege a rota
            $input = json_decode(file_get_contents('php://input'), true);
            $result = $this->fonteService->criarFonte($input);
            $this->sendResponse(201, $result);
        } catch (Exception $e) {
            $code = $e->getMessage() === "Acesso não autorizado" ? 401 : 400;
            $this->sendResponse($code, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }
    
    public function updateFonte($id) {
        try {
            Auth::validateToken(); // ALTERAÇÃO: Protege a rota
            $input = json_decode(file_get_contents('php://input'), true);
            $result = $this->fonteService->atualizarFonte($id, $input);
            $this->sendResponse(200, $result);
        } catch (Exception $e) {
            $code = $e->getMessage() === "Acesso não autorizado" ? 401 : 400;
            $this->sendResponse($code, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }
    
    public function deleteFonte($id) {
        try {
            Auth::validateToken(); // ALTERAÇÃO: Protege a rota
            $result = $this->fonteService->deletarFonte($id);
            $this->sendResponse(200, $result);
        } catch (Exception $e) {
            $code = $e->getMessage() === "Acesso não autorizado" ? 401 : 400;
            $this->sendResponse($code, ["erro" => $e->getMessage(), "dado" => null]);
        }
    }
}