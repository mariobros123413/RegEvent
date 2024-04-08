<?php
require_once '../src/Datos/Database.php';

$ruta = $_SERVER['REQUEST_URI'];
$database = Database::getInstance();
$conexion = $database->getConnection();
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

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
        $eventos = $eventosController->listarEventos();
        require __DIR__ . '/../src/Presentacion/views/eventos/listar.php';
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
        $idEvento = isset($queryParams['id']) ? $queryParams['id'] : null;

        if ($idEvento) {
            require __DIR__ . '/../src/Negocio/AsistenciaController.php';
            $asistenciaController = new AsistenciaController($conexion);
            $asistencias = $asistenciaController->obtenerAsistencias($idEvento);
            require __DIR__ . '/../src/Presentacion/views/Asistencia/registrar.php';
        } else {
            header("Location: /");
        }
        break;

    case '/eventos/asistencia/registrar':
        require __DIR__ . '/../src/Negocio/AsistenciaController.php';
        $asistenciaController = new AsistenciaController($conexion);
        $asistenciaController->registrarAsistencia();
        break;

    case '/eventos/asistencia/sse':
        $idEvento = isset($queryParams['id']) ? $queryParams['id'] : null;
        if ($idEvento) {
            require __DIR__ . '/../src/Negocio/AsistenciaController.php';
            $asistenciaController = new AsistenciaController($conexion);
            $asistenciaController->emitirEventoSSE($idEvento);
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