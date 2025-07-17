<?php
require_once './models/conexion.php';
require_once './models/paciente.php';
require_once './models/medico.php';
require_once './models/turno.php';
require_once './vista/menu.php'; 
require_once './controllers/pacienteController.php';
require_once './controllers/medicoController.php';
require_once __DIR__ . '/../utils/validador.php';
require_once __DIR__ . '/../utils/funcionesAuxiliares.php';


Conexion::comprobarConexion();

mostrarMenu();
?>
