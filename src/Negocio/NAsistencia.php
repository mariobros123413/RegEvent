<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './../src/Datos/DAsistencia.php';

class NAsistencia
{
    private $dAsistencia;

    public function __construct($conexion)
    {
        $this->dAsistencia = new DAsistencia($conexion);
    }

    public function registrarAsistencia($idInvitacion, $idEvento)
    {
        if ($this->verificarEventoInvitacion($idInvitacion, $idEvento)) {
            if ($this->verificarAsistenciaExistente($idInvitacion)) {
                return false;
            }
            $this->dAsistencia->registrarAsistencia($idInvitacion);
            echo json_encode(['success' => true, 'message' => 'Asistencia registrada con éxito.']);
            
            exit;
        }

    }

    public function obtenerAsistencias($idEvento)
    {
        if ($idEvento) {
            $asistencias = $this->dAsistencia->obtenerAsistencias($idEvento);
            return $asistencias;
        } else {
            echo "No se han encontrado eventos.";
        }
    }

    public function verificarEventoInvitacion($idInvitacion, $idEvento)
    {
        $resultado = $this->dAsistencia->verificarEventoInvitacion($idInvitacion, $idEvento);
        if ($resultado['total'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function emitirEventoSSE($idEvento)
    {
        // Encabezado para eventos SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        // Construir el mensaje SSE
        echo "event: asistencia\n";
        echo "data: " . json_encode($this->obtenerAsistencias($idEvento)) . "\n\n";
        flush(); // Forzar el envío del evento
    }

    public function verificarAsistenciaExistente($idInvitacion)
    {
        $resultado = $this->dAsistencia->cantAsistencias($idInvitacion);
        if ($resultado['total'] > 0) {
            return true;
        } else {
            return false;
        }
    }
}
?>