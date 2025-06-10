<?php

// --- CONFIGURAÇÕES DO BANCO DE DADOS ---
// As informações para a sua conexão com o MySQL/MariaDB.
define('DB_HOST', 'localhost');
define('DB_NAME', 'reliquias_perdidas'); // O nome do seu banco de dados.
define('DB_USER', 'root');                // Seu usuário do MySQL (geralmente 'root' em ambiente local).
define('DB_PASS', '');                    // Sua senha do MySQL (geralmente vazia no XAMPP).


// --- CONFIGURAÇÕES DO JWT (JSON WEB TOKEN) ---
// IMPORTANTE: Troque por uma chave secreta longa, aleatória e segura.
// Use um gerador online como https://randomkeygen.com/
define('JWT_KEY', '5f9b3a7c8e1d2f4a6b9c8d7e6f5a4b3c2d1e0f9a8b7c6d5e4f3a2b1c0d9e8f7a');

// Define quem está emitindo o token (sua API).
define('JWT_ISSUER', 'http://localhost');

// Define para quem o token se destina (seu front-end, por exemplo).
define('JWT_AUDIENCE', 'http://localhost');

// Tempo de expiração do token em segundos (1 hora = 3600 segundos).
define('JWT_EXPIRATION_TIME', 3600);