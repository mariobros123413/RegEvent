<?php

class InvitacionesDAO
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

    public function agregarInvitacion($idEvento, $nombreInvitado, $nroCelular)
    {
        $sql = "INSERT INTO invitacion (id_evento, nombre_invitado, nro_celular) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(1, $idEvento, PDO::PARAM_INT);
        $stmt->bindValue(2, $nombreInvitado, PDO::PARAM_STR);
        $stmt->bindValue(3, $nroCelular, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function actualizarInvitacion($id, $nombreInvitado, $emailInvitado)
    {
        $sql = "UPDATE invitacion SET nombre_invitado = ?, email_invitado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssi", $nombreInvitado, $emailInvitado, $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
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
}
