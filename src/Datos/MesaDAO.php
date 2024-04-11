<?php

class MesaDAO
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function crearMesa($id_evento, $tipo, $cant_sillas)
    {
        $this->conexion->beginTransaction();
        try {
            $sql = "INSERT INTO mesa (id_evento, tipo, capacidad) VALUES (?, ?, ?)";

            $stmt = $this->conexion->prepare($sql);

            $stmt->bindParam(1, $id_evento, PDO::PARAM_INT);
            $stmt->bindParam(2, $tipo, PDO::PARAM_STR);
            $stmt->bindParam(3, $cant_sillas, PDO::PARAM_INT);

            $stmt->execute();
            $mesaId = $this->conexion->lastInsertId();

            $this->conexion->commit();

            return $mesaId;
        } catch (PDOException $e) {
            $this->conexion->rollback();
            echo "Error al crearMesa: " . $e->getMessage();

            return false;
        }

    }
    public function listarMesas($id_evento)
    {
        // La consulta SQL para PostgreSQL, usando COALESCE en lugar de IFNULL.
        $sql = "SELECT m.*, (m.capacidad - COALESCE(i.total_invitaciones, 0) ) AS sillas_disponibles
            FROM mesa m
            LEFT JOIN (
                SELECT mesa_asignada, COUNT(*) AS total_invitaciones
                FROM invitacion
                GROUP BY mesa_asignada
            ) i ON m.id = i.mesa_asignada
            WHERE m.id_evento = :id_evento ORDER BY id ASC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_evento', $id_evento, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function eliminarMesa($id_mesa)
    {
        $sql = "DELETE FROM mesa WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bindValue(1, $id_mesa, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function actualizarMesa($id_mesa, $tipo)
    {
        $sql = "UPDATE mesa SET tipo = ? WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bindValue(1, $tipo);
        $stmt->bindValue(2, $id_mesa, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // El evento se actualizó correctamente
            return true;
        } else {
            // Ocurrió un error al actualizar el evento
            return false;
        }
    }

    public function getCantInvitacionesDeMesa($id_mesa)
    {
        try {
            $sql = "SELECT COUNT(*) AS TOTAL FROM invitacion, mesa WHERE invitacion.mesa_asignada = mesa.id AND mesa.id = :id_mesa";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(1, $id_mesa, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al verificar obtener invitaciones: " . $e->getMessage();
            return null;
        }
    }

    public function getMesa($id_mesa)
    {
        try {
            $sql = "SELECT * FROM mesa WHERE id= :id_mesa";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(1, $id_mesa, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener la mesa: " . $e->getMessage();
            return null;
        }
    }
}