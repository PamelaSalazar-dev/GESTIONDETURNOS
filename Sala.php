<?php

require_once 'Medico.php';
require_once 'Paciente.php';
require_once 'Consultorio.php';

class Sala {
    private $nombre;
    private $turnos;
    private const MAX_TURNOS = 8;

    // Constructor
    public function __construct($nombre) {
        if (empty($nombre)) {
            throw new InvalidArgumentException("El nombre de la sala no puede estar vacío.");
        }
        $this->nombre = $nombre;
        $this->turnos = [];
    }

    // Métodos getter
    public function getNombre() {
        return $this->nombre;
    }

    public function getTurnos() {
        return $this->turnos;
    }

    public function getCantidadTurnosDisponibles($nombreSala, $fecha) {
        if ($this->nombre !== $nombreSala) {
            throw new InvalidArgumentException("El nombre de la sala no coincide.");
        }
    
        // Filtrar los turnos por fecha
        $fechaDateTime = DateTime::createFromFormat('Y-m-d H:i', $fecha);
        if (!$fechaDateTime) {
            throw new InvalidArgumentException("Fecha no válida.");
        }

        
    }

    // Método para agregar un turno
    public function agregarTurno(Turno $turno) {
        if (count($this->turnos) >= self::MAX_TURNOS) {
            throw new RuntimeException("No se pueden agregar más turnos. La sala está llena.");
        }
        $this->turnos[] = $turno;
    }
}