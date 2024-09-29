<?php

require_once("Persona.php");

class Administrador extends Persona {
    

    public function __construct($nombre, $dni ) {
        parent::__construct($nombre,$dni);
     
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getDni(){
        return $this->dni;
    }
    public function toString(){
        return  $this->getNombre()." Es Administrador!!: ";
    }
    
    }