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
        $sql = "SELECT invitacion.id AS id_invitacion, * FROM invitacion, evento WHERE id_evento = :evento_id AND id_evento= evento.id ORDER BY invitacion.id ASC";
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
    public function agregarInvitacion($idEvento, $nombreInvitado, $nroCelular, $id_usuario_organizador)
    {
        // Imprime los valores de los parámetros para verificarlos

        $sql = "INSERT INTO invitacion (id_usuario, id_evento, nombre_invitado, nro_celular) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        // Especifica el tipo de dato para cada parámetro
        $stmt->bindValue(1, $id_usuario_organizador, PDO::PARAM_INT); // id_usuario es un entero
        $stmt->bindValue(2, $idEvento, PDO::PARAM_INT); // id_evento es un entero
        $stmt->bindValue(3, $nombreInvitado, PDO::PARAM_STR); // nombre_invitado es una cadena de caracteres
        $stmt->bindValue(4, $nroCelular, PDO::PARAM_STR); // nro_celular es una cadena de caracteres

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
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
        if (!$stmt) {
            return false;
        }
        $stmt->bindValue(1, $id, PDO::PARAM_INT); // id_usuario es un entero
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
