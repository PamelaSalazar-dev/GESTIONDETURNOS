<?php
require_once __DIR__ . '/../models/Turno.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/Medico.php';

class TurnoController {

    // Devuelve array de especialidades
    public static function obtenerEspecialidades() {
        return Medico::obtenerEspecialidades();
    }

    // Devuelve array de médicos para una especialidad
    public static function obtenerMedicosPorEspecialidad($especialidad) {
        return Medico::obtenerMedicosPorEspecialidad($especialidad);
    }

    // Devuelve array de horarios disponibles (de 9 a 15 hs cada 30 minutos) para un médico y fecha dada
    public static function obtenerHorariosDisponibles($dniMedico, $fecha) {
        $horariosOcupados = Turno::obtenerHorariosOcupados($dniMedico, $fecha);

        $horariosPosibles = [];
        for ($hora = 9; $hora < 15; $hora++) {
            $horariosPosibles[] = sprintf("%02d:00:00", $hora);
            $horariosPosibles[] = sprintf("%02d:30:00", $hora);
        }

        // Removemos horarios ocupados
        $horariosDisponibles = array_values(array_diff($horariosPosibles, $horariosOcupados));
        return $horariosDisponibles;
    }

    // Agrega un turno, devuelve true si se pudo, false si falla
    public static function agregarTurno($dniMedico, $dniPaciente, $fechaHora) {
        // Validar si paciente existe
        if (!Paciente::existePaciente($dniPaciente)) {
            return false;
        }

        // Validar que paciente no tenga turno en fecha/hora
        if (Turno::pacienteTieneTurno($dniPaciente, $fechaHora)) {
            return false;
        }

        // Validar sala disponible
        $salaDisponible = Turno::obtenerSalaDisponible($dniMedico, $fechaHora);
        if (!$salaDisponible) {
            return false;
        }

        $consultorio = "SaludAR"; // Nombre fijo

        $turno = new Turno($dniMedico, $dniPaciente, $consultorio, $fechaHora, $salaDisponible);
        return $turno->guardar();
    }

    // Listar todos los turnos (array)
    public static function listarTurnos() {
        return Turno::listarTurnos();
    }

    // Cancelar turno, devuelve true si pudo, false si no
    public static function cancelarTurno($dniPaciente, $fechaHora) {
        return Turno::cancelarTurno($dniPaciente, $fechaHora);
    }

    // Modificar turno, devuelve true si pudo, false si no
    public static function modificarTurno($dniPaciente, $fechaActual, $nuevaFecha) {
        // Validar paciente
        if (!Paciente::existePaciente($dniPaciente)) {
            return false;
        }

        // Validar que paciente no tenga turno en nueva fecha/hora
        if (Turno::pacienteTieneTurno($dniPaciente, $nuevaFecha)) {
            return false;
        }

        $conexion = Conexion::getConexion();
        $stmtUpdate = $conexion->prepare("UPDATE turnos SET fechaTurno = ? WHERE dniPaciente = ? AND fechaTurno = ?");
        $stmtUpdate->execute([$nuevaFecha, $dniPaciente, $fechaActual]);

        return $stmtUpdate->rowCount() > 0;
    }

    // Función nueva para obtener todos los turnos de un paciente (para mostrar en menú)
    public static function obtenerTurnosPorPaciente($dniPaciente) {
        return Turno::obtenerTurnosPorPaciente($dniPaciente);
    }

}
?>
