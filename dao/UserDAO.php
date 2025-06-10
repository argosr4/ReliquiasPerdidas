<?php
// dao/UserDAO.php

use Generic\User;
use PDO;
use PDOException; // Adicionado para captura de erros específicos do PDO

class UserDAO {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create(User $user) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "INSERT INTO " . $this->table_name . " (nome, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindValue(':nome', $user->getNome());
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->bindValue(':senha', $user->getSenha());

            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            // Tratamento específico para erro de chave duplicada (e-mail já existe)
            if ($e->getCode() == 23000) {
                throw new Exception("O e-mail informado já está em uso.");
            }
            // Para outros erros de banco, lança uma mensagem genérica
            throw new Exception("Erro no banco de dados ao tentar registrar o usuário.");
        }
    }

    public function findByEmail($email) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = 'SELECT id, nome, email, senha FROM ' . $this->table_name . ' WHERE email = :email';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar buscar o usuário.");
        }
    }
}