<?php
// dao/FonteHistoricaDAO.php

use Generic\FonteHistorica;
use PDO;
use PDOException; // Adicionado para captura de erros específicos do PDO

class FonteHistoricaDAO {
    private $conn;
    private $table_name = "fontes_historicas";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create(FonteHistorica $fonte) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "INSERT INTO " . $this->table_name . " (reliquia_id, titulo, tipo_fonte, link) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $fonte->getReliquiaId(),
                $fonte->getTitulo(),
                $fonte->getTipoFonte(),
                $fonte->getLink()
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar criar a fonte histórica.");
        }
    }

    public function readByReliquia($reliquia_id) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE reliquia_id = ? ORDER BY titulo";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$reliquia_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar buscar as fontes históricas.");
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
            throw new Exception("Erro no banco de dados ao tentar buscar a fonte histórica.");
        }
    }

    public function update(FonteHistorica $fonte) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "UPDATE " . $this->table_name . " SET reliquia_id = ?, titulo = ?, tipo_fonte = ?, link = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $fonte->getReliquiaId(),
                $fonte->getTitulo(),
                $fonte->getTipoFonte(),
                $fonte->getLink(),
                $fonte->getId()
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar atualizar a fonte histórica.");
        }
    }

    public function delete($id) {
        // ALTERAÇÃO: Adicionado bloco try...catch para tratamento de erro.
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao tentar deletar a fonte histórica.");
        }
    }
}