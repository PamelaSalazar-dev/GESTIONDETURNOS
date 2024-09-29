<?php

require_once 'Medico.php';
require_once 'Paciente.php';
require_once 'Consultorio.php';

class Turno {
    private $fecha;
    private $doctor;
    private $paciente;

    // Constructor
    public function __construct($fecha, $doctor, $paciente) {
        if (empty($doctor) || empty($paciente)) {
            throw new InvalidArgumentException("El doctor y el paciente no pueden estar vacíos.");
        }
        $dateTime = DateTime::createFromFormat('Y-m-d H:i', $fecha);
        if (!$dateTime) {
            throw new InvalidArgumentException("Fecha no válida.");
        }
        $this->fecha = $dateTime->format('Y-m-d H:i');
        $this->doctor = $doctor;
        $this->paciente = $paciente;
    }

    // Métodos getter
    public function getFecha() {
        return $this->fecha;
    }

    public function getDoctor() {
        return $this->doctor;
    }

    public function getPaciente() {
        return $this->paciente;
    }

    public function cambiarTurno($nuevaFecha) {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i', $nuevaFecha);
        if (!$dateTime) {
            throw new InvalidArgumentException("Fecha no válida.");
        }
        $this->fecha = $dateTime->format('Y-m-d H:i');
    }

    public function mostrarInformacion() {
        $fechaFormateada = (new DateTime($this->fecha))->format('d/m/Y H:i');
        return "Fecha: $fechaFormateada, Doctor: {$this->doctor}, Paciente: {$this->paciente}";
    }
}