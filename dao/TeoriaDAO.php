<?php
// Não precisa de namespace, pois o autoloader já trata.
// Se fosse usar, seria 'namespace dao;'
use generic\Teoria; // Importa a classe do modelo
use PDO; // Importa a classe PDO

class TeoriaDAO {
    private $conn;
    private $table_name = "teorias";

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create(Teoria $teoria) {
        $query = "INSERT INTO " . $this->table_name . "
                  (reliquia_id, autor, descricao, user_id)
                  VALUES (?, ?, ?, ?)"; // Adicionado user_id aqui

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $teoria->getReliquiaId(),
            $teoria->getAutor(),
            $teoria->getDescricao(),
            $teoria->getUserId() // Adicionado aqui
        ]);
    }

    // READ BY RELIQUIA
    public function readByReliquia($reliquia_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE reliquia_id = ? ORDER BY criado_em DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$reliquia_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ ONE
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function update(Teoria $teoria) {
        $query = "UPDATE " . $this->table_name . "
                  SET reliquia_id = ?, autor = ?, descricao = ?
                  WHERE id = ?"; // user_id não deve ser atualizado aqui

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $teoria->getReliquiaId(),
            $teoria->getAutor(),
            $teoria->getDescricao(),
            $teoria->getId()
        ]);
    }

    // DELETE
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>