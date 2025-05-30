<?php
// dao/UserDAO.php
// Não precisa de namespace, pois o autoloader já trata.
// Se fosse usar, seria 'namespace dao;'
use generic\User; // Importa a classe do modelo
// REMOVA ESTA LINHA: use PDO; // <--- REMOVER ESTA LINHA!

class UserDAO {
    private $conn;
    private $table_name = "users";

    public function __construct($db = null) {
        // Se a conexão não for passada, tenta obter do Singleton
        if ($db === null) {
            $this->conn = \generic\MysqlSingleton::getInstance()->conexao; // Adicionado \generic\ para garantir o namespace
        } else {
            $this->conn = $db;
        }
    }

    public function create(User $user) {
        $query = "INSERT INTO " . $this->table_name . "
                  (nome, email, senha)
                  VALUES (:nome, :email, :senha)";

        $stmt = $this->conn->prepare($query);

        // MUDAR DE bindParam PARA bindValue
        $stmt->bindValue(':nome', $user->getNome()); // <--- MUDANÇA AQUI
        $stmt->bindValue(':email', $user->getEmail()); // <--- MUDANÇA AQUI
        $stmt->bindValue(':senha', $user->getSenha()); // <--- MUDANÇA AQUI

        return $stmt->execute();
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare('SELECT id, nome, email, senha FROM ' . $this->table_name . ' WHERE email = :email');
        $stmt->bindParam(':email', $email); // Este bindParam está ok, pois $email é uma variável
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // PDO ainda precisa ser usado aqui, mas sem o 'use' no topo
    }

    // Você pode adicionar métodos para update e delete de usuários se necessário
    // public function update(User $user) { ... }
    // public function delete($id) { ... }
}
?>