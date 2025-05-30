<?php
namespace generic;

use PDO;
use PDOException;
use Exception; // Certifique-se de importar a classe Exception

class MysqlSingleton {
    private static $instance = null;
    public $conexao = null; // Torne a conexão pública para acesso fácil

    // Configurações do banco de dados - ATENÇÃO: ALTERE ISSO PARA SUAS CREDENCIAIS REAIS
    private $dsn = 'mysql:host=localhost;dbname=reliquias_perdidas;charset=utf8';
    private $usuario = 'root'; // Ajuste conforme seu usuário do MySQL
    private $senha = '';     // Ajuste conforme sua senha do MySQL

    private function __construct() {
        try {
            $this->conexao = new PDO($this->dsn, $this->usuario, $this->senha);
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexao->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    // Impede a clonagem da instância
    private function __clone() {}

    // Impede a desserialização da instância
    public function __wakeup() {
        throw new Exception("Cannot deserialize a singleton.");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new MysqlSingleton();
        }
        return self::$instance;
    }

    // Este método 'executar' não é usado pelos DAOs que usam PDO nativo.
    // Ele seria útil se você tivesse uma camada de abstração de query mais genérica.
    // Mantenho-o aqui para referência, mas os DAOs diretos são mais comuns.
    public function executar($query, $param = array()) {
        if ($this->conexao) {
            $sth = $this->conexao->prepare($query);
            foreach ($param as $k => $v) {
                // Bind parameters starting from 1 for positional or named keys
                if (is_int($k)) {
                    $sth->bindValue($k + 1, $v);
                } else {
                    $sth->bindValue($k, $v);
                }
            }
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }
}
?>