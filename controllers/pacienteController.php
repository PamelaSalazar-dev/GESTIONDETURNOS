<?php
require_once './models/paciente.php';

class PacienteController {
    public static function listarPacientes() {
        $pacientes = Paciente::listarPacientes();
        if (empty($pacientes)) {
            echo "No hay pacientes registrados.\n";
        } else {
            foreach ($pacientes as $paciente) {
                echo "DNI: " . $paciente['dni'] . " | Obra Social: " . $paciente['obraSocial'] . " | Historia Clínica: " . $paciente['historiaClinica'] . "\n";
            }
        }
    }

    public static function agregarPaciente($dni, $obraSocial, $historiaClinica) {
        $paciente = new Paciente($dni, $obraSocial, $historiaClinica);
        if ($paciente->guardar()) {
            echo "Paciente agregado exitosamente.\n";
        } else {
            echo "Error al agregar el paciente.\n";
        }
    }

    public static function modificarPaciente($dni, $obraSocial, $historiaClinica) {
        $paciente = new Paciente($dni, $obraSocial, $historiaClinica);
        if ($paciente->modificar()) {
            echo "Paciente modificado exitosamente.\n";
        } else {
            echo "Error al modificar el paciente.\n";
        }
    }

    public static function eliminarPaciente($dni) {
        if (Paciente::eliminar($dni)) {
            echo "Paciente eliminado exitosamente.\n";
        } else {
            echo "Error al eliminar el paciente.\n";
        }
    }
}
?>
