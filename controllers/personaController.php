<?php
require_once __DIR__ . '/../models/persona.php';

class PersonaController {
    public static function agregarPersona($dni, $nombre) {
        if (!Persona::guardar($dni, $nombre)) {
            echo "❌ No se pudo registrar la persona.\n";
        } else {
            echo "✅ Persona registrada correctamente.\n";
        }
    }
}

