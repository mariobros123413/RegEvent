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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Asume que esta ruta es tanto para mostrar el formulario (GET) como para procesar la creación (POST)
            require __DIR__ . '/../src/Negocio/EventosController.php';
            $eventosController = new EventosController($conexion);
            $eventosController->crearEvento();
        } else {
            // Mostrar el formulario de creación de eventos
            require __DIR__ . '/../src/Presentacion/views/eventos/crear.php';
        }
        break;


    case '/eventos/listar':
        require __DIR__ . '/../src/Negocio/EventosController.php';
        $eventosController = new EventosController($conexion);
        $eventos = $eventosController->listarEventos(); // Obtener los eventos
        require __DIR__ . '/../src/Presentacion/views/eventos/listar.php'; // Incluir la vista
        break;

    // Agrega esta ruta para manejar la edición de eventos
    case '/eventos/editar':
        require __DIR__ . '/../src/Negocio/EventosController.php';
        $eventosController = new EventosController($conexion);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Llama al método editarEvento para procesar los datos del formulario
            $eventosController->editarEvento();
        } else {
            // Si no es una solicitud POST, podrías redirigir al usuario o mostrar un error
            header("Location: /"); // Por ejemplo, redirige al inicio
        }
        break;

    case '/eventos/eliminar':
        require __DIR__ . '/../src/Negocio/EventosController.php';
        $eventosController = new EventosController($conexion);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Llama al método eliminarEvento para procesar la eliminación del evento
            $eventosController->eliminarEvento();
        } else {
            // Si no es una solicitud POST, podrías redirigir al usuario o mostrar un error
            header("Location: /"); // Por ejemplo, redirige al inicio
        }
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

