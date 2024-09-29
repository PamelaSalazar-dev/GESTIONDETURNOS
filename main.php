<?php
require_once 'conexion.php';
require_once 'Paciente.php';
require_once 'Medico.php';
require_once 'Menu.php';

function manejarOpcion($opcion) {
    switch ($opcion) {
        case '1':
            mostrarSubmenuPacientes();
            break;
        case '2':
            mostrarSubmenuMedicos();
            break;
        case '3':
            // Implementar opción de turnos
            break;
        case '4':
            exit("Saliendo...\n");
            //break;
        default:
            echo "Opción no válida. Inténtalo de nuevo.\n";
            mostrarMenu();
            break;
    }
}

function mostrarMenu() {
    echo "\nMenú Principal\n";
    echo "1. Pacientes\n";
    echo "2. Médicos\n";
    echo "3. Turnos\n";
    echo "4. Salir\n";
    echo "Selecciona una opción: ";

    $opcion = trim(fgets(STDIN));
    manejarOpcion($opcion);
}

// Comprobar la conexión
Conexion::comprobarConexion();

// Mostrar el menú principal al inicio
mostrarMenu();
?>