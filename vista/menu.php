<?php
require_once './controllers/pacienteController.php'; 
require_once './controllers/medicoController.php';
require_once './controllers/turnoController.php'; 

function mostrarMenu() {
    cls(); 
    echo "\033[34m=== Menú Principal ===\033[0m\n"; 
    echo "\033[32m1. Gestionar Pacientes\033[0m\n"; 
    echo "\033[32m2. Gestionar Médicos\033[0m\n"; 
    echo "\033[32m3. Gestionar Turnos\033[0m\n"; 
    echo "\033[31m0. Salir\033[0m\n"; 
    echo "Seleccione una opción: ";

    $opcion = trim(fgets(STDIN));
    switch ($opcion) {
        case '1':
            mostrarSubmenuPacientes();
            break;
        case '2':
            mostrarSubmenuMedicos();
            break;
        case '3': // Caso para gestionar turnos
            mostrarSubmenuTurnos(); // Llama al submenú de turnos
            break;
        case '0':
            echo "Saliendo del programa...\n";
            exit;
        default:
            echo "\033[31mOpción no válida. Inténtalo de nuevo.\033[0m\n";
            mostrarMenu(); 
            break;
    }
}

function mostrarSubmenuPacientes() {
    while (true) {
        cls(); 
        echo "\033[34m=== Submenú de Pacientes ===\033[0m\n";
        echo "\033[32m1. Agregar Paciente\033[0m\n";
        echo "\033[32m2. Modificar Paciente\033[0m\n";
        echo "\033[32m3. Eliminar Paciente\033[0m\n";
        echo "\033[32m4. Listar Pacientes\033[0m\n";
        echo "\033[31m0. Volver al Menú Principal\033[0m\n";
        echo "Seleccione una opción: ";

        $opcion = trim(fgets(STDIN));
        manejarOpcionPacientes($opcion);
    }
}

function manejarOpcionPacientes($opcion) {
    switch ($opcion) {
        case '0':
            mostrarMenu();
            break;
        case '1':
            echo "Ingrese DNI del paciente: ";
            $dni = trim(fgets(STDIN));
            echo "Ingrese Obra Social: ";
            $obraSocial = trim(fgets(STDIN));
            echo "Ingrese Historia Clínica: ";
            $historiaClinica = trim(fgets(STDIN));
            PacienteController::agregarPaciente($dni, $obraSocial, $historiaClinica);
            break;
        case '2':
            echo "Ingrese DNI del paciente a modificar: ";
            $dni = trim(fgets(STDIN));
            echo "Ingrese nueva Obra Social: ";
            $obraSocial = trim(fgets(STDIN));
            echo "Ingrese nueva Historia Clínica: ";
            $historiaClinica = trim(fgets(STDIN));
            PacienteController::modificarPaciente($dni, $obraSocial, $historiaClinica);
            break;
        case '3':
            echo "Ingrese DNI del paciente a eliminar: ";
            $dni = trim(fgets(STDIN));
            PacienteController::eliminarPaciente($dni);
            break;
        case '4':
            PacienteController::listarPacientes();
            break;
        default:
            echo "\033[31mOpción no válida. Inténtalo de nuevo.\033[0m\n";
            manejarOpcionPacientes($opcion); 
            break;
    }
    pressEnter(); 
}

function mostrarSubmenuMedicos() {
    while (true) {
        cls(); // 
        echo "\033[34m=== Submenú de Médicos ===\033[0m\n";
        echo "\033[32m1. Agregar Médico\033[0m\n";
        echo "\033[32m2. Listar Médicos\033[0m\n";
        echo "\033[31m0. Volver al Menú Principal\033[0m\n";
        echo "Seleccione una opción: ";
        
        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1':
                echo "Ingrese Matrícula del médico: ";
                $matricula = trim(fgets(STDIN));
                echo "Ingrese Especialidad del médico: ";
                $especialidad = trim(fgets(STDIN));
                echo "Ingrese DNI del médico: ";
                $dni = trim(fgets(STDIN));

                MedicoController::agregarMedico($matricula, $especialidad, $dni);
                break;
            case '2':
                MedicoController::listarMedicos();
                break;
            case '0':
                return; // Vuelve al menú principal
            default:
                echo "\033[31mOpción no válida. Inténtalo de nuevo.\033[0m\n";
                break;
        }
        pressEnter(); 
    }
}

function mostrarSubmenuTurnos() {
    while (true) {
        cls(); // Limpiar la consola al mostrar el submenú
        echo "\033[34m=== Submenú de Turnos ===\033[0m\n";
        echo "\033[32m1. Agregar Turno\033[0m\n";
        echo "\033[32m2. Listar Turnos\033[0m\n";
        echo "\033[31m0. Volver al Menú Principal\033[0m\n";
        echo "Seleccione una opción: ";

        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1':
                echo "Ingrese DNI del Médico: ";
                $dnimedico = trim(fgets(STDIN));
                echo "Ingrese DNI del Paciente: ";
                $dnipaciente = trim(fgets(STDIN));
                echo "Ingrese Nombre del Consultorio: ";
                $nombreconsultorio = trim(fgets(STDIN));
                echo "Ingrese Fecha del Turno (YYYY-MM-DD HH:MM:SS): ";
                $fechaturno = trim(fgets(STDIN));
                echo "Ingrese ID de la Sala: ";
                $idsala = trim(fgets(STDIN));
                TurnoController::agregarTurno($dnimedico, $dnipaciente, $nombreconsultorio, $fechaturno, $idsala);
                break;

            case '2':
                TurnoController::todosLosTurnos();
                break;

            case '0':
                return; // Vuelve al menú principal
            default:
                echo "\033[31mOpción no válida. Inténtalo de nuevo.\033[0m\n";
                break;
        }
        pressEnter(); 
    }
}

// Función para limpiar la consola
function cls() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        system('cls');
    } else {
        system('clear');
    }
}


function pressEnter() {
    echo "Presione Enter para continuar...";
    fgets(STDIN); 
}


mostrarMenu();
