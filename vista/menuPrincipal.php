<?php
require_once __DIR__ . '/../utils/FuncionesAuxiliares.php';
require_once 'menuPaciente.php';
require_once 'menuMedico.php';
require_once 'menuTurno.php';

function mostrarMenuPrincipal() {
    do {
        FuncionesAuxiliares::limpiarPantalla();

        echo "===== MENU PRINCIPAL =====\n";
        echo "1. Pacientes\n";
        echo "2. Médicos\n";
        echo "3. Turnos\n";
        echo "4. Salir\n";
        echo "Seleccione una opción: ";

        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case 1:
                mostrarMenuPaciente();
                break;
            case 2:
                mostrarMenuMedico();
                break;
            case 3:
                mostrarMenuTurno();
                break;
            case 4:
                echo "Saliendo...\n";
                exit;
            default:
                echo "Opción inválida. Intente nuevamente.\n";
                FuncionesAuxiliares::pausar();
                break;
        }
    } while (true);
}
