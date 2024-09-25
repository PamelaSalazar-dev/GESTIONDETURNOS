<?php

class Paciente {
    private $dni;
    private $obraSocial;
    private $historiaClinica;

    public function __construct($dni, $obraSocial, $historiaClinica) {
        $this->dni = $dni;
        $this->obraSocial = $obraSocial;
        $this->historiaClinica = $historiaClinica;
    }

    public static function todosLosPacientes() {
        $sql = "SELECT dni, obraSocial, historiaClinica FROM pacientes";
        return Conexion::query($sql);
    }
}

function listarPacientes() {
    $pacientes = Paciente::todosLosPacientes();

    foreach ($pacientes as $paciente) {
        echo "DNI: {$paciente->dni} | Obra social: {$paciente->obraSocial} | Historia clínica: {$paciente->historiaClinica}\n";
    }
}
?>
                                                                                                                                                                 





