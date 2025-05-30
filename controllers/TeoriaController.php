<?php
namespace controllers;

use service\TeoriaService;
use generic\MysqlSingleton; // Usar o Singleton para obter a conexão

class TeoriaController {
    private $teoriaService;

    public function __construct($db = null) {
        // Se a conexão não for passada, tenta obter do Singleton
        if ($db === null) {
            $db = MysqlSingleton::getInstance()->conexao;
        }
        $this->teoriaService = new TeoriaService($db);
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));

        try {
            switch ($method) {
                case 'GET':
                    // GET /api/teorias/{id}
                    if (isset($segments[2]) && is_numeric($segments[2])) {
                        $this->getTeoria($segments[2]);
                    } 
                    // GET /api/teorias?reliquia_id={id}
                    elseif (isset($_GET['reliquia_id']) && is_numeric($_GET['reliquia_id'])) {
                        $this->getTeoriasByReliquia($_GET['reliquia_id']);
                    } 
                    // Caso contrário, retorna erro de parâmetros inválidos ou lista todas (se implementado)
                    else {
                        $this->sendResponse(400, ["error" => "Parâmetros inválidos para GET em teorias. Use /api/teorias/{id} ou /api/teorias?reliquia_id={id}."]);
                    }
                    break;

                case 'POST':
                    // POST /api/teorias
                    $this->createTeoria();
                    break;

                case 'PUT':
                    // PUT /api/teorias/{id}
                    if (isset($segments[2]) && is_numeric($segments[2])) {
                        $this->updateTeoria($segments[2]);
                    } else {
                        $this->sendResponse(400, ["error" => "ID é obrigatório para atualização de teoria"]);
                    }
                    break;

                case 'DELETE':
                    // DELETE /api/teorias/{id}
                    if (isset($segments[2]) && is_numeric($segments[2])) {
                        $this->deleteTeoria($segments[2]);
                    } else {
                        $this->sendResponse(400, ["error" => "ID é obrigatório para exclusão de teoria"]);
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

    private function getTeoria($id) {
        $teoria = $this->teoriaService->obterTeoria($id);
        $this->sendResponse(200, $teoria);
    }

    private function getTeoriasByReliquia($reliquia_id) {
        $teorias = $this->teoriaService->obterTeoriasPorReliquia($reliquia_id);
        $this->sendResponse(200, $teorias);
    }

    private function createTeoria() {
        // A sessão já deve ter sido iniciada no index.php
        if (!isset($_SESSION['user'])) {
            $this->sendResponse(401, ["error" => "Usuário não autenticado"]);
        }
        $user = $_SESSION['user'];
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Corpo da requisição JSON inválido.");
        }
        $result = $this->teoriaService->criarTeoria($input, $user['id']);
        $this->sendResponse(201, $result);
    }

    private function updateTeoria($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Corpo da requisição JSON inválido.");
        }
        $result = $this->teoriaService->atualizarTeoria($id, $input);
        $this->sendResponse(200, $result);
    }

    private function deleteTeoria($id) {
        $result = $this->teoriaService->deletarTeoria($id);
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