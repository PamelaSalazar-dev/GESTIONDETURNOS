<?php
require_once __DIR__ . '/../controllers/TurnoController.php';
require_once __DIR__ . '/../controllers/PacienteController.php';
require_once __DIR__ . '/../utils/Validador.php';

function mostrarMenuTurno() {
    do {
        echo "\n===== MENU TURNOS =====\n";
        echo "1. Solicitar turno\n";
        echo "2. Listar turnos\n";
        echo "3. Cancelar turno\n";
        echo "4. Modificar turno\n";
        echo "5. Volver al menú principal\n";
        echo "Seleccione una opción: ";
        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case 1:
                // Solicitar turno paso a paso respetando la lógica que tenías
                $especialidades = TurnoController::obtenerEspecialidades();
                if (empty($especialidades)) {
                    echo "No hay especialidades disponibles.\n";
                    break;
                }
                echo "Especialidades disponibles:\n";
                foreach ($especialidades as $i => $esp) {
                    echo ($i + 1) . ". $esp\n";
                }
                echo "Seleccione especialidad (número): ";
                $espSeleccionada = intval(trim(fgets(STDIN)));
                if ($espSeleccionada < 1 || $espSeleccionada > count($especialidades)) {
                    echo "Especialidad inválida.\n";
                    break;
                }
                $especialidad = $especialidades[$espSeleccionada - 1];

                $medicos = TurnoController::obtenerMedicosPorEspecialidad($especialidad);
                if (empty($medicos)) {
                    echo "No hay médicos para la especialidad $especialidad.\n";
                    break;
                }
                echo "Médicos disponibles:\n";
                foreach ($medicos as $i => $med) {
                    echo ($i + 1) . ". Nombre: {$med['nombre']} - Matrícula: {$med['matricula']}\n";
                }
                echo "Seleccione médico (número): ";
                $medSeleccionado = intval(trim(fgets(STDIN)));
                if ($medSeleccionado < 1 || $medSeleccionado > count($medicos)) {
                    echo "Médico inválido.\n";
                    break;
                }
                $medico = $medicos[$medSeleccionado - 1];

                // Validar fecha: repetir hasta que sea válida y no sea pasada
                do {
                    echo "Ingrese fecha para el turno (YYYY-MM-DD): ";
                    $fecha = trim(fgets(STDIN));
                    if (!Validador::validarFormatoFecha($fecha)) {
                        echo "Formato de fecha inválido.\n";
                        $fechaValida = false;
                    } else {
                        $fechaHoy = date('Y-m-d');
                        if ($fecha < $fechaHoy) {
                            echo "No se pueden solicitar turnos para fechas pasadas.\n";
                            $fechaValida = false;
                        } else {
                            $fechaValida = true;
                        }
                    }
                } while (!$fechaValida);

                $horariosDisponibles = TurnoController::obtenerHorariosDisponibles($medico['dni'], $fecha);
                if (empty($horariosDisponibles)) {
                    echo "No hay horarios disponibles para el médico en esa fecha.\n";
                    break;
                }
                echo "Horarios disponibles:\n";
                foreach ($horariosDisponibles as $i => $hora) {
                    echo ($i + 1) . ". $hora\n";
                }
                echo "Seleccione horario (número): ";
                $horarioSeleccionado = intval(trim(fgets(STDIN)));
                if ($horarioSeleccionado < 1 || $horarioSeleccionado > count($horariosDisponibles)) {
                    echo "Horario inválido.\n";
                    break;
                }
                $horaTurno = $horariosDisponibles[$horarioSeleccionado - 1];
                $fechaHora = $fecha . ' ' . $horaTurno;

                echo "Ingrese DNI del paciente: ";
                $dniPaciente = trim(fgets(STDIN));
                if (!Validador::validarDNI($dniPaciente)) {
                    echo "DNI inválido.\n";
                    break;
                }
                if (!PacienteController::existePaciente($dniPaciente)) {
                    echo "Paciente no registrado.\n";
                    break;
                }

                if (!TurnoController::agregarTurno($medico['dni'], $dniPaciente, $fechaHora)) {
                    echo "No se pudo reservar el turno (¿Ya tiene turno en esa fecha/hora o no hay salas disponibles?).\n";
                } else {
                    echo "Turno reservado exitosamente.\n";
                }
                break;

            case 2:
                $turnos = TurnoController::listarTurnos();
                if (empty($turnos)) {
                    echo "No hay turnos registrados.\n";
                } else {
                    echo "\n===== LISTADO DE TURNOS =====\n";
                    foreach ($turnos as $t) {
                        echo "------------------------------\n";
                        echo "Paciente: {$t['nombrePaciente']}\n";
                        echo "Médico: {$t['nombreMedico']}\n";
                        echo "Fecha y hora: {$t['fechaTurno']}\n";
                        echo "Sala: {$t['idSala']}\n";
                    }
                    echo "------------------------------\n\n";
                }
                break;

            case 3:
                echo "Ingrese DNI del paciente para cancelar turno: ";
                $dniCancelar = trim(fgets(STDIN));
                if (!Validador::validarDNI($dniCancelar)) {
                    echo "DNI inválido.\n";
                    break;
                }

                $turnosPaciente = TurnoController::obtenerTurnosPorPaciente($dniCancelar);
                if (empty($turnosPaciente)) {
                    echo "El paciente no tiene turnos para cancelar.\n";
                    break;
                }

                echo "Turnos del paciente:\n";
                foreach ($turnosPaciente as $index => $turno) {
                    echo ($index + 1) . ". Fecha y hora: {$turno['fechaTurno']} - Sala: {$turno['idSala']} - Consultorio: {$turno['nombreConsultorio']}\n";
                }

                echo "Seleccione el número del turno que desea cancelar: ";
                $opcionCancelar = intval(trim(fgets(STDIN)));

                if ($opcionCancelar < 1 || $opcionCancelar > count($turnosPaciente)) {
                    echo "Opción inválida.\n";
                    break;
                }

                $turnoSeleccionado = $turnosPaciente[$opcionCancelar - 1];
                $fechaTurnoCancelar = $turnoSeleccionado['fechaTurno'];

                if (TurnoController::cancelarTurno($dniCancelar, $fechaTurnoCancelar)) {
                    echo "Turno cancelado correctamente.\n";
                } else {
                    echo "No se pudo cancelar el turno.\n";
                }
                break;

            case 4:
                echo "Ingrese DNI del paciente para modificar turno: ";
                $dniModificar = trim(fgets(STDIN));
                if (!Validador::validarDNI($dniModificar)) {
                    echo "DNI inválido.\n";
                    break;
                }

                $turnosPaciente = TurnoController::obtenerTurnosPorPaciente($dniModificar);

                if (empty($turnosPaciente)) {
                    echo "El paciente no tiene turnos para modificar.\n";
                    break;
                }

                echo "Turnos del paciente:\n";
                foreach ($turnosPaciente as $index => $turno) {
                    echo ($index + 1) . ". Fecha y hora: {$turno['fechaTurno']} - Sala: {$turno['idSala']} - Consultorio: {$turno['nombreConsultorio']}\n";
                }

                echo "Seleccione el número del turno que desea modificar: ";
                $opcionModificar = intval(trim(fgets(STDIN)));

                if ($opcionModificar < 1 || $opcionModificar > count($turnosPaciente)) {
                    echo "Opción inválida.\n";
                    break;
                }

                $turnoSeleccionado = $turnosPaciente[$opcionModificar - 1];
                $fechaActual = $turnoSeleccionado['fechaTurno'];

                echo "Ingrese nueva fecha y hora para el turno (YYYY-MM-DD HH:MM:SS): ";
                $nuevaFecha = trim(fgets(STDIN));
                if (!Validador::validarFormatoFechaHora($nuevaFecha)) {
                    echo "Formato de fecha y hora inválido.\n";
                    break;
                }

                if (TurnoController::modificarTurno($dniModificar, $fechaActual, $nuevaFecha)) {
                    echo "Turno modificado correctamente.\n";
                } else {
                    echo "No se pudo modificar el turno (¿existe turno o conflicto?).\n";
                }
                break;

            case 5:
                return; // Volver al menú principal

            default:
                echo "Opción inválida.\n";
        }
    } while (true);
}
?>
