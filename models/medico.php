<?php
require_once 'conexion.php';

class Medico {
    private $matricula;
    private $especialidad;
    private $dni;

    public function __construct($matricula, $especialidad, $dni) {
        $this->matricula = $matricula;
        $this->especialidad = $especialidad;
        $this->dni = $dni;
    }

    public function guardar() {
        $conexion = Conexion::getConexion();

        try {
            // Verificar que el DNI exista en personas
            $stmtCheck = $conexion->prepare("SELECT COUNT(*) FROM personas WHERE dni = ?");
            $stmtCheck->execute([$this->dni]);

            if ($stmtCheck->fetchColumn() == 0) {
                echo "❌ No se puede agregar médico porque el DNI no existe en personas.\n";
                return false;
            }

            // Insertar en medicos
            $stmt = $conexion->prepare("INSERT INTO medicos (matricula, especialidad, dni) VALUES (?, ?, ?)");
            $stmt->execute([$this->matricula, $this->especialidad, $this->dni]);

            return true;

        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                echo "❌ No se pudo guardar el médico: verifique si ya existe o si el DNI está duplicado.\n";
            } else {
                echo "❌ Error inesperado al guardar médico: " . $e->getMessage() . "\n";
            }
            return false;
        }
    }

    public static function eliminar($dni) {
        $conexion = Conexion::getConexion();

        try {
            // Verificar si tiene turnos asignados
            $stmtCheck = $conexion->prepare("SELECT COUNT(*) FROM turnos WHERE dniMedico = ?");
            $stmtCheck->execute([$dni]);

            if ($stmtCheck->fetchColumn() > 0) {
                echo "❌ No se puede eliminar el médico porque tiene turnos asignados. Cancele sus turnos primero.\n";
                return false;
            }

            // Eliminar médico
            $stmt = $conexion->prepare("DELETE FROM medicos WHERE dni = ?");
            return $stmt->execute([$dni]);

        } catch (PDOException $e) {
            echo "❌ Error inesperado al eliminar médico: " . $e->getMessage() . "\n";
            return false;
        }
    }

    public static function listarMedicos() {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->query("
            SELECT m.matricula, m.especialidad, p.nombre, m.dni
            FROM medicos m
            JOIN personas p ON m.dni = p.dni
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function existeMedico($dni) {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM medicos WHERE dni = ?");
        $stmt->execute([$dni]);
        return $stmt->fetchColumn() > 0;
    }

    public static function obtenerEspecialidades() {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->query("SELECT DISTINCT especialidad FROM medicos");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function obtenerMedicosPorEspecialidad($especialidad) {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("
            SELECT m.matricula, m.dni, p.nombre 
            FROM medicos m
            JOIN personas p ON m.dni = p.dni
            WHERE m.especialidad = ?
        ");
        $stmt->execute([$especialidad]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Getters
    public function getMatricula() {
        return $this->matricula;
    }

    public function getEspecialidad() {
        return $this->especialidad;
    }
}
?>
