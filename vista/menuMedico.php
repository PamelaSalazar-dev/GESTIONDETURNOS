<?php
require_once __DIR__ . '/../controllers/MedicoController.php';
require_once __DIR__ . '/../utils/Validador.php';

function mostrarMenuMedico() {
    do {
        echo "\n===== MENU MÉDICOS =====\n";
        echo "1. Agregar médico\n";
        echo "2. Listar médicos\n";
        echo "3. Eliminar médico\n";
        echo "4. Volver al menú principal\n";
        echo "Seleccione una opción: ";
        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case 1:
                echo "Ingrese DNI (8 dígitos): ";
                $dni = trim(fgets(STDIN));
                if (!Validador::validarDNI($dni)) {
                    echo "DNI inválido.\n";
                    break;
                }
                // No se pide nombre porque no es necesario para agregar médico
                echo "Ingrese matrícula (5 dígitos): ";
                $matricula = trim(fgets(STDIN));
                if (!Validador::validarMatricula($matricula)) {
                    echo "Matrícula inválida.\n";
                    break;
                }
                echo "Ingrese especialidad: ";
                $especialidad = trim(fgets(STDIN));
                if (!Validador::validarNombre($especialidad)) {
                    echo "Especialidad inválida.\n";
                    break;
                }

                $respuesta = MedicoController::agregarMedico($dni, $matricula, $especialidad);
                if (isset($respuesta['error'])) {
                    echo "Error: " . $respuesta['error'] . "\n";
                } else {
                    echo $respuesta['success'] . "\n";
                }
                break;

            case 2:
                $medicos = MedicoController::listarMedicos();
                if (empty($medicos)) {
                    echo "No hay médicos registrados.\n";
                } else {
                    echo "\n===== LISTADO DE MÉDICOS =====\n";
                    foreach ($medicos as $m) {
                        echo "------------------------------\n";
                        echo "DNI: {$m['dni']}\n";
                        echo "Matrícula: {$m['matricula']}\n";
                        echo "Especialidad: {$m['especialidad']}\n";
                    }
                    echo "------------------------------\n\n";
                }
                break;

            case 3:
                echo "Ingrese DNI del médico a eliminar: ";
                $dniEliminar = trim(fgets(STDIN));
                if (!Validador::validarDNI($dniEliminar)) {
                    echo "DNI inválido.\n";
                    break;
                }
                $respuesta = MedicoController::eliminarMedico($dniEliminar);
                if (isset($respuesta['error'])) {
                    echo "Error: " . $respuesta['error'] . "\n";
                } else {
                    echo $respuesta['success'] . "\n";
                }
                break;

            case 4:
                return; // Volver al menú principal

            default:
                echo "Opción inválida.\n";
        }
    } while (true);
}
