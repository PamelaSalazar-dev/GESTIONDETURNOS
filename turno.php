<?php

require_once 'Medico.php';
require_once 'Paciente.php';

class Turno {
    public $medico;
    public $paciente;

    public function __construct($medico, $paciente) {
        $this->medico = $medico;
        $this->paciente = $paciente;
    }
}
