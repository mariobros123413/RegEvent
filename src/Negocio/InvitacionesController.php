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

    public function listarInvitacionesPorEvento($idEvento)
    {
        $invitaciones = $this->invitacionesModel->obtenerInvitacionesPorEvento($idEvento);
        require_once __DIR__ . '/../Presentacion/views/invitaciones/listar.php';
    }

    public function agregarInvitacion($idEvento, $nombreInvitado, $emailInvitado)
    {
        $resultado = $this->invitacionesModel->agregarInvitacion($idEvento, $nombreInvitado, $emailInvitado);
        if ($resultado) {
            // Redirecciona o muestra un mensaje de éxito
        } else {
            // Maneja el error
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

    public function eliminarInvitacion($id)
    {
        $resultado = $this->invitacionesModel->eliminarInvitacion($id);
        if ($resultado) {
            // Redirecciona o muestra un mensaje de éxito
        } else {
            // Maneja el error
        }
    }


}
$invitacionesController = new InvitacionesController($conexion);
