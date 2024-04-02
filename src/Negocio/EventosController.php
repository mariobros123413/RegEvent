<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();



require_once './../src/Datos/EventosDAO.php';

class EventosController
{
    private $eventosService;

    public function __construct($conexion)
    {
        $this->eventosService = new EventosDAO($conexion);
    }

    public function crearEvento()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obtener los datos del formulario
            $titulo = $_POST["titulo"];
            $direccion = $_POST["direccion"];
            $descripcion = $_POST["descripcion"];
            $fecha = $_POST["fecha"];
            $hora = $_POST["hora"];
            // Suponiendo que "id_usuario_organizador" proviene de algún lugar, ya sea de la sesión, una variable global, etc.
            $id_usuario_organizador = 1;

            // Llamar al método de la capa de negocio para crear el evento
            $resultado = $this->eventosService->crearEvento($id_usuario_organizador, $titulo, $direccion, $descripcion, $fecha . ' ' . $hora);

            // Redireccionar a una página diferente después de procesar el formulario
            if ($resultado) {
                $_SESSION['evento_creado'] = true;
                header("Location: /eventos/crear"); // Redirigir a la página de crear evento
                return;
            } else {
                // Ocurrió un error al crear el evento
                echo "Hubo un error al crear el evento";
            }

        }
    }

    public function listarEventos()
    {
        // Obtener todos los eventos
        $eventos = $this->eventosService->obtenerTodosEventos();
        if ($eventos) {
            // Si se obtienen eventos correctamente, incluir la vista para mostrar los eventos
            return $eventos;
        } else {
            // Si no se obtienen eventos, puedes mostrar un mensaje de error o redirigir a otra página
            echo "No se han encontrado eventos.";
        }
    }

    public function editarEvento()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obtener los datos del formulario
            $id_evento = $_POST["id_evento"]; // Asegúrate de obtener el ID del evento
            $titulo = $_POST["titulo"];
            $direccion = $_POST["direccion"];
            $descripcion = $_POST["descripcion"];
            $fecha = $_POST["fecha"];
            $hora = $_POST["hora"];

            // Combina fecha y hora
            $fechaHora = $fecha . ' ' . $hora;

            // Llamar al método de la capa de negocio para actualizar el evento
            $resultado = $this->eventosService->actualizarEvento($id_evento, $titulo, $direccion, $descripcion, $fechaHora);

            // Procesa el resultado de la actualización...
            if ($resultado) {
                $_SESSION['evento_actualizado'] = true;
                header("Location: /eventos/listar"); // Redirigir a la página de listar eventos
            } else {
                echo "Hubo un error al actualizar el evento";
            }
        }
    }
    public function eliminarEvento()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Verificar si se proporcionó un ID de evento en la solicitud GET
            if (isset($_POST['id_evento_eliminar'])) {
                $id_evento = $_POST['id_evento_eliminar'];

                // Llamar al método de la capa de datos para eliminar el evento
                $resultado = $this->eventosService->eliminarEvento($id_evento);

                // Procesar el resultado de la eliminación...
                if ($resultado) {
                    $_SESSION['evento_eliminado'] = true;
                    header("Location: /eventos/listar"); // Redirigir a la página de listar eventos
                } else {
                    echo "Hubo un error al eliminar el evento";
                }
            } else {
                // Si no se proporcionó un ID de evento, puedes redirigir al usuario o mostrar un mensaje de error
                echo "ID de evento no proporcionado";
            }
        }
    }


}

// Obtener la conexión a la base de datos

// Inicializar el controlador de eventos
$eventosController = new EventosController($conexion);
?>