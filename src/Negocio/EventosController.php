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

    public function crearEvento($titulo, $direccion, $descripcion, $fecha, $hora)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $resultado = $this->eventosService->crearEvento($titulo, $direccion, $descripcion, $fecha . ' ' . $hora);

            if ($resultado) {
                $_SESSION['evento_creado'] = true;
                header("Location: /eventos/crear");
                return true;
            } else {
                echo "Hubo un error al crear el evento";
            }

        }
    }

    public function listarEventos()
    {
        $eventos = $this->eventosService->obtenerTodosEventos();
        if ($eventos) {
            return $eventos;
        } else {
            echo "No se han encontrado eventos.";
        }
    }

    public function editarEvento($id_evento, $titulo, $direccion, $descripcion, $fecha, $hora)
    {
        $fechaHora = $fecha . ' ' . $hora;

        // Llamar al método de la capa de negocio para actualizar el evento
        $resultado = $this->eventosService->actualizarEvento($id_evento, $titulo, $direccion, $descripcion, $fechaHora);

        // Procesa el resultado de la actualización...
        if ($resultado) {
            $_SESSION['evento_actualizado'] = true;
            header("Location: /eventos/listar"); // Redirigir a la página de listar eventos
            return true;
        } else {
            echo "Hubo un error al actualizar el evento";
        }

    }
    public function eliminarEvento($id_evento)
    {
        if (isset($_POST['id_evento_eliminar'])) {

            $resultado = $this->eventosService->eliminarEvento($id_evento);
            if ($resultado) {
                $_SESSION['evento_eliminado'] = true;
                header("Location: /eventos/listar"); // Redirigir a la página de listar eventos
                return true;
            } else {
                echo "Hubo un error al eliminar el evento";
            }
        } else {
            echo "ID de evento no proporcionado";
        }
    }



}

// Obtener la conexión a la base de datos

// Inicializar el controlador de eventos
$eventosController = new EventosController($conexion);
?>