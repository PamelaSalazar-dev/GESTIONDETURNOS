<?php
require_once 'models/Medico.php';
require_once 'models/Persona.php';  // <-- agregado para validar existencia de persona
require_once 'models/Paciente.php'; // <-- agregado para validar que no sea paciente

class MedicoController {

    public static function agregarMedico($dni, $matricula, $especialidad) {
        if (!Persona::existe($dni)) {
            return ['error' => 'No existe persona con DNI ' . $dni];
        }
        if (Medico::existeMedico($dni)) {
            return ['error' => 'Ya existe un médico con DNI ' . $dni];
        }
        if (Paciente::existePaciente($dni)) {
            return ['error' => 'El DNI ' . $dni . ' ya está registrado como paciente'];
        }
        $resultado = Medico::agregar($dni, $matricula, $especialidad);
        if ($resultado) {
            return ['success' => 'Médico agregado correctamente.'];
        } else {
            return ['error' => 'Error al agregar médico.'];
        }
    }

    public static function listarMedicos() {
        return Medico::listar();
    }

    public static function eliminarMedico($dni) {
        if (Medico::tieneTurnos($dni)) {
            return ['error' => 'No se puede eliminar el médico porque tiene turnos asignados.'];
        }
        $resultado = Medico::eliminar($dni);
        if ($resultado) {
            return ['success' => 'Médico eliminado correctamente.'];
        } else {
            return ['error' => 'Error al eliminar médico.'];
        }
    }

    public static function existeMedico($dni) {
        return Medico::existeMedico($dni);
    }

    public static function obtenerMedicosPorEspecialidad($especialidad) {
        return Medico::obtenerMedicosPorEspecialidad($especialidad);
    }

    public static function obtenerEspecialidades() {
        return Medico::obtenerEspecialidades();
    }
}
?>
