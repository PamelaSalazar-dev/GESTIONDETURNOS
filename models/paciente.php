<?php
require_once 'conexion.php';
require_once 'persona.php';

class Paciente {
    private $dni;
    private $nombre;
    private $obraSocial;
    private $historiaClinica;

    public function __construct($dni, $nombre, $obraSocial, $historiaClinica) {
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->obraSocial = $obraSocial;
        $this->historiaClinica = $historiaClinica;
    }

    public function guardar() {
        $conexion = Conexion::getConexion();

        try {
            // 1. Verificar que el DNI exista en personas
            if (!Persona::existe($this->dni)) {
                echo "❌ No se puede agregar paciente porque el DNI no existe en personas.\n";
                return false;
            }

            // 2. Validar que el nombre coincida con el que está en personas
            $nombreBD = Persona::obtenerNombre($this->dni);
            if (strcasecmp(trim($this->nombre), trim($nombreBD)) !== 0) {
                echo "❌ El nombre ingresado no coincide con el registrado para este DNI.\n";
                return false;
            }

            // 3. Insertar en pacientes
            $stmt = $conexion->prepare("INSERT INTO pacientes (dni, obraSocial, historiaClinica) VALUES (?, ?, ?)");
            $stmt->execute([$this->dni, $this->obraSocial, $this->historiaClinica]);

            return true;

        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                echo "❌ No se pudo guardar el paciente: verifique si ya existe o si el DNI está duplicado.\n";
            } else {
                echo "❌ Error inesperado al guardar paciente: " . $e->getMessage() . "\n";
            }
            return false;
        }
    }

    public function modificar() {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("UPDATE pacientes SET obraSocial = ?, historiaClinica = ? WHERE dni = ?");
        return $stmt->execute([$this->obraSocial, $this->historiaClinica, $this->dni]);
    }

    public static function listarPacientes() {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->query("
            SELECT p.dni, per.nombre, p.obraSocial, p.historiaClinica
            FROM pacientes p
            JOIN personas per ON p.dni = per.dni
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminar($dni) {
        $conexion = Conexion::getConexion();
        try {
            $stmt = $conexion->prepare("DELETE FROM pacientes WHERE dni = ?");
            return $stmt->execute([$dni]);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'foreign key') || $e->getCode() == '23000') {
                echo "❌ No se puede eliminar el paciente porque tiene turnos asignados. Cancele sus turnos primero.\n";
            } else {
                echo "❌ Error inesperado al eliminar paciente: " . $e->getMessage() . "\n";
            }
            return false;
        }
    }

    public static function tieneTurnosAsignados($dni) {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM turnos WHERE dniPaciente = ?");
        $stmt->execute([$dni]);
        return $stmt->fetchColumn() > 0;
    }

    public static function existePaciente($dni) {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM pacientes WHERE dni = ?");
        $stmt->execute([$dni]);
        return $stmt->fetchColumn() > 0;
    }
}
