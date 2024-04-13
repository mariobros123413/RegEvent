<?php

class DInvitacion
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }
    public function obtenerInvitacionesPorEvento($idEvento)
    {
        $sql = "SELECT invitacion.id AS id_invitacion, * FROM invitacion, evento WHERE id_evento = :evento_id AND id_evento= evento.id ORDER BY invitacion.id ASC";
        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(':evento_id', $idEvento, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function agregarInvitacion($idEvento, $nombreInvitado, $nroCelular, $mesa_asignada)
    {
        $sql = "INSERT INTO invitacion (id_evento, nombre_invitado, nro_celular, mesa_asignada) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(1, $idEvento, PDO::PARAM_INT);
        $stmt->bindValue(2, $nombreInvitado, PDO::PARAM_STR);
        $stmt->bindValue(3, $nroCelular, PDO::PARAM_INT);
        $stmt->bindValue(4, $mesa_asignada, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function eliminarInvitacion($id)
    {
        ////////
        $sql = "DELETE FROM invitacion WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function actualizarInvitacion($id_invitacion, $nombre_invitado, $nro_celular, $mesa_asignada)
    {
        $sql = "UPDATE invitacion SET nombre_invitado = ?, nro_celular = ?, mesa_asignada = ? WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bindValue(1, $nombre_invitado);
        $stmt->bindValue(2, $nro_celular);
        $stmt->bindValue(3, $mesa_asignada);
        $stmt->bindValue(4, $id_invitacion, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // El evento se actualizó correctamente
            return true;
        } else {
            // Ocurrió un error al actualizar el evento
            return false;
        }
    }
}
