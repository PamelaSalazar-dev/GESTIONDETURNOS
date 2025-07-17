<?php
require_once __DIR__ . '/../models/medico.php';
require_once __DIR__ . '/../utils/validador.php';

class MedicoController {

    public static function agregarMedico($matricula, $especialidad, $dni) {
        if (!Validador::validarMatricula($matricula)) {
            echo "❌ Matrícula inválida. Debe contener exactamente 5 números.\n";
            return;
        }

        if (!Validador::validarNombre($especialidad)) {
            echo "❌ Especialidad inválida. Solo se permiten letras y espacios.\n";
            return;
        }

        if (!Validador::validarDNI($dni)) {
            echo "❌ DNI inválido. Debe contener exactamente 8 dígitos.\n";
            return;
        }

        $medico = new Medico($matricula, $especialidad, $dni);
        if ($medico->guardar()) {
            echo "✅ Médico agregado exitosamente.\n";
        } else {
            echo "❌ No se pudo agregar el médico.\n";
        }
    }

    public static function eliminarMedico($dni) {
        if (!Validador::validarDNI($dni)) {
            echo "❌ DNI inválido.\n";
            return;
        }

        if (Medico::eliminar($dni)) {
            echo "✅ Médico eliminado exitosamente.\n";
        } else {
            echo "❌ No se pudo eliminar el médico. Verifique si tiene turnos asignados.\n";
        }
    }

    public static function listarMedicos() {
        $medicos = Medico::listarMedicos();
        if (empty($medicos)) {
            echo "⚠️ No hay médicos registrados.\n";
        } else {
            foreach ($medicos as $medico) {
                echo "Matrícula: {$medico['matricula']} | Nombre: {$medico['nombre']} | Especialidad: {$medico['especialidad']} | DNI: {$medico['dni']}\n";
            }
        }
    }
}
?>
