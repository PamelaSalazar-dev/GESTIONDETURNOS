<?php
require_once 'paciente.php';
require_once 'medico.php';
require_once 'turno.php';

function mostrarSubmenuPacientes() {
    echo "\nSubmenú Pacientes\n";
    echo "1. Listar Pacientes\n";
    echo "2. Agregar Paciente\n";
    echo "3. Modificar Paciente\n";
    echo "4. Borrar Paciente\n";
    echo "5. Volver al Menú Principal\n";
    echo "Selecciona una opción: ";

    $opcion = trim(fgets(STDIN));
    manejarOpcionPacientes($opcion);
}

function manejarOpcionPacientes($opcion) {
    switch ($opcion) {
        case '1':
            listarPacientes();
            break;
        // Aquí puedes agregar las funciones de agregar, modificar y borrar pacientes
        case '5':
            return; // Volver al menú principal
        default:
            echo "Opción no válida. Inténtalo de nuevo.\n";
            mostrarSubmenuPacientes();
            break;
    }
}

function mostrarSubmenuMedicos() {
    echo "\nSubmenú Médicos\n";
    echo "1. Listar Médicos\n";
    echo "2. Agregar Médico\n";
    echo "3. Modificar Médico\n";
    echo "4. Borrar Médico\n";
    echo "5. Volver al Menú Principal\n";
    echo "Selecciona una opción: ";

    $opcion = trim(fgets(STDIN));
    manejarOpcionMedicos($opcion);
}

function manejarOpcionMedicos($opcion) {
    switch ($opcion) {
        case '1':
            listarMedicos();
            break;
        // Aquí puedes agregar las funciones de agregar, modificar y borrar médicos
        case '5':
            return; // Volver al menú principal
        default:
            echo "Opción no válida. Inténtalo de nuevo.\n";
            mostrarSubmenuMedicos();
            break;
    }
}

function manejarTurnos() {
    echo "\nSubmenú Turnos\n";
    echo "1. Listar Turnos\n";
    echo "2. Agregar Turno\n";
    echo "3. Volver al Menú Principal\n";
    echo "Selecciona una opción: ";

    $opcion = trim(fgets(STDIN));
    manejarOpcionTurnos($opcion);
}

function manejarOpcionTurnos($opcion) {
    switch ($opcion) {
        case '1':
            listarTurnos(); // Función que debe listar los turnos
            break;
        case '2':
            agregarTurno(); // Implementar esta función para agregar un turno
            break;
        case '3':
            return; // Volver al menú principal
        default:
            echo "Opción no válida. Inténtalo de nuevo.\n";
            manejarTurnos();
            break;
    }
}
?>
