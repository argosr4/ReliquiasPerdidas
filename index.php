<?php
// index.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// O resto do seu código continua aqui...
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

// Ambiente de Produção: Não exibir erros técnicos no retorno da API
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// AUTOLOADER DO COMPOSER: Carrega todas as classes do projeto e da biblioteca JWT
require_once __DIR__ . '/vendor/autoload.php';

// CONFIGURAÇÕES: Carrega as constantes do JWT (chave secreta, etc.)
require_once __DIR__ . '/config.php';

// ROTAS: Inclui o arquivo que define as rotas da API
include __DIR__ . '/generic/routes.php';

// Define a base path da API para remover da URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/api'; // Ajuste se sua API estiver em um subdiretório diferente
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}
if (empty($requestUri)) {
    $requestUri = '/';
}

global $router; // Acessa a instância do roteador criada em routes.php

// Despacha a requisição para o handler da rota correspondente
$router->dispatch($_SERVER['REQUEST_METHOD'], $requestUri);