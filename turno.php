<?php
require_once 'conexion.php';

class Turno {
    private $dniPaciente;
    private $idSala;
    private $fechaTurno;

    public function __construct($dniPaciente, $idSala, $fechaTurno) {
        $this->dniPaciente = $dniPaciente;
        $this->idSala = $idSala;
        $this->fechaTurno = $fechaTurno;
    }

    public static function todosLosTurnos() {
    $pdo = Conexion::getConexion();
    $sql = "SELECT t.fechaTurno, p.dni AS dniPaciente, p.obraSocial AS obraSocialPaciente, m.matricula AS matriculaMedico, s.nombre AS nombreSala
            FROM turnos t
            INNER JOIN pacientes p ON t.dniPaciente = p.dni
            INNER JOIN medicos m ON t.dniMedico = m.matricula
            INNER JOIN salas s ON t.idSala = s.idSala";  // Corregido a 'idSala'
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        echo "Error al obtener los turnos: " . $e->getMessage();
        return [];
    }
}

    

   

    public static function agregarTurno($dniPaciente, $idSala, $fechaTurno) {
        $pdo = Conexion::getConexion();
        $sql = "INSERT INTO turnos (dniPaciente, idSala, fechaTurno) VALUES (:dniPaciente, :idSala, :fechaTurno)";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':dniPaciente', $dniPaciente);
            $stmt->bindParam(':idSala', $idSala);
            $stmt->bindParam(':fechaTurno', $fechaTurno);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al agregar el turno: " . $e->getMessage();
            return false;
        }
    }
}

function listarTurnos() {
    $turnos = Turno::todosLosTurnos();
    if (empty($turnos)) {
        echo "No hay turnos disponibles.\n";
        return;
    }

    echo "Lista de Turnos:\n";
    foreach ($turnos as $turno) {
        echo "Fecha: {$turno->fechaTurno} | Paciente: {$turno->nombrePaciente} | Médico: {$turno->nombreMedico} | Sala: {$turno->nombreSala}\n";
    }
}

function agregarTurno() {
    echo "Ingrese DNI del Paciente: ";
    $dniPaciente = trim(fgets(STDIN));
    echo "Ingrese ID de Sala: ";
    $idSala = trim(fgets(STDIN));
    echo "Ingrese fecha del Turno (YYYY-MM-DD HH:MM:SS): ";
    $fechaTurno = trim(fgets(STDIN));

    if (Turno::agregarTurno($dniPaciente, $idSala, $fechaTurno)) {
        echo "Turno agregado exitosamente.\n";
    } else {
        echo "Error al agregar el turno.\n";
    }
}
?>
