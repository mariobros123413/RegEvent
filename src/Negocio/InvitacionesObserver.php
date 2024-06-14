<?php
// src/Negocio/InvitacionesObserver.php
require_once './../src/Negocio/IObserver.php';
require_once __DIR__ . '/../Negocio/NInvitacion.php';

class InvitacionesObserver implements IObserver
{
    private $nInvitacion;
    private $urls = [];

    public function __construct($conexion)
    {
        $this->nInvitacion = new NInvitacion($conexion);
    }

    public function update($id_evento, $titulo, $direccion, $descripcion, $fechaHora)
    {
        $invitaciones = $this->nInvitacion->listarInvitacionesPorEventoObservers($id_evento);

        foreach ($invitaciones as $invitacion) {
            // Crear mensaje de WhatsApp
            $mensaje = "Hola {$invitacion['nombre_invitado']}, el evento {$titulo} ha sido actualizado. Nueva información: {$direccion}, {$direccion}, el {$fechaHora}.";
            $url = "https://wa.me/{$invitacion['nro_celular']}?text=" . urlencode($mensaje);
            $this->urls[] = $url;
        }
        
    }
    public function getUrls()
    {
        return $this->urls;
    }
}
?>