<?php
namespace controllers;

use service\ReliquiaService;
use generic\MysqlSingleton; // Usar o Singleton para obter a conexão

class ReliquiaController {
    private $reliquiaService;

    public function __construct($db = null) {
        // Se a conexão não for passada, tenta obter do Singleton
        if ($db === null) {
            $db = MysqlSingleton::getInstance()->conexao;
        }
        $this->reliquiaService = new ReliquiaService($db);
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));

        try {
            switch ($method) {
                case 'GET':
                    // GET /api/reliquias/{id}
                    if (isset($segments[2]) && is_numeric($segments[2])) {
                        $this->getReliquia($segments[2]);
                    } 
                    // GET /api/reliquias?buscar=nome&nome={nome}
                    elseif (isset($_GET['buscar']) && $_GET['buscar'] === 'nome' && isset($_GET['nome'])) {
                        $this->buscarPorNome($_GET['nome']);
                    } 
                    // GET /api/reliquias?buscar=epoca&epoca={epoca}
                    elseif (isset($_GET['buscar']) && $_GET['buscar'] === 'epoca' && isset($_GET['epoca'])) {
                        $this->buscarPorEpoca($_GET['epoca']);
                    } 
                    // GET /api/reliquias
                    else {
                        $this->getAllReliquias();
                    }
                    break;

                case 'POST':
                    // POST /api/reliquias
                    $this->createReliquia();
                    break;

                case 'PUT':
                    // PUT /api/reliquias/{id}
                    if (isset($segments[2]) && is_numeric($segments[2])) {
                        $this->updateReliquia($segments[2]);
                    } else {
                        $this->sendResponse(400, ["error" => "ID é obrigatório para atualização de relíquia"]);
                    }
                    break;

                case 'DELETE':
                    // DELETE /api/reliquias/{id}
                    if (isset($segments[2]) && is_numeric($segments[2])) {
                        $this->deleteReliquia($segments[2]);
                    } else {
                        $this->sendResponse(400, ["error" => "ID é obrigatório para exclusão de relíquia"]);
                    }
                    break;

                default:
                    $this->sendResponse(405, ["error" => "Método não permitido"]);
                    break;
            }
        } catch (\Exception $e) {
            $this->sendResponse(400, ["error" => $e->getMessage()]);
        }
    }

    private function getAllReliquias() {
        $reliquias = $this->reliquiaService->obterTodasReliquias();
        $this->sendResponse(200, $reliquias);
    }

    private function getReliquia($id) {
        $reliquia = $this->reliquiaService->obterReliquia($id);
        $this->sendResponse(200, $reliquia);
    }

    private function createReliquia() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Corpo da requisição JSON inválido.");
        }
        $result = $this->reliquiaService->criarReliquia($input);
        $this->sendResponse(201, $result);
    }

    private function updateReliquia($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Corpo da requisição JSON inválido.");
        }
        $result = $this->reliquiaService->atualizarReliquia($id, $input);
        $this->sendResponse(200, $result);
    }

    private function deleteReliquia($id) {
        $result = $this->reliquiaService->deletarReliquia($id);
        $this->sendResponse(200, $result);
    }

    private function buscarPorNome($nome) {
        $reliquias = $this->reliquiaService->buscarPorNome($nome);
        $this->sendResponse(200, $reliquias);
    }

    private function buscarPorEpoca($epoca) {
        $reliquias = $this->reliquiaService->buscarPorEpoca($epoca);
        $this->sendResponse(200, $reliquias);
    }

    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
?>