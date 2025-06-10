<?php
// dao/TeoriaDAO.php

use Generic\Teoria;
use PDO;
use PDOException; // Adicionado para captura de erros específicos do PDO

class TeoriaDAO {
    private $conn;
    private $table_name = "teorias";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create(Teoria $teoria) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "INSERT INTO " . $this->table_name . " (reliquia_id, autor, descricao, user_id) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $teoria->getReliquiaId(),
                $teoria->getAutor(),
                $teoria->getDescricao(),
                $teoria->getUserId()
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar criar a teoria.");
        }
    }

    public function readByReliquia($reliquia_id) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            // Melhoria: A query agora busca o nome do autor da teoria usando um JOIN.
            $query = "SELECT t.*, u.nome as nome_autor FROM " . $this->table_name . " t JOIN users u ON t.user_id = u.id WHERE t.reliquia_id = ? ORDER BY t.criado_em DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$reliquia_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar buscar as teorias.");
        }
    }

    public function readOne($id) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar buscar a teoria.");
        }
    }

    public function update(Teoria $teoria) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "UPDATE " . $this->table_name . " SET reliquia_id = ?, autor = ?, descricao = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $teoria->getReliquiaId(),
                $teoria->getAutor(),
                $teoria->getDescricao(),
                $teoria->getId()
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar atualizar a teoria.");
        }
    }

    public function delete($id) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar deletar a teoria.");
        }
    }
}