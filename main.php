<?php
require_once __DIR__ . '/models/Conexion.php';
require_once __DIR__ . '/models/Persona.php';
require_once __DIR__ . '/models/Paciente.php';
require_once __DIR__ . '/models/Medico.php';
require_once __DIR__ . '/models/Turno.php';

require_once __DIR__ . '/controllers/PacienteController.php';
require_once __DIR__ . '/controllers/MedicoController.php';
require_once __DIR__ . '/controllers/TurnoController.php';

require_once __DIR__ . '/utils/Validador.php';

require_once __DIR__ . '/vista/menuPrincipal.php';

Conexion::comprobarConexion();

mostrarMenuPrincipal();
