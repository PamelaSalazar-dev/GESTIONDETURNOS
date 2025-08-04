<?php
require_once __DIR__ . '/../models/paciente.php';
require_once __DIR__ . '/../utils/validador.php';

class PacienteController {

    public static function agregarPaciente($dni, $nombre, $obraSocial, $historiaClinica) {
        // Validar que el DNI tenga formato correcto
        if (!Validador::validarDNI($dni)) {
            echo "❌ DNI inválido. Debe contener exactamente 8 dígitos.\n";
            return;
        }

        // Validar que el nombre sea válido (letras y espacios)
        if (!Validador::validarNombre($nombre)) {
            echo "❌ Nombre inválido. Solo se permiten letras y espacios.\n";
            return;
        }

        // Validar obra social
        if (!Validador::validarObraSocial($obraSocial)) {
            echo "❌ Obra Social inválida. Solo se permiten letras y espacios.\n";
            return;
        }

        // Validar historia clínica (ejemplo: hasta 6 números)
        if (!Validador::validarHistoriaClinica($historiaClinica)) {
            echo "❌ Historia clínica inválida. Debe contener hasta 6 números.\n";
            return;
        }

        $paciente = new Paciente($dni, $nombre, $obraSocial, $historiaClinica);
        if ($paciente->guardar()) {
            echo "✅ Paciente agregado exitosamente.\n";
        } else {
            echo "❌ No se pudo agregar el paciente.\n";
        }
    }

    public static function modificarPaciente($dni, $obraSocial, $historiaClinica) {
        // Validar DNI
        if (!Validador::validarDNI($dni)) {
            echo "❌ DNI inválido.\n";
            return;
        }

        // Validar obra social
        if (!Validador::validarObraSocial($obraSocial)) {
            echo "❌ Obra Social inválida.\n";
            return;
        }

        // Validar historia clínica
        if (!Validador::validarHistoriaClinica($historiaClinica)) {
            echo "❌ Historia clínica inválida.\n";
            return;
        }

        // En este caso no modificamos el nombre, por eso lo dejamos vacío
        $paciente = new Paciente($dni, '', $obraSocial, $historiaClinica);
        if ($paciente->modificar()) {
            echo "✅ Paciente modificado exitosamente.\n";
        } else {
            echo "❌ Error al modificar el paciente.\n";
        }
    }

    public static function eliminarPaciente($dni) {
        if (!Validador::validarDNI($dni)) {
            echo "❌ DNI inválido.\n";
            return;
        }

        // Verificamos que no tenga turnos asignados
        if (Paciente::tieneTurnosAsignados($dni)) {
            echo "❌ No se puede eliminar el paciente porque tiene turnos asignados. Cancele los turnos primero.\n";
            return;
        }

        if (Paciente::eliminar($dni)) {
            echo "✅ Paciente eliminado exitosamente.\n";
        } else {
            echo "❌ No se pudo eliminar el paciente.\n";
        }
    }

    public static function listarPacientes() {
        $pacientes = Paciente::listarPacientes();
        if (empty($pacientes)) {
            echo "⚠️ No hay pacientes registrados.\n";
            return;
        }

        foreach ($pacientes as $paciente) {
            echo "DNI: {$paciente['dni']} | Nombre: {$paciente['nombre']} | Obra Social: {$paciente['obraSocial']} | Historia Clínica: {$paciente['historiaClinica']}\n";
        }
    }
}
?>


