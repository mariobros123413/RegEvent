<?php

class AsistenciaDAO
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerAsistencias($idEvento)
    {
        $sql = "SELECT invitacion.id AS idInvitacion,* FROM asistencia, invitacion  WHERE invitacion.id_evento = :evento_id AND id_invitacion = invitacion.id ORDER BY asistencia.id ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':evento_id', $idEvento, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function registrarAsistencia($idInvitacion)
    {
        // Obtener la fecha y hora actual
        $fechaHoraActual = date("Y-m-d H:i:s");

        // Restar 5 horas a la fecha y hora actual
        $fechaHora = date("Y-m-d H:i:s", strtotime($fechaHoraActual . " -6 hours"));

        $sql = "INSERT INTO asistencia (fecha_llegada, id_invitacion) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bindValue(1, $fechaHora, PDO::PARAM_STR);
        $stmt->bindValue(2, $idInvitacion, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function cantAsistencias($idInvitacion)
    {
        try {
            $sql = "SELECT COUNT(*) AS total FROM asistencia WHERE id_invitacion = :idInvitacion";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':idInvitacion', $idInvitacion, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al verificar la asistencia existente: " . $e->getMessage();
            return null;
        }
    }

    public function verificarEventoASistencia($idInvitacion, $idEvento)
    {
        try {
            $sql = "SELECT COUNT(*) AS total FROM invitacion, evento WHERE evento.id = :idEvento AND invitacion.id= :idInvitacion AND invitacion.id_evento = evento.id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':idInvitacion', $idInvitacion, PDO::PARAM_INT);
            $stmt->bindParam(':idEvento', $idEvento, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al verificar la asistencia existente: " . $e->getMessage();
            return null;
        }
    }
}
?>