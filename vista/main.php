<?php
require_once './models/conexion.php'; // Ajusta la ruta
require_once './models/paciente.php'; // Ajusta la ruta
require_once './models/medico.php';
require_once './models/turno.php'; // Ajusta la ruta
require_once './models/turno.php'; // Ajusta la ruta
require_once './vista/menu.php'; // Asegúrate de que este archivo contenga la lógica de menú
require_once './controllers/pacienteController.php';
require_once './controllers/medicoController.php';
require_once './controllers/turnoController.php';

// Comprobar la conexión
Conexion::comprobarConexion();

// Mostrar el menú principal al inicio
mostrarMenu();
?>
