<?php
// AsistenciaDAO.php

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
    public function registrarAsistencia($idInvitacion, $idEvento)
    {
        // Obtener la fecha y hora actual
        $fechaHora = date("Y-m-d H:i:s");

        // Preparar la consulta SQL para insertar la asistencia en la base de datos
        $sql = "INSERT INTO asistencia (fecha_llegada, id_invitacion, id_evento) VALUES (?, ?,?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bindValue(1, $fechaHora, PDO::PARAM_STR);
        $stmt->bindValue(2, $idInvitacion, PDO::PARAM_INT);
        $stmt->bindValue(3, $idEvento, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function verificarAsistenciaExistente($idInvitacion)
    {
        try {
            // Preparar la consulta SQL para verificar si la invitación ya existe en la tabla de asistencia
            $sql = "SELECT COUNT(*) AS total FROM asistencia WHERE id_invitacion = :idInvitacion";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':idInvitacion', $idInvitacion, PDO::PARAM_INT);
            $stmt->execute();

            // Obtener el resultado de la consulta
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verificar si la asistencia ya existe
            if ($resultado['total'] > 0) {
                return true; // La asistencia ya existe
            } else {
                return false; // La asistencia no existe
            }
        } catch (PDOException $e) {
            // Manejar el error en caso de que ocurra una excepción
            echo "Error al verificar la asistencia existente: " . $e->getMessage();
            return false; // Devolver false en caso de error
        }
    }
}
?>