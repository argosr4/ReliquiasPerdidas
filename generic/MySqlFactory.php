<?php
class MySqlFactory {
    private static $instances = [];
    
    public static function getConnection($config = null) {
        $key = $config ? md5(serialize($config)) : 'default';
        
        if (!isset(self::$instances[$key])) {
            if ($config) {
                self::$instances[$key] = self::createConnection($config);
            } else {
                $database = new Database();
                self::$instances[$key] = $database->getConnection();
            }
        }
        
        return self::$instances[$key];
    }
    
    private static function createConnection($config) {
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8";
            $pdo = new PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception("Erro de conexão: " . $e->getMessage());
        }
    }
    
    public static function closeConnection($key = 'default') {
        if (isset(self::$instances[$key])) {
            self::$instances[$key] = null;
        }
    }
}
