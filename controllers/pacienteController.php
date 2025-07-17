<?php
require_once __DIR__ . '/../models/paciente.php';
require_once __DIR__ . '/../utils/validador.php';

class PacienteController {

    public static function agregarPaciente($dni, $nombre, $obraSocial, $historiaClinica) {
        if (!Validador::validarDNI($dni)) {
            echo "❌ DNI inválido. Debe contener exactamente 8 dígitos.\n";
            return;
        }

        if (!Validador::validarNombre($nombre)) {
            echo "❌ Nombre inválido. Solo se permiten letras y espacios.\n";
            return;
        }

        if (!Validador::validarObraSocial($obraSocial)) {
            echo "❌ Obra Social inválida. Solo se permiten letras y espacios.\n";
            return;
        }

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
        if (!Validador::validarDNI($dni)) {
            echo "❌ DNI inválido.\n";
            return;
        }

        if (!Validador::validarObraSocial($obraSocial)) {
            echo "❌ Obra Social inválida.\n";
            return;
        }

        if (!Validador::validarHistoriaClinica($historiaClinica)) {
            echo "❌ Historia clínica inválida.\n";
            return;
        }

        $paciente = new Paciente($dni, '', $obraSocial, $historiaClinica); // Nombre vacío, ya que no se modifica
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
        } else {
            foreach ($pacientes as $paciente) {
                echo "DNI: {$paciente['dni']} | Nombre: {$paciente['nombre']} | Obra Social: {$paciente['obraSocial']} | Historia Clínica: {$paciente['historiaClinica']}\n";
            }
        }
    }
}
?>
