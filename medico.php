<?php

require_once("Persona.php");

class Medico extends Persona{
    private $matricula;
    private $especialidad;

    public function __construct($nombre, $dni, $matricula, $especialidad) {
        parent::__construct($nombre, $dni);
        $this->matricula = $matricula;
        $this->especialidad = $especialidad;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getDni(){
        return $this->dni;
    }

    public function getMatricula(){
        return $this->matricula;
    }

    public function getEspecialidad(){
        return $this->especialidad;
    }
    public function toString(){
        return "Nombre:".$this->getNombre()." Dni:".$this->getDni();
    } 

    public static function todosLosMedicos() {
        $sql = "SELECT matricula, especialidad FROM medicos";
        return Conexion::query($sql);
    }

    function listarMedicos() {
        $medicos = Medico::todosLosMedicos();

        foreach ($medicos as $medico) {
             echo "Nombre: ".$this->getnombre()."\nMatrícula: ".$this->getMatricula(). "\nEspecialidad: ".$this->getEspecialidad();
        }
    }
    function listarMedicosPorEspecialidad($especialidad) {//nueva funcion agregada
        $medicos = Medico::todosLosMedicos();

        foreach ($medicos as $medico) {
            if($medico->especialidad == $especialidad)//comprobar que ande!!!
            echo "Matrícula: {$medico->matricula} | Especialidad: {$medico->especialidad}\n";
        }
    }
}

    