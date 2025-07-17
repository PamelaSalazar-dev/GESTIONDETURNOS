<?php
class Validador {

    // Valida que el DNI tenga exactamente 8 dígitos numéricos
    public static function validarDNI($dni) {
        return preg_match('/^\d{8}$/', $dni);
    }

    // Valida que el nombre contenga solo letras y espacios (incluye acentos y ñ)
    public static function validarNombre($nombre) {
        $nombre = trim($nombre);
        return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $nombre);
    }

    // Valida que la obra social contenga solo letras y espacios
    public static function validarObraSocial($obraSocial) {
        return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $obraSocial);
    }

    // Valida que la historia clínica sea numérica de hasta 6 dígitos
    public static function validarHistoriaClinica($historiaClinica) {
        return preg_match('/^\d{1,6}$/', $historiaClinica);
    }

    // Valida que la matrícula tenga exactamente 5 dígitos numéricos
    public static function validarMatricula($matricula) {
        return preg_match('/^\d{5}$/', $matricula);
    }

    // Valida que el formato de fecha y hora sea YYYY-MM-DD HH:MM:SS
    public static function validarFormatoFechaHora($fechaHora) {
        return preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $fechaHora);
    }

    // (opcional) Valida solo el formato de fecha YYYY-MM-DD
    public static function validarFormatoFecha($fecha) {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha);
    }
}
?>
