<?php
require_once './models/conexion.php'; // Asegúrate de incluir la clase de conexión

class Medico {
    private $matricula;
    private $especialidad;
    private $dni;
    private $db; 

    public function __construct($matricula, $especialidad, $dni) {
        $this->matricula = $matricula;
        $this->especialidad = $especialidad;
        $this->dni = $dni;
        $this->db = Conexion::getConexion(); 
    }

    public function guardar() {
        $sql = "INSERT INTO medicos (matricula, especialidad, dni) VALUES (:matricula, :especialidad, :dni)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':matricula', $this->matricula);
        $stmt->bindParam(':especialidad', $this->especialidad);
        $stmt->bindParam(':dni', $this->dni);
        return $stmt->execute();
    }

    public static function todosLosMedicos() {
        $db = Conexion::getConexion(); 
        $sql = "SELECT * FROM medicos";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $medicos = [];

        foreach ($resultados as $row) {
            // Crear una nueva instancia de Medico para cada fila
            $medicos[] = new Medico($row['matricula'], $row['especialidad'], $row['dni']);
        }

        return $medicos; 
    }

    // Métodos para obtener matrícula y especialidad
    public function getMatricula() {
        return $this->matricula;
    }

    public function getEspecialidad() {
        return $this->especialidad;
    }
}

