<?php
class Consultorio{
    private $nombre;
    private $persona;
    private $turno;//se agrega array de turnos

    public function __construct($nombre) {
    $this->nombre= $nombre;
    $this->persona=[];
    $this->turno=[];//se agrega al constructor
    }
    
}