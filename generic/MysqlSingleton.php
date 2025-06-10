<?php
namespace Generic;

use PDO;
use PDOException;
use Exception;

class MysqlSingleton {
    private static $instance = null;
    public $conexao = null;

    // ALTERAÇÃO: As credenciais fixas foram removidas daqui.
    // Elas agora são lidas do arquivo config.php através de constantes.

    private function __construct() {
        try {
            // ALTERAÇÃO: O DSN e as credenciais agora usam as constantes do config.php
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
            $this->conexao = new PDO($dsn, DB_USER, DB_PASS);
            
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexao->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // ALTERAÇÃO: Tratamento de erro aprimorado para falha de conexão.
            // Se o banco de dados estiver indisponível, a API inteira para.
            http_response_code(503); // Service Unavailable
            echo json_encode([
                "erro" => "Serviço indisponível. Falha na conexão com o banco de dados.",
                "dado" => null
            ]);
            exit; // Interrompe a execução
        }
    }

    // Impede a clonagem da instância
    private function __clone() {}

    // Impede a desserialização da instância
    public function __wakeup() {
        throw new Exception("Não é possível desserializar um singleton.");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new MysqlSingleton();
        }
        return self::$instance;
    }
}