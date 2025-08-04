<?php
require_once './models/turno.php';
require_once './models/paciente.php';
require_once './models/medico.php';
require_once __DIR__ . '/../utils/validador.php';

class TurnoController {

    private static function limpiarPantalla() {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cls');
        } else {
            system('clear');
        }
    }

    public static function solicitarTurno() {
        echo "Especialidades disponibles:\n";
        $especialidades = Medico::obtenerEspecialidades();
        foreach ($especialidades as $index => $esp) {
            echo ($index + 1) . ". $esp\n";
        }

        echo "Seleccione especialidad (número): ";
        $espSeleccionada = intval(trim(fgets(STDIN)));
        if ($espSeleccionada < 1 || $espSeleccionada > count($especialidades)) {
            echo "❌ Especialidad inválida.\n";
            return;
        }
        $especialidad = $especialidades[$espSeleccionada - 1];

        $medicos = Medico::obtenerMedicosPorEspecialidad($especialidad);
        if (empty($medicos)) {
            echo "❌ No hay médicos disponibles para la especialidad $especialidad.\n";
            return;
        }
        echo "Médicos disponibles:\n";
        foreach ($medicos as $index => $med) {
            echo ($index + 1) . ". Matrícula: {$med['matricula']} - Nombre: {$med['nombre']}\n";
        }

        echo "Seleccione médico (número): ";
        $medSeleccionado = intval(trim(fgets(STDIN)));
        if ($medSeleccionado < 1 || $medSeleccionado > count($medicos)) {
            echo "❌ Médico inválido.\n";
            return;
        }
        $medico = $medicos[$medSeleccionado - 1];

        do {
            self::limpiarPantalla();
            echo "Ingrese fecha para el turno (YYYY-MM-DD): ";
            $fechaInput = trim(fgets(STDIN));

            // NUEVO: salir si no se ingresa nada
            if (empty($fechaInput)) {
                echo "\033[33m⚠️ Entrada vacía. Cancelando operación.\033[0m\n";
                return;
            }

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInput)) {
                echo "\033[31m❌ Formato inválido. Use guiones (YYYY-MM-DD)\033[0m\n";
                sleep(2);
                continue;
            }

            list($anio, $mes, $dia) = explode('-', $fechaInput);
            if (!checkdate((int)$mes, (int)$dia, (int)$anio)) {
                echo "\033[31m❌ Fecha inválida.\033[0m\n";
                sleep(2);
                continue;
            }

            $fechaTurnoObj = new DateTime($fechaInput);
            $hoy = new DateTime();
            $hoy->setTime(0, 0);

            if ($fechaTurnoObj < $hoy) {
                echo "\033[31m❌ No se pueden agendar turnos en el pasado.\033[0m\n";
                sleep(2);
                continue;
            }

            break;
        } while (true);

        $fechaTurno = $fechaInput;
        $horariosOcupados = Turno::obtenerHorariosOcupados($medico['dni'], $fechaTurno);

        $horariosPosibles = [];
        for ($hora = 9; $hora < 15; $hora++) {
            $horariosPosibles[] = sprintf("%02d:00:00", $hora);
            $horariosPosibles[] = sprintf("%02d:30:00", $hora);
        }

        $horariosDisponibles = array_values(array_diff($horariosPosibles, $horariosOcupados));

        if (empty($horariosDisponibles)) {
            echo "\033[31mNo hay horarios disponibles para el médico en esa fecha.\033[0m\n";
            return;
        }

        echo "Horarios disponibles:\n";
        foreach ($horariosDisponibles as $i => $horario) {
            echo ($i + 1) . ". $horario\n";
        }

        echo "Seleccione horario (número): ";
        $horarioSeleccionado = intval(trim(fgets(STDIN)));
        if ($horarioSeleccionado < 1 || $horarioSeleccionado > count($horariosDisponibles)) {
            echo "\033[31m❌ Horario inválido.\033[0m\n";
            return;
        }

        $horaTurno = $horariosDisponibles[$horarioSeleccionado - 1];
        $fechaHoraTurno = $fechaTurno . " " . $horaTurno;

        $salaDisponible = Turno::obtenerSalaDisponible($medico['dni'], $fechaHoraTurno);
        if (!$salaDisponible) {
            echo "\033[31m❌ No hay salas disponibles para ese horario.\033[0m\n";
            return;
        }

        echo "Ingrese DNI del paciente: ";
        $dniPaciente = trim(fgets(STDIN));
        if (!Paciente::existePaciente($dniPaciente)) {
            echo "\033[31m❌ Paciente no registrado.\033[0m\n";
            return;
        }

        if (Turno::pacienteTieneTurno($dniPaciente, $fechaHoraTurno)) {
            echo "\033[31m❌ El paciente ya tiene un turno agendado para esa fecha y hora.\033[0m\n";
            return;
        }

        $consultorio = "SaludAR";

        $turno = new Turno($medico['dni'], $dniPaciente, $consultorio, $fechaHoraTurno, $salaDisponible);
        if ($turno->guardar()) {
            echo "\n\033[32m✅ Turno reservado exitosamente.\033[0m\n";
            echo "🧑‍⚕️ Médico: {$medico['nombre']}\n";
            echo "📅 Fecha y hora: {$fechaHoraTurno}\n";
            echo "🏥 Sala: {$salaDisponible} del consultorio {$consultorio}\n";
        } else {
            echo "\033[31m❌ Error al reservar turno.\033[0m\n";
        }
    }

    public static function listarTurnos() {
        $turnos = Turno::listarTurnos();
        if (empty($turnos)) {
            echo "\033[33mNo hay turnos registrados.\033[0m\n";
            return;
        }

        echo "\n\033[1;36m=== Lista de Turnos ===\033[0m\n";
        foreach ($turnos as $turno) {
            echo "🧑 Paciente: " . $turno['nombrePaciente'] . "\n";
            echo "🧑‍⚕️ Médico: " . $turno['nombreMedico'] . "\n";
            echo "📅 Fecha y hora: " . $turno['fechaTurno'] . "\n";
            echo "🏥 Sala: " . $turno['idSala'] . " | Consultorio: " . $turno['nombreConsultorio'] . "\n";
            echo "----------------------------------------\n";
        }
    }

    public static function cancelarTurno() {
        echo "Ingrese DNI del paciente: ";
        $dniPaciente = trim(fgets(STDIN));

        $turnos = Turno::obtenerTurnosPorPaciente($dniPaciente);

        if (empty($turnos)) {
            echo "\033[33m⚠️ Este paciente no tiene turnos asignados.\033[0m\n";
            return;
        }

        echo "\nTurnos asignados:\n";
        foreach ($turnos as $i => $turno) {
            echo ($i + 1) . ". Fecha: {$turno['fechaTurno']} | Sala: {$turno['idSala']} | Consultorio: {$turno['nombreConsultorio']}\n";
        }

        echo "Seleccione el número del turno a cancelar: ";
        $opcion = intval(trim(fgets(STDIN)));

        if ($opcion < 1 || $opcion > count($turnos)) {
            echo "\033[31m❌ Opción inválida.\033[0m\n";
            return;
        }

        $fechaTurno = $turnos[$opcion - 1]['fechaTurno'];

        Turno::cancelarTurno($dniPaciente, $fechaTurno);
    }

    public static function modificarTurno() {
        echo "Ingrese DNI del paciente: ";
        $dniPaciente = trim(fgets(STDIN));

        if (!Paciente::existePaciente($dniPaciente)) {
            echo "\033[31m❌ Paciente no registrado.\033[0m\n";
            return;
        }

        $turnos = Turno::obtenerTurnosPorPaciente($dniPaciente);

        if (empty($turnos)) {
            echo "\033[33m⚠️ Este paciente no tiene turnos asignados.\033[0m\n";
            return;
        }

        echo "\nTurnos asignados:\n";
        foreach ($turnos as $i => $turno) {
            echo ($i + 1) . ". Fecha: {$turno['fechaTurno']} | Sala: {$turno['idSala']} | Consultorio: {$turno['nombreConsultorio']}\n";
        }

        echo "Seleccione el número del turno a modificar: ";
        $opcion = intval(trim(fgets(STDIN)));

        if ($opcion < 1 || $opcion > count($turnos)) {
            echo "\033[31m❌ Opción inválida.\033[0m\n";
            return;
        }

        $fechaActual = $turnos[$opcion - 1]['fechaTurno'];

        echo "Ingrese nueva fecha para el turno (YYYY-MM-DD HH:MM:SS): ";
        $nuevaFecha = trim(fgets(STDIN));

        if (!Validador::validarFormatoFechaHora($nuevaFecha)) {
            echo "\033[31m❌ Formato de fecha inválido. Debe ser: YYYY-MM-DD HH:MM:SS\n\033[0m";
            return;
        }

        $conexion = Conexion::getConexion();

        $stmtCheck = $conexion->prepare("SELECT COUNT(*) FROM turnos WHERE dniPaciente = ? AND fechaTurno = ?");
        $stmtCheck->execute([$dniPaciente, $nuevaFecha]);

        if ($stmtCheck->fetchColumn() > 0) {
            echo "\033[31m❌ El paciente ya tiene un turno en esa nueva fecha.\033[0m\n";
            return;
        }

        $stmtUpdate = $conexion->prepare("UPDATE turnos SET fechaTurno = ? WHERE dniPaciente = ? AND fechaTurno = ?");
        $stmtUpdate->execute([$nuevaFecha, $dniPaciente, $fechaActual]);

        if ($stmtUpdate->rowCount() > 0) {
            echo "\033[32m✅ Turno modificado exitosamente.\033[0m\n";
        } else {
            echo "\033[31m❌ No se pudo modificar el turno.\033[0m\n";
        }
    }
}
?>
