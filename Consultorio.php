<?php
class Consultorio{
    private $nombre;
    private $persona;

    public function __construct($nombre) {
    $this->nombre= $nombre;
    $this->persona=[];
    }
    
}