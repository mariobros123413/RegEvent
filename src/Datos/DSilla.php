<?php

class DSilla
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function listarSillas($id_mesa)
    {
        $sql = "SELECT silla.* FROM silla WHERE id_mesa = :id_mesa";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_mesa', $id_mesa, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarSillas($id_mesa, $cant)
    {
        // Iniciar una transacción

        try {
            $query = "INSERT INTO silla (id_mesa, nro) VALUES (:id_mesa, :nro)";

            for ($i = 0; $i < $cant; $i++) {
                $stmt = $this->conexion->prepare($query);
                $stmt->bindValue(':id_mesa', $id_mesa, PDO::PARAM_INT);
                $stmt->bindValue(':nro', $i + 1, PDO::PARAM_INT);
                $stmt->execute();
            }

            return true;
        } catch (PDOException $e) {
            echo "Error al agregarSillas: " . $e->getMessage();

            return false;
        }
    }
    public function actualizarSilla($id_mesa, $cant)
    {
        try {
            $sql = "DELETE FROM silla WHERE id_mesa= :id_mesa";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id_mesa', $id_mesa, PDO::PARAM_INT);
            $stmt->execute();

            $this->agregarSillas($id_mesa, $cant);

            $query = "UPDATE mesa SET capacidad = ? WHERE id = ?";
            $stmt = $this->conexion->prepare($query);
            $stmt->bindValue(1, $cant);
            $stmt->bindValue(2, $id_mesa, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error al agregarSillas: " . $e->getMessage();
            return false;
        }
    }
}