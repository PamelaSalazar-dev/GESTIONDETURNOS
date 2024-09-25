<?php

class Conexion {
    private static $db = null;
    private static $host = 'localhost';  
    private static $dbname = 'gestionturnos'; 
    private static $user = 'root';  
    private static $password = '07062020';  
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8";
            self::$db = new PDO($dsn, self::$user, self::$password);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
        }
    }

    public static function getConexion() {
        if (self::$db === null) {
            new self();  // Establece la conexión si aún no está establecida
        }
        return self::$db;
    }
    
    public static function comprobarConexion() {
        try {
            $pdo = self::getConexion();  // Asegura que la conexión esté establecida
            if ($pdo !== null) {
                $statement = $pdo->query("SELECT VERSION()");
                $version = $statement->fetchColumn();
                echo "Conexión exitosa. Versión de MySQL: " . $version;
            } else {
                echo "Conexión fallida.";
            }
        } catch (PDOException $e) {
            echo "Error al comprobar la conexión: " . $e->getMessage();
        }
    }

    public static function query($sql) {
        $pdo = self::getConexion();
        if ($pdo !== null) {
            try {
                $statement = $pdo->query($sql);
                return $statement->fetchAll(PDO::FETCH_OBJ);
            } catch (PDOException $e) {
                echo "Error en la consulta SQL: " . $e->getMessage();
                return [];
            }
        } else {
            echo "No se pudo ejecutar la consulta: la conexión es nula.";
            return [];
        }
    }
}

// Comprobar la conexión
Conexion::comprobarConexion();

?>
