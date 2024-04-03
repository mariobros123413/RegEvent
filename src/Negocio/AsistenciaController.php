<?php
// AsistenciaController.php

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

    public function registrarAsistencia($idInvitacion)
    {
        // Lógica para registrar la asistencia
        // Aquí podrías insertar el registro de asistencia en la base de datos utilizando el ID de la invitación

        // Ejemplo de cómo podrías llamar al método correspondiente en la capa de datos
        if ($this->asistenciaModel->verificarAsistenciaExistente($idInvitacion)) {
            // Si la invitación ya existe, emitir un mensaje o realizar alguna acción apropiada
            echo "La asistencia ya ha sido registrada previamente.";
            return false;
        }
        $resultado = $this->asistenciaModel->registrarAsistencia($idInvitacion);

        // Verificar si la asistencia se registró correctamente
        if ($resultado) {
            // Aquí podrías realizar cualquier otra acción necesaria después de registrar la asistencia, como actualizar la vista o redirigir al usuario
            return true;
        } else {
            // Manejar el caso en que no se pueda registrar la asistencia
            return false;
        }
    }

    public function obtenerAsistencias($idEvento)
    {
        if ($idEvento) {
            // Si se obtienen eventos correctamente, incluir la vista para mostrar los eventos
            $asistencias = $this->asistenciaModel->obtenerAsistencias($idEvento);
            return $asistencias;
        } else {
            // Si no se obtienen eventos, puedes mostrar un mensaje de error o redirigir a otra página
            echo "No se han encontrado eventos.";
        }
    }
}
?>