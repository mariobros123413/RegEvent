<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();



require_once './../src/Datos/DEvento.php';
require_once './../src/Negocio/IObserver.php'; // Importar la interfaz

class NEvento
{
    private $dEvento;
    private $observers = [];

    public function __construct($conexion)
    {
        $this->dEvento = new DEvento($conexion);
    }

    public function crearEvento($titulo, $direccion, $descripcion, $fecha, $hora)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $resultado = $this->dEvento->crearEvento($titulo, $direccion, $descripcion, $fecha . ' ' . $hora);

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
        $eventos = $this->dEvento->obtenerTodosEventos();
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
        $resultado = $this->dEvento->actualizarEvento($id_evento, $titulo, $direccion, $descripcion, $fechaHora);
        // Procesa el resultado de la actualización...
        if ($resultado) {
            $this->notifyObservers($id_evento, $titulo, $direccion, $descripcion,$fechaHora);
            $_SESSION['evento_actualizado'] = true;
            return true;
        } else {
            echo "Hubo un error al actualizar el evento";
        }

    }
    public function eliminarEvento($id_evento)
    {
        if (isset($_POST['id_evento_eliminar'])) {

            $resultado = $this->dEvento->eliminarEvento($id_evento);
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

    // Métodos del patrón Observer
    public function registerObserver(IObserver $observer)
    {
        $this->observers[] = $observer;
    }

    public function removeObserver(IObserver $observer)
    {
        $key = array_search($observer, $this->observers, true);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    public function notifyObservers($id_evento, $titulo, $direccion, $descripcion, $fechaHora)
    {
        foreach ($this->observers as $observer) {
            $observer->update($id_evento, $titulo, $direccion, $descripcion, $fechaHora);
        }
    }

}
?>