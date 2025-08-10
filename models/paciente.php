<?php
require_once 'Conexion.php';

class Paciente {

    public static function existePaciente($dni) {
        $pdo = Conexion::getConexion();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM pacientes WHERE dni = :dni");
        $stmt->execute(['dni' => $dni]);
        return $stmt->fetchColumn() > 0;
    }

    public static function obtenerNombre($dni) {
        $pdo = Conexion::getConexion();
        $stmt = $pdo->prepare("SELECT nombre FROM personas WHERE dni = :dni");
        $stmt->execute(['dni' => $dni]);
        return $stmt->fetchColumn();
    }

    public static function agregar($dni, $nombre, $obraSocial, $historiaClinica) {
        if (self::existePaciente($dni)) {
            return false; // Ya existe paciente con ese DNI
        }
        $pdo = Conexion::getConexion();
        $stmt = $pdo->prepare("INSERT INTO pacientes (dni, obraSocial, historiaClinica) VALUES (:dni, :obraSocial, :historiaClinica)");
        return $stmt->execute([
            'dni' => $dni,
            'obraSocial' => $obraSocial,
            'historiaClinica' => $historiaClinica
        ]);
    }

    public static function modificarObraSocial($dni, $obraSocial) {
        if (!self::existePaciente($dni)) {
            return false;
        }
        $pdo = Conexion::getConexion();
        $stmt = $pdo->prepare("UPDATE pacientes SET obraSocial = :obraSocial WHERE dni = :dni");
        return $stmt->execute([
            'obraSocial' => $obraSocial,
            'dni' => $dni
        ]);
    }

    public static function eliminar($dni) {
        if (!self::existePaciente($dni)) {
            return false;
        }

        $pdo = Conexion::getConexion();

        // Verificar si tiene turnos asociados
        $consulta = $pdo->prepare("SELECT COUNT(*) FROM turnos WHERE dniPaciente = :dni");
        $consulta->execute(['dni' => $dni]);
        $tieneTurnos = $consulta->fetchColumn();

        if ($tieneTurnos > 0) {
            echo "No se puede eliminar el paciente porque tiene turnos asignados.\n";
            return false;
        }

        $stmt = $pdo->prepare("DELETE FROM pacientes WHERE dni = :dni");
        return $stmt->execute(['dni' => $dni]);
    }

    public static function listar() {
        $pdo = Conexion::getConexion();
        $stmt = $pdo->query("SELECT p.dni, per.nombre, p.obraSocial, p.historiaClinica
                             FROM pacientes p
                             INNER JOIN personas per ON p.dni = per.dni");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>


