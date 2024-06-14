<?php
require_once '../src/Datos/Database.php';
require_once '../src/Negocio/NEvento.php';
require_once '../src/Negocio/NInvitacion.php';
require_once '../src/Datos/DInvitacion.php';
require_once '../src/Negocio/InvitacionesObserver.php';
require_once '../src/Negocio/NMesa.php';

$ruta = $_SERVER['REQUEST_URI'];
$database = Database::getInstance();
$conexion = $database->getConnection();
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

parse_str($queryString, $queryParams);
// Crear instancia de NEvento
$nEvento = new NEvento($conexion);

// Crear instancia del observador y registrarlo
$invitacionesObserver = new InvitacionesObserver($conexion);
$nEvento->registerObserver($invitacionesObserver);

switch ($url) {

    case '/':
        require __DIR__ . '/../src/Presentacion/views/home.php';
        break;

    case '/eventos/crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST["titulo"];
            $direccion = $_POST["direccion"];
            $descripcion = $_POST["descripcion"];
            $fecha = $_POST["fecha"];
            $hora = $_POST["hora"];
            $nEvento->crearEvento($titulo, $direccion, $descripcion, $fecha, $hora);
        } else {
            require __DIR__ . '/../src/Presentacion/views/eventos/crear.php';
        }
        break;

    case '/eventos/listar':
        $eventos = $nEvento->listarEventos();
        require __DIR__ . '/../src/Presentacion/views/eventos/listar.php';
        break;

    case '/eventos/editar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_evento = $_POST["id_evento"];
            $titulo = $_POST["titulo"];
            $direccion = $_POST["direccion"];
            $descripcion = $_POST["descripcion"];
            $fecha = $_POST["fecha"];
            $hora = $_POST["hora"];
            $resultado = $nEvento->editarEvento($id_evento, $titulo, $direccion, $descripcion, $fecha, $hora);

            if ($resultado) {
                // Obtener las URLs generadas por el observador
                $urls = $invitacionesObserver->getUrls();
                if (!empty($urls)) {
                    // Almacenar las URLs en una variable de JavaScript
                    echo "<script>";
                    echo "var urls = " . json_encode($urls) . ";";
                    echo "urls.forEach(function(url) {";
                    echo "console.log('URL: ' + url);";  // Imprimir en la consola del navegador
                    echo "window.open(url, '_blank');";  // Abrir las URLs en nuevas pestañas
                    echo "});";
                    echo "</script>";
                }
                // Dar tiempo a las URLs para abrirse antes de redirigir
                echo "<script>setTimeout(function() { window.location.href = '/eventos/listar'; }, 2000);</script>";
            } else {
                header("Location: /");
            }
        }
        break;

    case '/eventos/eliminar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_evento = $_POST['id_evento_eliminar'];
            $nEvento->eliminarEvento($id_evento);
        } else {
            header("Location: /");
        }
        break;

    case '/eventos/invitaciones':
        $nInvitacion = new NInvitacion($conexion);
        $idEvento = isset($queryParams['id']) ? $queryParams['id'] : null;
        $nombreEvento = isset($queryParams['titulo']) ? $queryParams['titulo'] : null;
        $lugar = isset($queryParams['direccion']) ? $queryParams['direccion'] : null;
        $descripcion = isset($queryParams['descripcion']) ? $queryParams['descripcion'] : null;
        $hora = isset($queryParams['fecha']) ? $queryParams['fecha'] : null;

        if ($idEvento && $nombreEvento && $lugar && $hora) {
            $datos = $nInvitacion->listarInvitacionesPorEvento($idEvento);
            // Define $datos en un ámbito que la vista pueda acceder
            $GLOBALS['datos'] = $datos;
            require_once __DIR__ . '/../src/Presentacion/views/invitaciones/listar.php';
        } else {
            echo "Datos del evento requeridos.";
        }
        break;


    case '/eventos/invitaciones/crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/../src/Negocio/NInvitacion.php';
            $nInvitacion = new NInvitacion($conexion);
            $id_evento = $_POST["id_evento"];
            $nombre_invitado = $_POST["nombre"];
            $nro_celular = $_POST["nro_celular"];
            $mesa_asignada = $_POST["mesa_asignada"];  // Captura el valor de la mesa seleccionada
            $nInvitacion->agregarInvitacion($id_evento, $nombre_invitado, $nro_celular, $mesa_asignada);
        } else {
            require __DIR__ . '/../src/Presentacion/views/eventos/crear.php';
        }
        break;

    case '/eventos/invitaciones/editar':
        require __DIR__ . '/../src/Negocio/NInvitacion.php';
        $nInvitacion = new NInvitacion($conexion);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idInvitacion = $_POST["idInvitacion"];
            $nombre_invitado = $_POST["nombre_invitado"];
            $nro_celular = $_POST["nrocelular"];
            $mesa_asignada = $_POST["mesa_asignada"];
            $nInvitacion->actualizarInvitacion($idInvitacion, $nombre_invitado, $nro_celular, $mesa_asignada);
        } else {
            header("Location: /");
        }
        break;

    case '/eventos/invitaciones/eliminar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/../src/Negocio/NInvitacion.php';
            $nInvitacion = new NInvitacion($conexion);
            $id = $_POST["id_invitacion"];
            $nInvitacion->eliminarInvitacion($id);
        } else {
            require __DIR__ . '/../src/Presentacion/views/eventos/crear.php';
        }
        break;

    case '/eventos/asistencia':
        $idEvento = isset($queryParams['id']) ? $queryParams['id'] : null;

        if ($idEvento) {
            require __DIR__ . '/../src/Negocio/NAsistencia.php';
            $nAsistencia = new NAsistencia($conexion);
            $asistencias = $nAsistencia->obtenerAsistencias($idEvento);
            require __DIR__ . '/../src/Presentacion/views/Asistencia/registrar.php';
        } else {
            header("Location: /");
        }
        break;

    case '/eventos/mesas':
        $idEvento = isset($queryParams['id']) ? $queryParams['id'] : null;

        if ($idEvento) {
            $nMesa = new NMesa($conexion);
            $mesas = $nMesa->listarMesas($idEvento);
            require __DIR__ . '/../src/Presentacion/views/mesas/gestionar.php';
        } else {
        }
        break;

    case '/eventos/mesas/crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        require __DIR__ . '/../src/Negocio/NAsistencia.php';
        $nAsistencia = new NAsistencia($conexion);
        $idInvitacion = $_POST["codigoQR"];
        $idEvento = $_POST["id"];
        $nAsistencia->registrarAsistencia($idInvitacion, $idEvento);
        break;

    case '/eventos/asistencia/sse':
        $idEvento = isset($queryParams['id']) ? $queryParams['id'] : null;
        if ($idEvento) {
            require __DIR__ . '/../src/Negocio/NAsistencia.php';
            $nAsistencia = new NAsistencia($conexion);
            $nAsistencia->emitirEventoSSE($idEvento);
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