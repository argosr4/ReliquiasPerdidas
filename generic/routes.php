<?php
namespace Generic;

use Generic\MysqlSingleton;

class Router {
    private $routes = [];
    public function add($method, $path, $handler) { $this->routes[] = ['method' => strtoupper($method), 'path' => $path, 'handler' => $handler]; }
    public function dispatch($method, $uri) {
        foreach ($this->routes as $route) {
            $path_regex = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $route['path']);
            if (preg_match("#^$path_regex$#", $uri, $matches) && $route['method'] === $method) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                call_user_func($route['handler'], $params);
                return;
            }
        }
        http_response_code(404);
        echo json_encode(["erro" => "Rota não encontrada ou método não permitido.", "dado" => null]);
    }
}

$router = new Router();
$dbConnection = MysqlSingleton::getInstance()->conexao;

// Usando function() em vez de fn() para maior compatibilidade
$router->add('POST', '/register', function() { (new \Controllers\UserController())->register(); });
$router->add('POST', '/login', function() { (new \Controllers\UserController())->login(); });

$router->add('GET', '/reliquias', function() use ($dbConnection) { (new \Controllers\ReliquiaController($dbConnection))->getAllReliquias(); });
$router->add('GET', '/reliquias/{id}', function($params) use ($dbConnection) { (new \Controllers\ReliquiaController($dbConnection))->getReliquia($params['id']); });
$router->add('POST', '/reliquias', function() use ($dbConnection) { (new \Controllers\ReliquiaController($dbConnection))->createReliquia(); });
$router->add('PUT', '/reliquias/{id}', function($params) use ($dbConnection) { (new \Controllers\ReliquiaController($dbConnection))->updateReliquia($params['id']); });
$router->add('DELETE', '/reliquias/{id}', function($params) use ($dbConnection) { (new \Controllers\ReliquiaController($dbConnection))->deleteReliquia($params['id']); });

// ... adicione as outras rotas aqui usando a mesma sintaxe com function() ...