<?php
require_once 'conexion.php';
require_once './controllers/pacienteController.php';

class Paciente {
    private $dni;
    private $obraSocial;
    private $historiaClinica;

    public function __construct($dni, $obraSocial, $historiaClinica) {
        $this->dni = $dni;
        $this->obraSocial = $obraSocial;
        $this->historiaClinica = $historiaClinica;
    }

    public function guardar() {
        $conexion = Conexion::getConexion(); 
        $stmt = $conexion->prepare("INSERT INTO pacientes (dni, obraSocial, historiaClinica) VALUES (?, ?, ?)");
        return $stmt->execute([$this->dni, $this->obraSocial, $this->historiaClinica]);
    }

    public function modificar() {
        $conexion = Conexion::getConexion(); 
        $stmt = $conexion->prepare("UPDATE pacientes SET obraSocial = ?, historiaClinica = ? WHERE dni = ?");
        return $stmt->execute([$this->obraSocial, $this->historiaClinica, $this->dni]);
    }

    public static function listarPacientes() {
        $conexion = Conexion::getConexion(); 
        $stmt = $conexion->query("SELECT dni, obraSocial, historiaClinica FROM pacientes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminar($dni) {
        $conexion = Conexion::getConexion(); 
        $stmt = $conexion->prepare("DELETE FROM pacientes WHERE dni = ?");
        return $stmt->execute([$dni]);
    }
}
?>


