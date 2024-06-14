<?php
require_once '../src/Negocio/InvitacionesStrategy.php';

class FacebookInvitacionStrategy implements InvitacionStrategy
{
    public function enviarInvitacion($invitacionId, $numeroCelular, $titulo, $direccion, $descripcion, $fecha, $nombre_invitado, $mesa_asignada)
    {
        $nombre_invitado_sin_espacios = str_replace(' ', '', $nombre_invitado);

        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($invitacionId);

        $mensaje = "¡Hola {$nombre_invitado}! Estás invitado a mi evento.\n" .
            "Título: {$titulo}\n" .
            "Descripción: {$descripcion}\n" .
            "Dirección: {$direccion}\n" .
            "Nro de mesa asignada: {$mesa_asignada}\n" .
            "Fecha: {$fecha}\n" .
            "Código QR para el ingreso:\n" .
            "{$qrCodeUrl}";
        $url = "https://www.messenger.com/t/{$nombre_invitado_sin_espacios}?text=" . urlencode($mensaje);
        echo $url;

    }
}
?>