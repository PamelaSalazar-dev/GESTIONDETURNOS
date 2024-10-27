<?php
require_once './models/medico.php';

class MedicoController {
    public static function agregarMedico($matricula, $especialidad, $dni) {
        // se una instancia de Medico
        $medico = new Medico($matricula, $especialidad, $dni);

        // guardar el medico
        if ($medico->guardar()) {
            echo "Médico guardado correctamente.\n";
        } else {
            echo "Error al guardar el médico. Verifica los datos ingresados.\n";
        }
    }

    public static function listarMedicos() {
        $medicos = Medico::todosLosMedicos(); // Mtodo para listar medicos
        echo "=== Lista de Médicos ===\n";
        foreach ($medicos as $medico) {
            echo "Matrícula: " . $medico->getMatricula() . " - Especialidad: " . $medico->getEspecialidad() . "\n";
        }
    }
}


