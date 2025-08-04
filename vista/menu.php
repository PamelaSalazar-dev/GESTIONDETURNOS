<?php
require_once __DIR__ . '/../controllers/pacienteController.php';
require_once __DIR__ . '/../controllers/medicoController.php';
require_once __DIR__ . '/../controllers/turnoController.php';
require_once __DIR__ . '/../models/persona.php'; // Para validar DNI en personas

function limpiarConsola() {
    if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
        system('cls');
    } else {
        system('clear');
    }
}

function mostrarMenu() {
    do {
        limpiarConsola();
        echo "\033[1;36m=== Menú Principal ===\033[0m\n";
        echo "1. Gestionar Pacientes\n";
        echo "2. Gestionar Médicos\n";
        echo "3. Turnos Médicos\n";
        echo "0. Salir\n";
        echo "Seleccione una opción: ";
        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1':
                mostrarSubmenuPacientes();
                break;
            case '2':
                mostrarSubmenuMedicos();
                break;
            case '3':
                mostrarSubmenuTurnos();
                break;
            case '0':
                echo "👋 ¡Hasta luego!\n";
                break;
            default:
                echo "❌ Opción inválida.\n";
                sleep(1.5);
        }
    } while ($opcion !== '0');
}

function mostrarSubmenuPacientes() {
    do {
        limpiarConsola();
        echo "\033[1;33m=== Submenú Pacientes ===\033[0m\n";
        echo "1. Agregar Paciente\n";
        echo "2. Modificar Paciente\n";
        echo "3. Eliminar Paciente\n";
        echo "4. Listar Pacientes\n";
        echo "0. Volver al Menú Principal\n";
        echo "Seleccione una opción: ";
        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1':
                echo "Ingrese DNI (8 dígitos): ";
                $dni = trim(fgets(STDIN));
                
                if (!Persona::existe($dni)) {
                    echo "\033[31m❌ El DNI no existe en la tabla personas. No se puede agregar paciente.\033[0m\n";
                    break;
                }
                
                echo "Ingrese nombre: ";
                $nombre = trim(fgets(STDIN));
                echo "Ingrese Obra Social: ";
                $obraSocial = trim(fgets(STDIN));
                echo "Ingrese Historia Clínica: ";
                $historia = trim(fgets(STDIN));
                PacienteController::agregarPaciente($dni, $nombre, $obraSocial, $historia);
                break;
            case '2':
                echo "Ingrese DNI del paciente a modificar: ";
                $dni = trim(fgets(STDIN));
                echo "Nueva Obra Social: ";
                $obraSocial = trim(fgets(STDIN));
                echo "Nueva Historia Clínica: ";
                $historia = trim(fgets(STDIN));
                PacienteController::modificarPaciente($dni, $obraSocial, $historia);
                break;
            case '3':
                echo "Ingrese DNI del paciente a eliminar: ";
                $dni = trim(fgets(STDIN));
                PacienteController::eliminarPaciente($dni);
                break;
            case '4':
                PacienteController::listarPacientes();
                break;
            case '0':
                return;
            default:
                echo "❌ Opción inválida.\n";
        }

        echo "\nPresione ENTER para continuar...";
        fgets(STDIN);

    } while ($opcion !== '0');
}

function mostrarSubmenuMedicos() {
    do {
        echo "\n=== Submenú Médicos ===\n";
        echo "1. Agregar Médico\n";
        echo "2. Listar Médicos\n";
        echo "3. Eliminar Médico\n";
        echo "0. Volver al Menú Principal\n";
        echo "Seleccione una opción: ";
        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1': // Agregar Médico
                do {
                    echo "Ingrese Matrícula del médico (5 números): ";
                    $matricula = trim(fgets(STDIN));
                    if (!Validador::validarMatricula($matricula)) {
                        echo "❌ Matrícula inválida. Debe contener exactamente 5 números.\n";
                    }
                } while (!Validador::validarMatricula($matricula));

                echo "Ingrese Especialidad del médico: ";
                $especialidad = trim(fgets(STDIN));

                echo "Ingrese DNI del médico: ";
                $dni = trim(fgets(STDIN));

                MedicoController::agregarMedico($matricula, $especialidad, $dni);
                break;

            case '2':
                MedicoController::listarMedicos();
                break;

            case '3':
                echo "Ingrese DNI del médico a eliminar: ";
                $dniEliminar = trim(fgets(STDIN));
                MedicoController::eliminarMedico($dniEliminar);
                break;

            case '0':
                return;

            default:
                echo "Opción inválida. Intente nuevamente.\n";
        }

        echo "Presione ENTER para continuar...";
        fgets(STDIN);
    } while (true);
}

function mostrarSubmenuTurnos() {
    do {
        limpiarConsola();
        echo "\n\033[1;36m=== Turnos Médicos ===\033[0m\n";
        echo "1. Solicitar Turno\n";
        echo "2. Listar Turnos\n";
        echo "3. Cancelar Turno\n";
        echo "4. Modificar Turno\n";
        echo "0. Volver al Menú Principal\n";
        echo "Seleccione una opción: ";
        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1':
                TurnoController::solicitarTurno();
                break;
            case '2':
                TurnoController::listarTurnos();
                break;
            case '3':
                TurnoController::cancelarTurno();
                break;
            case '4':
                TurnoController::modificarTurno();
                break;
            case '0':
                return;
            default:
                echo "❌ Opción inválida.\n";
        }

        echo "\nPresione ENTER para continuar...";
        fgets(STDIN);

    } while ($opcion !== '0');
}

// Ejecutar el menú
mostrarMenu();
