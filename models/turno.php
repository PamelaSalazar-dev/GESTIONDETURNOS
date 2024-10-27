<?php
require_once 'conexion.php';

class Turno {
    private $dnimedico;
    private $dnipaciente;
    private $nombreconsultorio;
    private $fechaturno;
    private $idsala;

    // Constructor para inicializar las propiedades
    public function __construct($dnimedico, $dnipaciente, $nombreconsultorio, $fechaturno, $idsala) {
        $this->dnimedico = $dnimedico;
        $this->dnipaciente = $dnipaciente;
        $this->nombreconsultorio = $nombreconsultorio;
        $this->fechaturno = $fechaturno;
        $this->idsala = $idsala;
    }

    // Método para guardar el turno en la base de datos
    public function guardar() {
        $conexion = Conexion::getConexion(); 
        $query = "INSERT INTO turnos (dnimedico, dnipaciente, nombreconsultorio, fechaturno, idsala) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        
        return $stmt->execute([$this->dnimedico, $this->dnipaciente, $this->nombreconsultorio, $this->fechaturno, $this->idsala]);
    }

    // Método para obtener todos los turnos
    public static function todosLosTurnos() {
        $conexion = Conexion::getConexion(); // Obtener la conexión
        $query = "SELECT dnimedico, dnipaciente, nombreconsultorio, fechaturno, idsala FROM turnos";
        $stmt = $conexion->query($query);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve los resultados como un array asociativo
    }
}
?>
