<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './../src/Datos/DInvitacion.php'; // Asegúrate de que la ruta es correcta
require_once './../src/Negocio/NMesa.php'; // Asegúrate de que la ruta es correcta

class NInvitacion
{
    private $dInvitacion;
    private $nMesa;
    public function __construct($conexion)
    {
        $this->dInvitacion = new DInvitacion($conexion);
        $this->nMesa = new NMesa($conexion);
    }

    public function listarInvitacionesPorEvento($idEvento, $nombreEvento, $lugar, $descripcion, $hora)
    {
        $invitaciones = $this->dInvitacion->obtenerInvitacionesPorEvento($idEvento);
        $_SESSION['evento_actual'] = [
            'id' => $_GET['id'],
            'titulo' => $_GET['titulo'],
            'direccion' => $_GET['direccion'],
            'descripcion' => $_GET['descripcion'],
            'fecha' => $_GET['fecha']
        ];

        //added
        if ($invitaciones) {
            $mesas = $this->nMesa->listarMesas($idEvento);
            return ['invitaciones' => $invitaciones, 'mesas' => $mesas];

        }
    }


    public function agregarInvitacion($id_evento, $nombre_invitado, $nro_celular, $mesa_asignada)
    {
        $resultado = $this->dInvitacion->agregarInvitacion($id_evento, $nombre_invitado, $nro_celular, $mesa_asignada);

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
        $resultado = $this->dInvitacion->eliminarInvitacion($id);
        if ($resultado) {
            $_SESSION['invitacion_eliminada'] = true;
            header("Location: /eventos/invitaciones?id={$_SESSION['evento_actual']['id']}&titulo={$_SESSION['evento_actual']['titulo']}&direccion={$_SESSION['evento_actual']['direccion']}&descripcion={$_SESSION['evento_actual']['descripcion']}&fecha={$_SESSION['evento_actual']['fecha']}");
            return true;
        } else {
            echo "Hubo un error al eliminar la invitación";
        }
    }

    public function actualizarInvitacion($idInvitacion, $nombre_invitado, $nro_celular, $mesa_asignada)
    {
        $resultado = $this->dInvitacion->actualizarInvitacion($idInvitacion, $nombre_invitado, $nro_celular, $mesa_asignada);
        // Procesa el resultado de la actualización...
        if ($resultado) {
            $_SESSION['invitacion_actualizada'] = true;
            header("Location: /eventos/invitaciones?id={$_SESSION['evento_actual']['id']}&titulo={$_SESSION['evento_actual']['titulo']}&direccion={$_SESSION['evento_actual']['direccion']}&descripcion={$_SESSION['evento_actual']['descripcion']}&fecha={$_SESSION['evento_actual']['fecha']}");
            return true;
        } else {
            echo "Hubo un error al actualizar el evento";
        }
    }


}
$nInvitacion = new NInvitacion($conexion);
