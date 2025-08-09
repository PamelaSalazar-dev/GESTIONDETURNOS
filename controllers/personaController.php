<?php
require_once __DIR__ . '/../models/Persona.php';

class PersonaController {

    public static function existe($dni) {
        return Persona::existe($dni);
    }

    public static function obtenerNombre($dni) {
        return Persona::obtenerNombre($dni);
    }
}
?>


