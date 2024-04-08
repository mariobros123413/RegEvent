<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './../src/Datos/AsistenciaDAO.php';

class AsistenciaController
{
    private $asistenciaModel;

    public function __construct($conexion)
    {
        $this->asistenciaModel = new AsistenciaDAO($conexion);
    }

    public function registrarAsistencia()
    {
        $idInvitacion = $_POST["codigoQR"];
        $idEvento = $_POST["id"];
        if ($this->verificarEventoInvitacion($idInvitacion, $idEvento)) {
            if ($this->verificarAsistenciaExistente($idInvitacion)) {
                return false;
            }
            $this->asistenciaModel->registrarAsistencia($idInvitacion);
            echo json_encode(['success' => true, 'message' => 'Asistencia registrada con éxito.']);
            exit;
        }

    }

    public function obtenerAsistencias($idEvento)
    {
        if ($idEvento) {
            $asistencias = $this->asistenciaModel->obtenerAsistencias($idEvento);
            return $asistencias;
        } else {
            echo "No se han encontrado eventos.";
        }
    }

    public function verificarEventoInvitacion($idInvitacion, $idEvento)
    {
        $resultado = $this->asistenciaModel->verificarEventoASistencia($idInvitacion, $idEvento);
        if ($resultado['total'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function emitirEventoSSE($asistencia)
    {
        // Encabezado para eventos SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        // Construir el mensaje SSE
        echo "event: asistencia\n";
        echo "data: " . json_encode($this->obtenerAsistencias($asistencia)) . "\n\n";
        flush(); // Forzar el envío del evento
    }

    public function verificarAsistenciaExistente($idInvitacion)
    {
        $resultado = $this->asistenciaModel->cantAsistencias($idInvitacion);
        if ($resultado['total'] > 0) {
            return true;
        } else {
            return false;
        }
    }
}
?>