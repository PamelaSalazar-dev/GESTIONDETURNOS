<?php
require_once './models/turno.php';

class TurnoController {
    public static function agregarTurno($dnimedico, $dnipaciente, $nombreconsultorio, $fechaturno, $idsala) {
        // se una instancia de Turno
        $turno = new Turno($dnimedico, $dnipaciente, $nombreconsultorio, $fechaturno, $idsala);

        // se guardar el turno
        if ($turno->guardar()) {
            echo "Turno guardado correctamente.\n";
        } else {
            echo "Error al guardar el turno. Verifica los datos ingresados.\n";
        }
    }

    public static function todosLosTurnos() {
        $turnos = Turno::todosLosTurnos(); // se muestra la lista de turnos

        if (empty($turnos)) {
            echo "No hay turnos registrados.\n";
        } else {
            foreach ($turnos as $turno) {
                echo "DNI Médico: " . $turno['dnimedico'] . " - DNI Paciente: " . $turno['dnipaciente'] . " - Consultorio: " . $turno['nombreconsultorio'] . " - Fecha: " . $turno['fechaturno'] . " - Sala: " . $turno['idsala'] . "\n";
            }
        }
    }
}
?>