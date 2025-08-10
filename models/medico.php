<?php
require_once 'conexion.php';

class Medico {
    public static function obtenerMedicos() {
        $conexion = Conexion::getConexion();
        $sql = "SELECT m.dni, m.matricula, m.especialidad, p.nombre 
                FROM medicos m
                JOIN personas p ON m.dni = p.dni
                ORDER BY p.nombre";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerEspecialidades() {
        $conexion = Conexion::getConexion();
        $sql = "SELECT DISTINCT especialidad FROM medicos ORDER BY especialidad";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function obtenerMedicosPorEspecialidad($especialidad) {
        $conexion = Conexion::getConexion();
        $sql = "SELECT m.dni, m.matricula, p.nombre 
                FROM medicos m
                JOIN personas p ON m.dni = p.dni
                WHERE m.especialidad = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$especialidad]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function existeMedico($dni) {
        $conexion = Conexion::getConexion();
        $sql = "SELECT COUNT(*) FROM medicos WHERE dni = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$dni]);
        return $stmt->fetchColumn() > 0;
    }

    public static function existeComoPaciente($dni) {
        $conexion = Conexion::getConexion();
        $sql = "SELECT COUNT(*) FROM pacientes WHERE dni = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$dni]);
        return $stmt->fetchColumn() > 0;
    }

    public static function agregar($dni, $matricula, $especialidad) {
        if (self::existeMedico($dni)) {
            return false; // Ya existe
        }
        $conexion = Conexion::getConexion();
        $sql = "INSERT INTO medicos (dni, matricula, especialidad) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        return $stmt->execute([$dni, $matricula, $especialidad]);
    }

    public static function tieneTurnos($dniMedico) {
        $conexion = Conexion::getConexion();
        $sql = "SELECT COUNT(*) FROM turnos WHERE dniMedico = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$dniMedico]);
        return $stmt->fetchColumn() > 0;
    }

    public static function eliminar($dni) {
        // Primero: verificar si existe el médico
        if (!self::existeMedico($dni)) {
            return "no_existe"; // El médico no existe
        }
        // Segundo: verificar si es paciente
        if (self::existeComoPaciente($dni)) {
            return "paciente"; // No se puede eliminar porque también es paciente
        }
        // Tercero: verificar si tiene turnos
        if (self::tieneTurnos($dni)) {
            return false; // No puede eliminar porque tiene turnos asignados
        }
        // Si pasa las validaciones, eliminar
        $conexion = Conexion::getConexion();
        $sql = "DELETE FROM medicos WHERE dni = ?";
        $stmt = $conexion->prepare($sql);
        return $stmt->execute([$dni]);
    }

    public static function listar() {
        $conexion = Conexion::getConexion();
        $sql = "SELECT m.dni, p.nombre, m.matricula, m.especialidad
                FROM medicos m
                JOIN personas p ON m.dni = p.dni";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

