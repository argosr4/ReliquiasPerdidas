<?php
// Não precisa de namespace, pois o autoloader já trata.
// Se fosse usar, seria 'namespace dao;'
use generic\FonteHistorica; // Importa a classe do modelo
use PDO; // Importa a classe PDO

class FonteHistoricaDAO {
    private $conn;
    private $table_name = "fontes_historicas";

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create(FonteHistorica $fonte) {
        $query = "INSERT INTO " . $this->table_name . "
                  (reliquia_id, titulo, tipo_fonte, link)
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $fonte->getReliquiaId(),
            $fonte->getTitulo(),
            $fonte->getTipoFonte(),
            $fonte->getLink()
        ]);
    }

    // READ BY RELIQUIA
    public function readByReliquia($reliquia_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE reliquia_id = ? ORDER BY titulo";
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
    public function update(FonteHistorica $fonte) {
        $query = "UPDATE " . $this->table_name . "
                  SET reliquia_id = ?, titulo = ?, tipo_fonte = ?, link = ?
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $fonte->getReliquiaId(),
            $fonte->getTitulo(),
            $fonte->getTipoFonte(),
            $fonte->getLink(),
            $fonte->getId()
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