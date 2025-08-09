<?php
require_once 'conexion.php';

class Persona {
    protected $dni;
    protected $nombre;

    public function __construct($dni, $nombre) {
        $this->dni = $dni;
        $this->nombre = $nombre;
    }

    public function getDni() {
        return $this->dni;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public static function existe($dni) {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM personas WHERE dni = ?");
        $stmt->execute([$dni]);
        return $stmt->fetchColumn() > 0;
    }

    public static function obtenerNombre($dni) {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("SELECT nombre FROM personas WHERE dni = ?");
        $stmt->execute([$dni]);
        return $stmt->fetchColumn();
    }
}
?>
