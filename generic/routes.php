<?php
// generic/routes.php

// Usar as classes com seus namespaces
use controllers\FonteHistoricaController;
use controllers\ReliquiaController;
use controllers\TeoriaController;
use controllers\UserController;
use generic\MysqlSingleton;
use generic\Endpoint;

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = ['method' => strtoupper($method), 'path' => $path, 'handler' => $handler];
    }

    public function dispatch($method, $uri) {
        // Remover depurações para produção (opcional: mantenha a primeira linha se quiser ver o método real)
        // echo "Router está despachando URI: '{$uri}' com método: '{$method}' (RECEBIDO PELO PHP)<br>";
        // echo "----------------------------------------------------<br>";

        // Captura o método real da requisição uma vez
        $realMethod = strtoupper($_SERVER['REQUEST_METHOD']);

        foreach ($this->routes as $route) {
            $regex = preg_replace('#\{(\w+)\}#', '(?P<\1>[^/]+)', $route['path']);
            $regex = '#^' . $regex . '$#';

            // Remover depurações para produção
            // echo "Tentando match com Rota: '{$route['path']}' (Regex: '{$regex}')<br>";
            // $match_result = preg_match($regex, $uri, $matches);
            // echo "  Resultado preg_match: " . $match_result . "<br>";
            // if ($match_result) {
            //     echo "  Matches capturados: ";
            //     print_r($matches);
            //     echo "<br>";
            // }
            // echo "  Método da Rota (registrado): '{$route['method']}'<br>";
            // echo "  Método REAL da Req (\$_SERVER['REQUEST_METHOD']): '{$realMethod}'<br>";
            // echo "----------------------------------------------------<br>";

            // **************************************************************************
            // *** MUDANÇA CRÍTICA AQUI: Comparando o método REAL com o método da Rota ***
            // **************************************************************************
            if ($route['method'] === $realMethod && preg_match($regex, $uri, $matches)) {
                // Remover depurações para produção
                // echo "!!! MATCH ENCONTRADO para rota: '{$route['path']}' !!!<br>";

                array_shift($matches);
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $_SERVER['REQUEST_URI'] = $uri; // Preserva a URI para o Controller
                call_user_func($route['handler'], $params);
                return; // Importante: Retorna após encontrar e chamar o handler
            }
        }

        // Se o PATH deu match, mas o método não, ou se a rota não foi encontrada:
        // Primeiro, verifica se o PATH existe para qualquer método (para retornar 405 em vez de 404)
        foreach ($this->routes as $route) {
            $regex = preg_replace('#\{(\w+)\}#', '(?P<\1>[^/]+)', $route['path']);
            $regex = '#^' . $regex . '$#';
            if (preg_match($regex, $uri)) {
                http_response_code(405); // 405 Method Not Allowed
                header('Content-Type: application/json');
                echo json_encode(["error" => "Método {$realMethod} não permitido para esta rota."], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }

        // Se nenhuma rota corresponder (nenhum PATH deu match), verifique a documentação da API
        if ($uri === '/' && $realMethod === 'GET') { // A documentação sempre é GET
            header('Content-Type: application/json');
            echo json_encode(Endpoint::getDocumentation(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit;
        }

        // Se não for a documentação e nenhuma rota foi encontrada
        http_response_code(404); // 404 Not Found
        header('Content-Type: application/json');
        echo json_encode(["error" => "Rota não encontrada."], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Obtém a conexão PDO do Singleton uma única vez
try {
    $db = MysqlSingleton::getInstance()->conexao;
} catch (\Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(["error" => "Erro de conexão com o banco de dados: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    exit;
}

// *******************************************************************
// *** CRIA A INSTÂNCIA DO ROUTER AQUI E ADICIONA AS ROTAS A ELA ***
// *** E A TORNA GLOBALMENTE ACESSÍVEL PARA index.php ***
// *******************************************************************
$router = new Router(); // Crie a instância AQUI!

// Rotas para Relíquias
$router->add('GET', '/reliquias', function() use ($db) {
    $controller = new ReliquiaController($db);
    $controller->handleRequest();
});
$router->add('GET', '/reliquias/{id}', function() use ($db) {
    $controller = new ReliquiaController($db);
    $controller->handleRequest();
});
// Rotas para POST/PUT/DELETE de /reliquias (agora com o MÉTODO REAL aqui!)
$router->add('POST', '/reliquias', function() use ($db) {
    $controller = new ReliquiaController($db);
    $controller->handleRequest();
});
$router->add('PUT', '/reliquias/{id}', function() use ($db) {
    $controller = new ReliquiaController($db);
    $controller->handleRequest();
});
$router->add('DELETE', '/reliquias/{id}', function() use ($db) {
    $controller = new ReliquiaController($db);
    $controller->handleRequest();
});


// Rotas para Fontes Históricas
$router->add('GET', '/fontes', function() use ($db) {
    $controller = new FonteHistoricaController($db);
    $controller->handleRequest();
});
$router->add('GET', '/fontes/{id}', function() use ($db) {
    $controller = new FonteHistoricaController($db);
    $controller->handleRequest();
});
$router->add('POST', '/fontes', function() use ($db) {
    $controller = new FonteHistoricaController($db);
    $controller->handleRequest();
});
$router->add('PUT', '/fontes/{id}', function() use ($db) {
    $controller = new FonteHistoricaController($db);
    $controller->handleRequest();
});
$router->add('DELETE', '/fontes/{id}', function() use ($db) {
    $controller = new FonteHistoricaController($db);
    $controller->handleRequest();
});


// Rotas para Teorias
$router->add('GET', '/teorias', function() use ($db) {
    $controller = new TeoriaController($db);
    $controller->handleRequest();
});
$router->add('GET', '/teorias/{id}', function() use ($db) {
    $controller = new TeoriaController($db);
    $controller->handleRequest();
});
$router->add('POST', '/teorias', function() use ($db) {
    $controller = new TeoriaController($db);
    $controller->handleRequest();
});
$router->add('PUT', '/teorias/{id}', function() use ($db) {
    $controller = new TeoriaController($db);
    $controller->handleRequest();
});
$router->add('DELETE', '/teorias/{id}', function() use ($db) {
    $controller = new TeoriaController($db);
    $controller->handleRequest();
});


// Rotas para Usuários (Autenticação)
// AGORA COM OS MÉTODOS HTTP REAIS
$router->add('POST', '/register', function() {
    $controller = new UserController();
    $controller->register();
});
$router->add('POST', '/login', function() {
    $controller = new UserController();
    $controller->login();
});
$router->add('GET', '/logout', function() {
    $controller = new UserController();
    $controller->logout();
});
$router->add('GET', '/check-auth', function() {
    $controller = new UserController();
    $controller->checkAuth();
});

// ====================================================================
// ROTAS PARA VIEWS (OPCIONAL: Se você for usar PHP para renderizar HTML)
// ... (mantenha essas rotas de VIEW como estão) ...
// ====================================================================

// NÃO CHAME dispatch() AQUI. O index.php fará isso.
// return $router; // Poderíamos retornar, mas global é mais simples para este setup.
?>