<?php

class EventosDAO
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function crearEvento($titulo, $direccion, $descripcion, $fecha)
    {
        $sql = "INSERT INTO evento (titulo, direccion, descripcion, fecha) VALUES (?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(1, $titulo, PDO::PARAM_STR);
        $stmt->bindParam(2, $direccion, PDO::PARAM_STR);
        $stmt->bindParam(3, $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(4, $fecha, PDO::PARAM_STR);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // El evento se creó correctamente
            return true;
        } else {
            // Ocurrió un error al crear el evento
            return false;
        }
    }
    public function obtenerTodosEventos()
    {
        $sql = "SELECT * FROM evento ORDER BY id ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function actualizarEvento($id_evento, $titulo, $direccion, $descripcion, $fecha)
    {
        $sql = "UPDATE evento SET titulo = ?, direccion = ?, descripcion = ?, fecha = ? WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bindValue(1, $titulo);
        $stmt->bindValue(2, $direccion);
        $stmt->bindValue(3, $descripcion);
        $stmt->bindValue(4, $fecha);
        $stmt->bindValue(5, $id_evento, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // El evento se actualizó correctamente
            return true;
        } else {
            // Ocurrió un error al actualizar el evento
            return false;
        }
    }
    public function eliminarEvento($id_evento)
    {
        
        $sql = "DELETE FROM evento WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bindValue(1, $id_evento, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
