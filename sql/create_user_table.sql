CREATE DATABASE IF NOT EXISTS reliquias_perdidas;
USE reliquias_perdidas;

CREATE TABLE IF NOT EXISTS reliquias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    epoca VARCHAR(100) NOT NULL,
    localizacao_estimada TEXT NOT NULL,
    descricao TEXT
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, -- Senha hashed
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS teorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reliquia_id INT NOT NULL,
    autor VARCHAR(100), -- Pode ser o nome do usuário, mas mantive para flexibilidade
    descricao TEXT NOT NULL,
    user_id INT NOT NULL, -- Adicionado para ligar a teoria a um usuário
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reliquia_id) REFERENCES reliquias(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS fontes_historicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reliquia_id INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    tipo_fonte VARCHAR(50),
    link TEXT,
    FOREIGN KEY (reliquia_id) REFERENCES reliquias(id) ON DELETE CASCADE
);