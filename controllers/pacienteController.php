<?php
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Medico.php'; // necesario para validar que no sea médico

class PacienteController {

    public static function agregarPaciente($dni, $nombre, $obraSocial, $historiaClinica) {
        if (!Persona::existe($dni)) {
            return ['error' => 'No existe persona con DNI ' . $dni];
        }

        // 🔹 Validar que el nombre coincida con el que ya está en personas
        $nombreRegistrado = Persona::obtenerNombre($dni);
        if ($nombreRegistrado && strcasecmp(trim($nombreRegistrado), trim($nombre)) !== 0) {
            return ['error' => 'El nombre ingresado no coincide con el registrado para el DNI ' . $dni];
        }

        if (Paciente::existePaciente($dni)) {
            return ['error' => 'Ya existe un paciente con DNI ' . $dni];
        }

        if (Medico::existeMedico($dni)) {
            return ['error' => 'El DNI ' . $dni . ' ya está registrado como médico'];
        }

        $resultado = Paciente::agregar($dni, $nombre, $obraSocial, $historiaClinica);
        if ($resultado) {
            return ['success' => 'Paciente agregado correctamente.'];
        } else {
            return ['error' => 'Error al agregar paciente.'];
        }
    }

    public static function modificarPaciente($dni, $obraSocial) {
        if (!Paciente::existePaciente($dni)) {
            return ['error' => 'No existe paciente con DNI ' . $dni];
        }
        $resultado = Paciente::modificarObraSocial($dni, $obraSocial);
        if ($resultado) {
            return ['success' => 'Paciente modificado correctamente.'];
        } else {
            return ['error' => 'Error al modificar paciente.'];
        }
    }

    public static function eliminarPaciente($dni) {
        if (!Paciente::existePaciente($dni)) {
            return ['error' => 'No existe paciente con DNI ' . $dni];
        }
        $resultado = Paciente::eliminar($dni);
        if ($resultado) {
            return ['success' => 'Paciente eliminado correctamente.'];
        } else {
            return ['error' => 'Error al eliminar paciente.'];
        }
    }

    public static function listarPacientes() {
        return Paciente::listar();
    }

    public static function existePaciente($dni) {
        return Paciente::existePaciente($dni);
    }
}
?>
