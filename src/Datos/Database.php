<?php

class Database {
    private static $instance;
    private $connection;
    
    private function __construct() {
        // Establecer conexión a la base de datos aquí
        // Nota: Este es solo un ejemplo, deberás adaptarlo a tu configuración
        $host = 'localhost';
        $port = '5432';
        $database = 'regevent';
        $username = 'postgres';
        $password = '2025';

        try {
            $this->connection = new PDO("pgsql:host=$host;port=$port;dbname=$database;user=$username;password=$password");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            error_log("Conexión exitosa a la base de datos PostgreSQL");
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}

// Para obtener la conexión en cualquier parte de tu aplicación
$database = Database::getInstance();
$conn = $database->getConnection(); // continuar con la instancia
?>
