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
            $titulo = $_POST["titulo"];
            $direccion = $_POST["direccion"];
            $descripcion = $_POST["descripcion"];
            $fecha = $_POST["fecha"];
            $hora = $_POST["hora"];
            $eventosController->crearEvento($titulo, $direccion, $descripcion, $fecha, $hora);
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
            $id_evento = $_POST["id_evento"];
            $titulo = $_POST["titulo"];
            $direccion = $_POST["direccion"];
            $descripcion = $_POST["descripcion"];
            $fecha = $_POST["fecha"];
            $hora = $_POST["hora"];
            $eventosController->editarEvento($id_evento, $titulo, $direccion, $descripcion, $fecha, $hora);
        } else {
            header("Location: /");
        }
        break;

    case '/eventos/eliminar':
        require __DIR__ . '/../src/Negocio/EventosController.php';
        $eventosController = new EventosController($conexion);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_evento = $_POST['id_evento_eliminar'];
            $eventosController->eliminarEvento($id_evento);
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
            $id_evento = $_POST["id_evento"];
            $nombre_invitado = $_POST["nombre"];
            $nro_celular = $_POST["nro_celular"];
            $invitacionesController->agregarInvitacion($id_evento, $nombre_invitado, $nro_celular);
        } else {
            require __DIR__ . '/../src/Presentacion/views/eventos/crear.php';
        }
        break;

    case '/eventos/invitaciones/eliminar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/../src/Negocio/InvitacionesController.php';
            $invitacionesController = new InvitacionesController($conexion);
            $id = $_POST["id_invitacion"];
            $invitacionesController->eliminarInvitacion($id);
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

    case '/eventos/mesas':
        $idEvento = isset($queryParams['id']) ? $queryParams['id'] : null;

        if ($idEvento) {
            require __DIR__ . '/../src/Negocio/NMesas.php';
            $nMesa = new NMesa($conexion);
            $mesas = $nMesa->listarMesas($idEvento);
            require __DIR__ . '/../src/Presentacion/views/mesas/gestionar.php';
        } else {
        }
        break;

    case '/eventos/mesas/crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/../src/Negocio/NMesas.php';
            $nMesa = new NMesa($conexion);
            $idEvento = $_POST["id_evento"];
            $tipo = $_POST["tipo"];
            $cant_sillas = $_POST["cant_sillas"];
            $nMesa->crearMesa($idEvento, $tipo, $cant_sillas);
        } else {
            require __DIR__ . '/../src/Presentacion/views/eventos/crear.php';
        }
        break;

    case '/eventos/mesas/editar':
        require __DIR__ . '/../src/Negocio/NMesas.php';
        $nMesa = new NMesa($conexion);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_mesa = $_POST["id_mesa"];

            $tipo = $_POST["tipos"];
            $nMesa->actualizarMesa($id_mesa, $tipo);
        } else {
            header("Location: /");
        }
        break;


    case '/eventos/mesa/eliminar':
        require __DIR__ . '/../src/Negocio/NMesas.php';
        $nMesa = new NMesa($conexion);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_mesa = $_POST["mesa_id"];
            echo "ID de la mesa a eliminar: " . var_dump($_POST); // Agregar este echo para verificar el ID de la mesa
            $nMesa->eliminarMesa($id_mesa);
        } else {
            require __DIR__ . '/../src/Presentacion/views/eventos/crear.php';
        }
        break;

    case '/eventos/mesas/sillas':
        $mesaId = isset($queryParams['id']) ? $queryParams['id'] : null;
        $disp = isset($queryParams['disp']) ? $queryParams['disp'] : null;
        if ($mesaId) {
            require __DIR__ . '/../src/Negocio/NSilla.php';
            $nSilla = new NSilla($conexion);
            $sillas = $nSilla->listarSillas($mesaId);
            require __DIR__ . '/../src/Presentacion/views/sillas/gestionar.php';
        } else {
        }
        break;

    case '/eventos/mesas/sillas/editar':
        require __DIR__ . '/../src/Negocio/NSilla.php';
        $nSilla = new NSilla($conexion);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idMesa = $_POST["id_mesa"];
            $cant = $_POST["cant"];
            $nSilla->actualizarSillas($idMesa, $cant);
        } else {
            header("Location: /");
        }
        break;
    case '/eventos/asistencia/registrar':
        require __DIR__ . '/../src/Negocio/AsistenciaController.php';
        $asistenciaController = new AsistenciaController($conexion);
        $idInvitacion = $_POST["codigoQR"];
        $idEvento = $_POST["id"];
        $asistenciaController->registrarAsistencia($idInvitacion, $idEvento);
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