<?php

function mostrarSubmenuPacientes() {
    echo "\nMenú de Pacientes\n";
    echo "1. Listar Pacientes\n";
    echo "2. Volver al Menú Principal\n";
    echo "Selecciona una opción: ";

    $opcion = trim(fgets(STDIN));
    manejarSubmenuPacientes($opcion);
}

function manejarSubmenuPacientes($opcion) {
    switch ($opcion) {
        case '1':
            listarPacientes();
            break;
        case '2':
            mostrarMenu(); // Volver al menú principal
            break;
        default:
            echo "Opción no válida. Inténtalo de nuevo.\n";
            mostrarSubmenuPacientes();
            break;
    }
}

function mostrarSubmenuMedicos() {
    echo "\nMenú de Médicos\n";
    echo "1. Listar todos los Médicos\n";
    echo "2. Listar Médicos por especialidad\n";//agregada
    echo "3. Volver al Menú Principal\n";
    echo "Selecciona una opción: ";

    $opcion = trim(fgets(STDIN));
    manejarSubmenuMedicos($opcion);
}

function manejarSubmenuMedicos($opcion) {
    switch ($opcion) {
        case '1':
            listarMedicos();
            break;
        case '2':
            $especialidad = trim(fgets(STDIN));
            listarMedicosPorEspecialidad($especialidad);
            break;    
        case '3':
            mostrarMenu(); // Volver al menú principal
            break;
        default:
            echo "Opción no válida. Inténtalo de nuevo.\n";
            mostrarSubmenuMedicos();
            break;
    }
}
?>