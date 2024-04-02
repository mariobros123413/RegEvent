<?php

class InvitacionesDAO
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Método para obtener todas las invitaciones de un evento específico
    public function obtenerInvitacionesPorEvento($idEvento)
    {
        $sql = "SELECT * FROM invitacion WHERE id_evento = :evento_id";
        $stmt = $this->conexion->prepare($sql);

        // Usar bindValue para vincular el parámetro
        $stmt->bindValue(':evento_id', $idEvento, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Manejar el error, por ejemplo, lanzando una excepción o retornando false
            return false;
        }
    }


    // Método para añadir una nueva invitación
    public function agregarInvitacion($idEvento, $nombreInvitado, $emailInvitado)
    {
        $sql = "INSERT INTO invitacion (id_evento, nombre_invitado, email_invitado) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("iss", $idEvento, $nombreInvitado, $emailInvitado);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    // Método para actualizar una invitación
    public function actualizarInvitacion($id, $nombreInvitado, $emailInvitado)
    {
        $sql = "UPDATE invitacion SET nombre_invitado = ?, email_invitado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssi", $nombreInvitado, $emailInvitado, $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    // Método para eliminar una invitación
    public function eliminarInvitacion($id)
    {
        $sql = "DELETE FROM invitacion WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }
}
