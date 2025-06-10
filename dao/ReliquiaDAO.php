<?php
// dao/ReliquiaDAO.php

use Generic\Reliquia;
use PDO;
use PDOException; // Adicionado para captura de erros específicos do PDO

class ReliquiaDAO {
    private $conn;
    private $table_name = "reliquias";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create(Reliquia $reliquia) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "INSERT INTO " . $this->table_name . " (nome, epoca, localizacao_estimada, descricao) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $reliquia->getNome(),
                $reliquia->getEpoca(),
                $reliquia->getLocalizacaoEstimada(),
                $reliquia->getDescricao()
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar criar a relíquia.");
        }
    }

    public function readAll() {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY nome";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar buscar as relíquias.");
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
            throw new Exception("Erro no banco de dados ao tentar buscar a relíquia.");
        }
    }

    public function update(Reliquia $reliquia) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "UPDATE " . $this->table_name . " SET nome = ?, epoca = ?, localizacao_estimada = ?, descricao = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $reliquia->getNome(),
                $reliquia->getEpoca(),
                $reliquia->getLocalizacaoEstimada(),
                $reliquia->getDescricao(),
                $reliquia->getId()
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar atualizar a relíquia.");
        }
    }

    public function delete($id) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar deletar a relíquia.");
        }
    }
    
    public function searchByName($nome) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE nome LIKE ? ORDER BY nome";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['%' . $nome . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar buscar relíquias por nome.");
        }
    }
    
    public function searchByEpoca($epoca) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE epoca LIKE ? ORDER BY nome";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['%' . $epoca . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar buscar relíquias por época.");
        }
    }
}