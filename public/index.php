<?php
require_once '../src/Datos/Database.php';

// Define las rutas
$ruta = $_SERVER['REQUEST_URI'];
$database = Database::getInstance();
$conexion = $database->getConnection();
switch ($ruta) {
    case '/':
        require __DIR__ . '/../src/Presentacion/views/home.php';
        break;
    case '/eventos/crear':
        require __DIR__ . '/../src/Presentacion/views/eventos/crear.php';
        break;


    case '/eventos/listar':
        require __DIR__ . '/../src/Negocio/EventosController.php';
        $eventosController = new EventosController($conexion);
        $eventos = $eventosController->listarEventos(); // Obtener los eventos
        require __DIR__ . '/../src/Presentacion/views/eventos/listar.php'; // Incluir la vista
        break;

    case '/eventos/obtener':
        require __DIR__ . '/../src/Negocio/EventosController.php';
        $eventosController = new EventosController($conexion);
        $idEvento = isset($_GET['id']) ? $_GET['id'] : null;
        $eventosController->obtenerEventoPorId($idEvento);
        break;


    case '/invitaciones/crear':
        require __DIR__ . '/../src/Presentacion/views/invitaciones/crear.php';
        break;
    case '/invitaciones/listar':
        require __DIR__ . '/../src/Presentacion/views/invitaciones/listar.php';
        break;

    case '/eventos/crearController':
        require __DIR__ . '/../src/Negocio/EventosController.php';
        break;


    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}

