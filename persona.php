<?php
class Persona{
    protected $nombre;
    protected $dni;

    public function __construct($nombre, $dni){
        $this->nombre=$nombre;
        $this->dni=$dni;
    } 

}
