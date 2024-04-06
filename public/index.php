<?php
require_once '../src/Datos/Database.php';

// Define las rutas
$ruta = $_SERVER['REQUEST_URI'];
$database = Database::getInstance();
$conexion = $database->getConnection();
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

// Analiza los parámetros de consulta en un array (si existen)
parse_str($queryString, $queryParams);
switch ($url) {
    case '/':
        require __DIR__ . '/../src/Presentacion/views/home.php';
        break;
    case '/eventos/crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/../src/Negocio/EventosController.php';
            $eventosController = new EventosController($conexion);
            $eventosController->crearEvento();
        } else {
            require __DIR__ . '/../src/Presentacion/views/eventos/crear.php';
        }
        break;


    case '/eventos/listar':
        require __DIR__ . '/../src/Negocio/EventosController.php';
        $eventosController = new EventosController($conexion);
        $eventos = $eventosController->listarEventos(); // Obtener los eventos
        require __DIR__ . '/../src/Presentacion/views/eventos/listar.php'; // Incluir la vista
        break;

    case '/eventos/editar':
        require __DIR__ . '/../src/Negocio/EventosController.php';
        $eventosController = new EventosController($conexion);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventosController->editarEvento();
        } else {
            header("Location: /");
        }
        break;

    case '/eventos/eliminar':
        require __DIR__ . '/../src/Negocio/EventosController.php';
        $eventosController = new EventosController($conexion);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventosController->eliminarEvento();
        } else {
            header("Location: /");
        }
        break;




    case '/eventos/invitaciones':
        require __DIR__ . '/../src/Negocio/InvitacionesController.php';
        $invitacionesController = new InvitacionesController($conexion);
        $idEvento = isset($queryParams['id']) ? $queryParams['id'] : null;
        $nombreEvento = isset($queryParams['titulo']) ? $queryParams['titulo'] : null;
        $lugar = isset($queryParams['direccion']) ? $queryParams['direccion'] : null;
        $descripcion = isset($queryParams['descripcion']) ? $queryParams['descripcion'] : null;
        $hora = isset($queryParams['fecha']) ? $queryParams['fecha'] : null;

        if ($idEvento && $nombreEvento && $lugar && $hora) {
            $invitacionesController->listarInvitacionesPorEvento($idEvento, $nombreEvento, $lugar, $descripcion, $hora);
        } else {
            echo "Datos del evento requeridos.";
        }
        break;


    case '/eventos/invitaciones/crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/../src/Negocio/InvitacionesController.php';
            $invitacionesController = new InvitacionesController($conexion);
            $invitacionesController->agregarInvitacion();
        } else {
            require __DIR__ . '/../src/Presentacion/views/eventos/crear.php';
        }
        break;

    case '/eventos/invitaciones/eliminar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/../src/Negocio/InvitacionesController.php';
            $invitacionesController = new InvitacionesController($conexion);
            $invitacionesController->eliminarInvitacion();
        } else {
            require __DIR__ . '/../src/Presentacion/views/eventos/crear.php';
        }
        break;

    case '/eventos/asistencia':
        // Obtener el id_evento de los parámetros de la URL
        $idEvento = isset($queryParams['id']) ? $queryParams['id'] : null;

        if ($idEvento) {
            // Si se proporciona el id_evento, redirigir al controlador de asistencia
            require __DIR__ . '/../src/Negocio/AsistenciaController.php';
            $asistenciaController = new AsistenciaController($conexion);
            // $asistenciaController->registrarAsistencia($idEvento);
            $asistencias = $asistenciaController->obtenerAsistencias($idEvento);
            require __DIR__ . '/../src/Presentacion/views/Asistencia/registrar.php'; // Incluir la vista

        } else {
            // Si no se proporciona el id_evento, redirigir a otra página o mostrar un mensaje de error
            header("Location: /"); // Por ejemplo, redirige al inicio
        }
        break;

    case '/eventos/asistencia/registrar':
        // Obtener el id_evento de los parámetros de la URL
        // Si se proporciona el id_evento, redirigir al controlador de asistencia
        require __DIR__ . '/../src/Negocio/AsistenciaController.php';
        $asistenciaController = new AsistenciaController($conexion);
        $asistenciaController->registrarAsistencia();
        break;

    // En index.php, agrega una nueva ruta para SSE
    case '/eventos/asistencia/sse':
        $idEvento = isset($queryParams['id']) ? $queryParams['id'] : null;
        if ($idEvento) {
            require __DIR__ . '/../src/Negocio/AsistenciaController.php';
            $asistenciaController = new AsistenciaController($conexion);
            $asistenciaController->emitirEventoSSE($idEvento); // Este método debe ser creado
        } else {
            echo "ID del evento requerido.";
        }
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}

?>