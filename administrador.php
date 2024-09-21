<?php


class Administrador extends persona {
    

    public function __construct($nombre, $dni ) {
        parent::__construct($nombre,$dni);
     
    }
    public function toString()
    {
        return  $this->getNombre()." Es Administrador!!: ";
        
        }
    
    }
   
