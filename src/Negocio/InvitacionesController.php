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
        $invitaciones = $this->invitacionesModel->obtenerInvitacionesPorEvento($idEvento);
        $_SESSION['evento_actual'] = [
            'id' => $_GET['id'],
            'titulo' => $_GET['titulo'],
            'direccion' => $_GET['direccion'],
            'descripcion' => $_GET['descripcion'],
            'fecha' => $_GET['fecha']
        ];
        require_once __DIR__ . '/../Presentacion/views/invitaciones/listar.php';
        //added
        if ($invitaciones) 
            return $invitaciones;
    }


    public function agregarInvitacion($id_evento, $nombre_invitado, $nro_celular)
    {

        // Llamar al método de la capa de negocio para crear el evento
        $resultado = $this->invitacionesModel->agregarInvitacion($id_evento, $nombre_invitado, $nro_celular);//agregar id_mesa

        if ($resultado) {
            $_SESSION['invitacion_creada'] = true;
            header("Location: /eventos/invitaciones?id={$_SESSION['evento_actual']['id']}&titulo={$_SESSION['evento_actual']['titulo']}&direccion={$_SESSION['evento_actual']['direccion']}&descripcion={$_SESSION['evento_actual']['descripcion']}&fecha={$_SESSION['evento_actual']['fecha']}");
            return true;
        } else {
            echo "Hubo un error al crear la invitaicón";
        }
    }

    public function eliminarInvitacion($id) //aumentar idMesa y aumentar silla a la mesa
    {
        $resultado = $this->invitacionesModel->eliminarInvitacion($id);
        if ($resultado) {
            $_SESSION['invitacion_eliminada'] = true;
            header("Location: /eventos/invitaciones?id={$_SESSION['evento_actual']['id']}&titulo={$_SESSION['evento_actual']['titulo']}&direccion={$_SESSION['evento_actual']['direccion']}&descripcion={$_SESSION['evento_actual']['descripcion']}&fecha={$_SESSION['evento_actual']['fecha']}");
            return true;
        } else {
            echo "Hubo un error al eliminar la invitación";
        }
    }


}
$invitacionesController = new InvitacionesController($conexion);
