<?php
require_once 'conexion.php';
require_once __DIR__ . '/../utils/validador.php';

class Turno {
    private $dniMedico;
    private $dniPaciente;
    private $nombreConsultorio;
    private $fechaTurno;
    private $idSala;

    public function __construct($dniMedico, $dniPaciente, $nombreConsultorio, $fechaTurno, $idSala) {
        $this->dniMedico = $dniMedico;
        $this->dniPaciente = $dniPaciente;
        $this->nombreConsultorio = $nombreConsultorio;
        $this->fechaTurno = $fechaTurno;
        $this->idSala = $idSala;
    }

    public function guardar() {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("INSERT INTO turnos (dniMedico, dniPaciente, nombreConsultorio, fechaTurno, idSala) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$this->dniMedico, $this->dniPaciente, $this->nombreConsultorio, $this->fechaTurno, $this->idSala]);
    }

    public static function obtenerSalaDisponible($dniMedico, $fechaTurno) {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->query("SELECT idSala FROM salas");
        $salas = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $stmt2 = $conexion->prepare("SELECT idSala FROM turnos WHERE fechaTurno = ?");
        $stmt2->execute([$fechaTurno]);
        $salasOcupadas = $stmt2->fetchAll(PDO::FETCH_COLUMN);

        foreach ($salas as $sala) {
            if (!in_array($sala, $salasOcupadas)) {
                return $sala;
            }
        }

        return false;
    }

    public static function obtenerHorariosOcupados($dniMedico, $fecha) {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("SELECT TIME(fechaTurno) as horario FROM turnos WHERE dniMedico = ? AND DATE(fechaTurno) = ?");
        $stmt->execute([$dniMedico, $fecha]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function pacienteTieneTurno($dniPaciente, $fechaTurno) {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM turnos WHERE dniPaciente = ? AND fechaTurno = ?");
        $stmt->execute([$dniPaciente, $fechaTurno]);
        return $stmt->fetchColumn() > 0;
    }

    // Lista todos los turnos, ahora incluyendo el dniPaciente
    public static function listarTurnos() {
        $conexion = Conexion::getConexion();
        $sql = "SELECT 
                    t.fechaTurno, 
                    t.idSala, 
                    t.nombreConsultorio,
                    p.dni AS dniPaciente, 
                    p.nombre AS nombrePaciente, 
                    m.nombre AS nombreMedico
                FROM turnos t
                JOIN personas p ON t.dniPaciente = p.dni
                JOIN personas m ON t.dniMedico = m.dni
                ORDER BY t.fechaTurno ASC";

        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function cancelarTurno($dniPaciente, $fechaTurno) {
        $conexion = Conexion::getConexion();

        try {
            $stmt = $conexion->prepare("DELETE FROM turnos WHERE dniPaciente = ? AND fechaTurno = ?");
            $stmt->execute([$dniPaciente, $fechaTurno]);

            if ($stmt->rowCount() > 0) {
                echo "\033[32m✅ Turno cancelado exitosamente.\033[0m\n";
                return true;
            } else {
                echo "\033[33m⚠️ No se encontró un turno con esos datos.\033[0m\n";
                return false;
            }

        } catch (PDOException $e) {
            echo "❌ Error al cancelar el turno: " . $e->getMessage() . "\n";
            return false;
        }
    }

    public static function modificarTurnoInteractivo() {
        echo "Ingrese DNI del paciente: ";
        $dniPaciente = trim(fgets(STDIN));

        echo "Ingrese fecha actual del turno (YYYY-MM-DD HH:MM:SS): ";
        $fechaActual = trim(fgets(STDIN));

        echo "Ingrese nueva fecha para el turno (YYYY-MM-DD HH:MM:SS): ";
        $nuevaFecha = trim(fgets(STDIN));

        if (!Validador::validarFormatoFechaHora($fechaActual) || !Validador::validarFormatoFechaHora($nuevaFecha)) {
            echo "❌ Formato de fecha incorrecto. Debe ser YYYY-MM-DD HH:MM:SS\n";
            return;
        }

        $conexion = Conexion::getConexion();

        // Verificamos que el turno exista
        $stmt = $conexion->prepare("SELECT * FROM turnos WHERE dniPaciente = ? AND fechaTurno = ?");
        $stmt->execute([$dniPaciente, $fechaActual]);

        if ($stmt->rowCount() == 0) {
            echo "⚠️ No se encontró un turno con esos datos.\n";
            return;
        }

        // Verificar que no haya otro turno en la nueva fecha
        $stmtCheck = $conexion->prepare("SELECT COUNT(*) FROM turnos WHERE dniPaciente = ? AND fechaTurno = ?");
        $stmtCheck->execute([$dniPaciente, $nuevaFecha]);

        if ($stmtCheck->fetchColumn() > 0) {
            echo "❌ El paciente ya tiene un turno en esa nueva fecha.\n";
            return;
        }

        // Actualizar fecha
        $stmtUpdate = $conexion->prepare("UPDATE turnos SET fechaTurno = ? WHERE dniPaciente = ? AND fechaTurno = ?");
        $stmtUpdate->execute([$nuevaFecha, $dniPaciente, $fechaActual]);

        if ($stmtUpdate->rowCount() > 0) {
            echo "\033[32m✅ Turno modificado exitosamente.\033[0m\n";
        } else {
            echo "⚠️ No se pudo modificar el turno.\n";
        }
    }

    // Obtiene turnos específicos por DNI de paciente, útil para el menú cancelar turno
   public static function obtenerTurnosPorPaciente($dniPaciente) {
    $conexion = Conexion::getConexion();
    $stmt = $conexion->prepare("
        SELECT fechaTurno, idSala, nombreConsultorio
        FROM turnos
        WHERE dniPaciente = ?
        ORDER BY fechaTurno ASC
    ");
    $stmt->execute([$dniPaciente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }
}

?>


