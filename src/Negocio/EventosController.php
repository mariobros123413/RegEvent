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
    public function obtenerEventoPorId($id_evento)
    {
        // Llama al método de la capa de datos para obtener el evento por su ID
        $evento = $this->eventosService->obtenerEventoPorId($id_evento);
        if ($evento) {
            // Si se obtienen eventos correctamente, incluir la vista para mostrar los eventos
            return $evento;
        } else {
            // Si no se obtienen eventos, puedes mostrar un mensaje de error o redirigir a otra página
            echo "No se han encontrado ningún evento.";
        }
    }
}

// Obtener la conexión a la base de datos

// Inicializar el controlador de eventos
$eventosController = new EventosController($conexion);
$eventosController->crearEvento();
$eventosController->listarEventos();

?>