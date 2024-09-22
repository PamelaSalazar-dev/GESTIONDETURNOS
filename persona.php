<?php
 abstract class Persona{
    private $nombre;
    private $dni;

    public function __construct($nombre, $dni){
        $this->nombre=$nombre;
        $this->dni=$dni;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getDni(){
        return $this->dni;
    }
public function toString(){
    return "Nombre:".$this->getNombre()." Dni:".$this->getDni();
}

}
