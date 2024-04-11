<?php

class SillaDAO
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
            $query = "INSERT INTO silla (id_mesa) VALUES (:id_mesa)";

            for ($i = 0; $i < $cant; $i++) {
                $stmt = $this->conexion->prepare($query);

                $stmt->execute(array(':id_mesa' => $id_mesa));

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

    // public function actualizarSilla($id_mesa, $cant)
    // {
    //     // Obtener la cantidad actual de sillas en la mesa
    //     $query = "SELECT COUNT(*) AS total_sillas FROM silla WHERE id_mesa = :id_mesa";
    //     $statement = $this->conexion->prepare($query);
    //     $statement->execute(array(':id_mesa' => $id_mesa));
    //     $result = $statement->fetch(PDO::FETCH_ASSOC);
    //     $total_sillas = $result['total_sillas'];

    //     // Calcular la diferencia entre la cantidad actual y la cantidad deseada
    //     $diferencia = $cant - $total_sillas;

    //     if ($diferencia > 0) {
    //         // Agregar sillas si la cantidad deseada es mayor que la actual
    //         return $this->agregarSillas($id_mesa, $diferencia);
    //     } elseif ($diferencia < 0) {
    //         // Eliminar sillas si la cantidad deseada es menor que la actual
    //         return $this->eliminarSillas($id_mesa, abs($diferencia));
    //     } else {
    //         // No es necesario realizar ninguna actualización
    //         return true;
    //     }
    // }
    private function eliminarSillas($id_mesa, $cant)
    {
        try {
            // Eliminar la cantidad especificada de sillas de la mesa
            $query = "DELETE FROM silla WHERE id_mesa = :id_mesa LIMIT :cant";
            $statement = $this->conexion->prepare($query);
            $statement->bindValue(':id_mesa', $id_mesa, PDO::PARAM_INT);
            $statement->bindValue(':cant', $cant, PDO::PARAM_INT);
            $statement->execute();
            return true;
        } catch (PDOException $e) {
            // Manejar el error si ocurre durante la eliminación de sillas
            echo "Error al eliminar sillas: " . $e->getMessage();
            return false;
        }
    }

    public function sillasDisponibles($id_mesa)
    {

    }
}