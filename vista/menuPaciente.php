<?php
require_once __DIR__ . '/../controllers/PacienteController.php';
require_once __DIR__ . '/../utils/Validador.php';
require_once __DIR__ . '/../models/Persona.php'; // Para validar nombre contra DNI

function mostrarMenuPaciente() {
    do {
        echo "\n===== MENU PACIENTES =====\n";
        echo "1. Agregar paciente\n";
        echo "2. Listar pacientes\n";
        echo "3. Modificar paciente\n";
        echo "4. Eliminar paciente\n";
        echo "5. Volver al menú principal\n";
        echo "Seleccione una opción: ";
        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case 1:
                // Agregar paciente
                echo "Ingrese DNI (8 dígitos): ";
                $dni = trim(fgets(STDIN));
                if (!Validador::validarDNI($dni)) {
                    echo "DNI inválido.\n";
                    break;
                }

                // Si existe la persona, validar nombre contra la base
                if (Persona::existe($dni)) {
                    $nombreRegistrado = Persona::obtenerNombre($dni);
                    echo "Ingrese nombre: ";
                    $nombre = trim(fgets(STDIN));
                    if (!Validador::validarNombre($nombre)) {
                        echo "Nombre inválido.\n";
                        break;
                    }
                    if (strcasecmp($nombreRegistrado, $nombre) !== 0) {
                        echo "El nombre no coincide con el registrado para ese DNI.\n";
                        break;
                    }
                } else {
                    // Si no existe, pedir y validar el nombre normalmente
                    echo "Ingrese nombre: ";
                    $nombre = trim(fgets(STDIN));
                    if (!Validador::validarNombre($nombre)) {
                        echo "Nombre inválido.\n";
                        break;
                    }
                }

                echo "Ingrese obra social: ";
                $obraSocial = trim(fgets(STDIN));
                if (!Validador::validarObraSocial($obraSocial)) {
                    echo "Obra social inválida.\n";
                    break;
                }
                echo "Ingrese historia clínica (hasta 6 dígitos): ";
                $historiaClinica = trim(fgets(STDIN));
                if (!Validador::validarHistoriaClinica($historiaClinica)) {
                    echo "Historia clínica inválida.\n";
                    break;
                }

                $respuesta = PacienteController::agregarPaciente($dni, $nombre, $obraSocial, $historiaClinica);
                if (isset($respuesta['error'])) {
                    echo "Error: " . $respuesta['error'] . "\n";
                } else {
                    echo $respuesta['success'] . "\n";
                }
                break;

            case 2:
                // Listar pacientes
                $pacientes = PacienteController::listarPacientes();
                if (empty($pacientes)) {
                    echo "No hay pacientes registrados.\n";
                } else {
                    echo "\n===== LISTADO DE PACIENTES =====\n";
                    foreach ($pacientes as $p) {
                        echo "------------------------------\n";
                        echo "DNI: {$p['dni']}\n";
                        echo "Nombre: {$p['nombre']}\n";
                        echo "Obra social: {$p['obraSocial']}\n";
                        echo "Historia clínica: {$p['historiaClinica']}\n";
                    }
                    echo "------------------------------\n\n";
                }
                break;

            case 3:
                // Modificar paciente (obra social)
                echo "Ingrese DNI del paciente a modificar: ";
                $dniMod = trim(fgets(STDIN));
                if (!Validador::validarDNI($dniMod)) {
                    echo "DNI inválido.\n";
                    break;
                }
                echo "Ingrese nueva obra social: ";
                $nuevaObraSocial = trim(fgets(STDIN));
                if (!Validador::validarObraSocial($nuevaObraSocial)) {
                    echo "Obra social inválida.\n";
                    break;
                }
                $respuesta = PacienteController::modificarPaciente($dniMod, $nuevaObraSocial);
                if (isset($respuesta['error'])) {
                    echo "Error: " . $respuesta['error'] . "\n";
                } else {
                    echo $respuesta['success'] . "\n";
                }
                break;

            case 4:
                // Eliminar paciente
                echo "Ingrese DNI del paciente a eliminar: ";
                $dniEliminar = trim(fgets(STDIN));
                if (!Validador::validarDNI($dniEliminar)) {
                    echo "DNI inválido.\n";
                    break;
                }
                $respuesta = PacienteController::eliminarPaciente($dniEliminar);
                if (isset($respuesta['error'])) {
                    echo "Error: " . $respuesta['error'] . "\n";
                } else {
                    echo $respuesta['success'] . "\n";
                }
                break;

            case 5:
                return; // Volver al menú principal

            default:
                echo "Opción inválida.\n";
        }
    } while (true);
}
