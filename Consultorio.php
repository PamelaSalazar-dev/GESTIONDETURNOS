<?php
class Consultorio{
    private $nombre;
    private $persona;
    private $turnos;//se agrega array de turnos

    public function __construct($nombre) {
    $this->nombre= $nombre;
    $this->persona=[];
    $this->turnos=[];//se agrega al constructor
    }
    
}