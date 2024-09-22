<?php

class Medico {
    private $matricula;
    private $especialidad;

    public function __construct($matricula, $especialidad) {
        $this->matricula = $matricula;
        $this->especialidad = $especialidad;
    }

    public static function todosLosMedicos() {
        $sql = "SELECT matricula, especialidad FROM medicos";
        return Conexion::query($sql);
    }
}

    function listarMedicos() {
        $medicos = Medico::todosLosMedicos();

        foreach ($medicos as $medico) {
            echo "Matrícula: {$medico->matricula} | Especialidad: {$medico->especialidad}\n";
        }
    }
    function listarMedicosPorEspecialidad($especialidad) {//nueva funcion agregada
        $medicos = Medico::todosLosMedicos();

        foreach ($medicos as $medico) {
            if($medico->especialidad == $especialidad)//comprobar que ande!!!
            echo "Matrícula: {$medico->matricula} | Especialidad: {$medico->especialidad}\n";
        }
    }
?>
