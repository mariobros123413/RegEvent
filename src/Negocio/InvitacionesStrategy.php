<?php
interface InvitacionStrategy {
    public function enviarInvitacion($invitacionId, $numeroCelular, $titulo, $direccion, $descripcion, $fecha, $nombre_invitado, $mesa_asignada);
}
?>
