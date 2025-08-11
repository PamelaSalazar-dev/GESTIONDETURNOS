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
        $turnos = Turno::listarTurnos();
        // 🔹 Aseguramos que siempre incluya el consultorio
        foreach ($turnos as &$t) {
            if (empty($t['nombreConsultorio'])) {
                $t['nombreConsultorio'] = 'SaludAR';
            }
        }
        return $turnos;
    }

    // Cancelar turno, devuelve true si pudo, false si no
    public static function cancelarTurno($dniPaciente, $fechaHora) {
        return Turno::cancelarTurno($dniPaciente, $fechaHora);
    }

    // Modificar turno, devuelve array con 'success' o 'error' y mensaje
    public static function modificarTurno($dniPaciente, $fechaActual, $nuevaFecha) {
        // Validar paciente
        if (!Paciente::existePaciente($dniPaciente)) {
            return ['error' => 'El paciente no existe.'];
        }

        // Validar formato fecha y hora
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', $nuevaFecha);
        if (!$dt || $dt->format('Y-m-d H:i:s') !== $nuevaFecha) {
            return ['error' => 'Formato de fecha y hora inválido.'];
        }

        // Validar que los minutos sean 00 o 30
        $minutos = (int)$dt->format('i');
        if ($minutos !== 0 && $minutos !== 30) {
            return ['error' => 'Los minutos deben ser 00 o 30.'];
        }

        // Validar que la hora esté dentro del rango permitido 09:00 a 15:00
        $hora = (int)$dt->format('H');
        if ($hora < 9 || $hora > 15) {
            return ['error' => 'La hora debe estar entre 09:00 y 15:00.'];
        }
        // Si la hora es 15, entonces los minutos deben ser 00 (15:30 no es válido)
        if ($hora === 15 && $minutos !== 0) {
            return ['error' => 'El último turno válido es a las 15:00.'];
        }

        // Validar que paciente no tenga turno en nueva fecha/hora
        if (Turno::pacienteTieneTurno($dniPaciente, $nuevaFecha)) {
            return ['error' => 'El paciente ya tiene un turno en esa fecha y hora.'];
        }

        $conexion = Conexion::getConexion();
        $stmtUpdate = $conexion->prepare("UPDATE turnos SET fechaTurno = ?, nombreConsultorio = 'SaludAR' WHERE dniPaciente = ? AND fechaTurno = ?");
        $stmtUpdate->execute([$nuevaFecha, $dniPaciente, $fechaActual]);

        if ($stmtUpdate->rowCount() > 0) {
            return ['success' => 'Turno modificado correctamente.'];
        } else {
            return ['error' => 'No se pudo modificar el turno.'];
        }
    }

    // Función nueva para obtener todos los turnos de un paciente (para mostrar en menú)
    public static function obtenerTurnosPorPaciente($dniPaciente) {
        return Turno::obtenerTurnosPorPaciente($dniPaciente);
    }

}
?>
