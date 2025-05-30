<?php
// index.php

// Ativar a exibição de erros para depuração (remover em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar a sessão PHP, se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir o autoloader para carregar as classes automaticamente
require_once __DIR__ . '/generic/Autoload.php';

// Incluir o arquivo de rotas que contém o Router
// ESTA INCLUSÃO AGORA TAMBÉM VAI CRIAR E CONFIGURAR O OBJETO $router
include __DIR__ . '/generic/routes.php'; // Use INCLUDE aqui

// REMOVA A LINHA: $router = new Router();


// Define a base path da API (se aplicável, para o .htaccess)
// Removendo '/api' da URI para o Router se ele estiver presente
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/api';
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}
// Se a URI resultante for vazia, trate como '/'
if (empty($requestUri)) {
    $requestUri = '/';
}

// ******************************************************
// LINHAS DE DEPURACAO TEMPORARIAS - MANTER COMENTADAS
// ******************************************************
//echo "DEBUG - index.php - Início do Processamento:<br>";
//echo "Método HTTP recebido: " . $_SERVER['REQUEST_METHOD'] . "<br>";
//echo "URI Original (do servidor): " . $_SERVER['REQUEST_URI'] . "<br>";
//echo "URI Processada pelo index.php (para o Router): " . $requestUri . "<br>";
//echo "DEBUG - index.php - Fim do Processamento.<br>";
// ******************************************************

// Garante que estamos usando a instância $router criada e configurada em routes.php
global $router;

// Despacha a requisição
$router->dispatch($_SERVER['REQUEST_METHOD'], $requestUri);

?>