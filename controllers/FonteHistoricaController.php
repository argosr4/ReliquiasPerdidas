<?php
namespace controllers;

use service\FonteHistoricaService;
use generic\MysqlSingleton; // Usar o Singleton para obter a conexão

class FonteHistoricaController {
    private $fonteService;

    public function __construct($db = null) {
        // Se a conexão não for passada, tenta obter do Singleton
        if ($db === null) {
            $db = MysqlSingleton::getInstance()->conexao;
        }
        $this->fonteService = new FonteHistoricaService($db);
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));

        // Assume que a rota da API é /api/fontes
        // O ID estaria em $segments[2] se a URL fosse /api/fontes/123
        // Ou o reliquia_id estaria em $_GET['reliquia_id']

        try {
            switch ($method) {
                case 'GET':
                    // GET /api/fontes/{id}
                    if (isset($segments[2]) && is_numeric($segments[2])) {
                        $this->getFonte($segments[2]);
                    } 
                    // GET /api/fontes?reliquia_id={id}
                    elseif (isset($_GET['reliquia_id']) && is_numeric($_GET['reliquia_id'])) {
                        $this->getFontesByReliquia($_GET['reliquia_id']);
                    } 
                    // Caso contrário, retorna erro de parâmetros inválidos ou lista todas (se implementado)
                    else {
                        $this->sendResponse(400, ["error" => "Parâmetros inválidos para GET em fontes. Use /api/fontes/{id} ou /api/fontes?reliquia_id={id}."]);
                    }
                    break;

                case 'POST':
                    // POST /api/fontes
                    $this->createFonte();
                    break;

                case 'PUT':
                    // PUT /api/fontes/{id}
                    if (isset($segments[2]) && is_numeric($segments[2])) {
                        $this->updateFonte($segments[2]);
                    } else {
                        $this->sendResponse(400, ["error" => "ID é obrigatório para atualização de fonte histórica"]);
                    }
                    break;

                case 'DELETE':
                    // DELETE /api/fontes/{id}
                    if (isset($segments[2]) && is_numeric($segments[2])) {
                        $this->deleteFonte($segments[2]);
                    } else {
                        $this->sendResponse(400, ["error" => "ID é obrigatório para exclusão de fonte histórica"]);
                    }
                    break;

                default:
                    $this->sendResponse(405, ["error" => "Método não permitido"]);
                    break;
            }
        } catch (\Exception $e) { // Captura qualquer tipo de Exception
            $this->sendResponse(400, ["error" => $e->getMessage()]);
        }
    }

    private function getFonte($id) {
        $fonte = $this->fonteService->obterFonte($id);
        $this->sendResponse(200, $fonte);
    }

    private function getFontesByReliquia($reliquia_id) {
        $fontes = $this->fonteService->obterFontesPorReliquia($reliquia_id);
        $this->sendResponse(200, $fontes);
    }

    private function createFonte() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Corpo da requisição JSON inválido.");
        }
        $result = $this->fonteService->criarFonte($input);
        $this->sendResponse(201, $result);
    }

    private function updateFonte($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Corpo da requisição JSON inválido.");
        }
        $result = $this->fonteService->atualizarFonte($id, $input);
        $this->sendResponse(200, $result);
    }

    private function deleteFonte($id) {
        $result = $this->fonteService->deletarFonte($id);
        $this->sendResponse(200, $result);
    }

    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
?>