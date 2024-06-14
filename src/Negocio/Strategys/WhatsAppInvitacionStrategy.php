<?php
require_once '../src/Negocio/InvitacionesStrategy.php';

class WhatsAppInvitacionStrategy implements InvitacionStrategy
{
    private $urls = [];

    public function enviarInvitacion($invitacionId, $numeroCelular, $titulo, $direccion, $descripcion, $fecha, $nombre_invitado, $mesa_asignada)
    {
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($invitacionId);

        $mensaje = "¡Hola {$nombre_invitado}, estás invitado a mi evento!\n" .
            "Título: {$titulo}\n" .
            "Descripción: {$descripcion}\n" .
            "Dirección: {$direccion}\n" .
            "Nro de mesa asignada: {$mesa_asignada}\n" .
            "Fecha: {$fecha}\n" .
            "Código QR para el ingreso:\n" .
            "{$qrCodeUrl}";
        $enlace = "https://wa.me/+591{$numeroCelular}?text=" . urlencode($mensaje);
        $this->urls[] = $enlace;
        echo  $enlace;

    }
}
?>