<?php
// Não precisa de namespace, pois o autoloader já trata.
// Se fosse usar, seria 'namespace dao;'
use generic\Reliquia; // Importa a classe do modelo


class ReliquiaDAO {
    private $conn;
    private $table_name = "reliquias";

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create(Reliquia $reliquia) {
        $query = "INSERT INTO " . $this->table_name . "
                  (nome, epoca, localizacao_estimada, descricao)
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $reliquia->getNome(),
            $reliquia->getEpoca(),
            $reliquia->getLocalizacaoEstimada(),
            $reliquia->getDescricao()
        ]);
    }

    // READ ALL
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nome";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
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
    public function update(Reliquia $reliquia) {
        $query = "UPDATE " . $this->table_name . "
                  SET nome = ?, epoca = ?, localizacao_estimada = ?, descricao = ?
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $reliquia->getNome(),
            $reliquia->getEpoca(),
            $reliquia->getLocalizacaoEstimada(),
            $reliquia->getDescricao(),
            $reliquia->getId()
        ]);
    }

    // DELETE
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    // SEARCH BY NAME
    public function searchByName($nome) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE nome LIKE ? ORDER BY nome";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['%' . $nome . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // SEARCH BY EPOCA
    public function searchByEpoca($epoca) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE epoca LIKE ? ORDER BY nome";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['%' . $epoca . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>