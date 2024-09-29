<?php

require_once("Persona.php");

class Paciente extends Persona {
    private $obraSocial;
    private $historiaClinica;

    public function __construct($nombre, $dni, $historiaClinica, $obraSocial) {
        parent::__construct($nombre, $dni);
        $this->historiaClinica = $historiaClinica;
        $this->obraSocial = $obraSocial;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getDni(){
        return $this->dni;
    }

    public function getHistoriaClinica(){
        return $this->historiaClinica;
    }

    public function getObraSocial(){
        return $this->obraSocial;
    }
    public function toString(){
        return "Nombre:".$this->getNombre()." Dni:".$this->getDni()." Historia Clinica: ".$this->getHistoriaClinica()." Obra Social: ".$this->getObraSocial();
    } 

    public static function todosLosPacientes() {
        $sql = "SELECT dni, obraSocial, historiaClinica FROM pacientes";
        return Conexion::query($sql);
    }


    function listarPacientes() {
        $pacientes = Paciente::todosLosPacientes();

        foreach ($pacientes as $paciente) {
            echo "DNI: {$paciente->dni} | Obra social: {$paciente->obraSocial} | Historia clínica: {$paciente->historiaClinica}\n";
        }
    }

}