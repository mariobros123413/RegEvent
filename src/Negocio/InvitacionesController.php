<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './../src/Datos/InvitacionesDAO.php'; // Asegúrate de que la ruta es correcta

class InvitacionesController
{
    private $invitacionesModel;

    public function __construct($conexion)
    {
        $this->invitacionesModel = new InvitacionesDAO($conexion);
    }

    public function listarInvitacionesPorEvento($idEvento, $nombreEvento, $lugar, $descripcion, $hora)
    {
        // Aquí puedes utilizar $nombreEvento, $lugar y $hora para mostrar información adicional en la vista
        $invitaciones = $this->invitacionesModel->obtenerInvitacionesPorEvento($idEvento);
        $_SESSION['evento_actual'] = [
            'id' => $_GET['id'],
            'titulo' => $_GET['titulo'],
            'direccion' => $_GET['direccion'],
            'descripcion' => $_GET['descripcion'],
            'fecha' => $_GET['fecha']
        ];
        require_once __DIR__ . '/../Presentacion/views/invitaciones/listar.php';
    }


    public function agregarInvitacion()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obtener los datos del formulario
            $id_evento = $_POST["id_evento"]; // Asegúrate de obtener el ID del evento
            $nombre_invitado = $_POST["nombre"];
            $nro_celular = $_POST["nro_celular"];
            $id_usuario_organizador = 1;


            // Llamar al método de la capa de negocio para crear el evento
            $resultado = $this->invitacionesModel->agregarInvitacion($id_evento, $nombre_invitado, $nro_celular, $id_usuario_organizador);//id_usuario_organizador

            // Redireccionar a una página diferente después de procesar el formulario
            if ($resultado) {
                $_SESSION['invitacion_creada'] = true;
                // Redirigir al usuario a la página de invitaciones con la información del evento almacenada en la sesión
                header("Location: /eventos/invitaciones?id={$_SESSION['evento_actual']['id']}&titulo={$_SESSION['evento_actual']['titulo']}&direccion={$_SESSION['evento_actual']['direccion']}&descripcion={$_SESSION['evento_actual']['descripcion']}&fecha={$_SESSION['evento_actual']['fecha']}");
                return;
            } else {
                // Ocurrió un error al crear el evento
                echo "Hubo un error al crear la invitaicón";
            }

        }
    }

    public function actualizarInvitacion($id, $nombreInvitado, $emailInvitado)
    {
        $resultado = $this->invitacionesModel->actualizarInvitacion($id, $nombreInvitado, $emailInvitado);
        if ($resultado) {
            // Redirecciona o muestra un mensaje de éxito
        } else {
            // Maneja el error
        }
    }

    public function eliminarInvitacion()
    {
        $id = $_POST["id_invitacion"]; // Asegúrate de obtener el ID del evento
        echo "idEvento: $id";
        $resultado = $this->invitacionesModel->eliminarInvitacion($id);
        if ($resultado) {
            $_SESSION['invitacion_eliminada'] = true;
            // Redirigir al usuario a la página de invitaciones con la información del evento almacenada en la sesión
            header("Location: /eventos/invitaciones?id={$_SESSION['evento_actual']['id']}&titulo={$_SESSION['evento_actual']['titulo']}&direccion={$_SESSION['evento_actual']['direccion']}&descripcion={$_SESSION['evento_actual']['descripcion']}&fecha={$_SESSION['evento_actual']['fecha']}");
        } else {
            echo "Hubo un error al eliminar la invitación";
        }
    }


}
$invitacionesController = new InvitacionesController($conexion);
