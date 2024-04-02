<?php

class EventosDAO
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function crearEvento($id_usuario_organizador, $titulo, $direccion, $descripcion, $fecha)
    {
        // Preparar la consulta SQL para insertar un nuevo evento en la base de datos
        $sql = "INSERT INTO evento (titulo, direccion, descripcion, fecha, id_usuario) VALUES (?, ?, ?, ?, ?)";

        // Preparar la sentencia
        $stmt = $this->conexion->prepare($sql);

        // Enlazar los valores con los marcadores de posición

        $stmt->bindParam(1, $titulo, PDO::PARAM_STR);
        $stmt->bindParam(2, $direccion, PDO::PARAM_STR);
        $stmt->bindParam(3, $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(4, $fecha, PDO::PARAM_STR);
        $stmt->bindParam(5, $id_usuario_organizador, PDO::PARAM_INT);

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
        // Preparar la consulta SQL para actualizar el evento en la base de datos
        $sql = "UPDATE evento SET titulo = ?, direccion = ?, descripcion = ?, fecha = ? WHERE id = ?";

        // Preparar la sentencia
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            // No se pudo preparar la sentencia
            return false;
        }
        $stmt->bindValue(1, $titulo);
        $stmt->bindValue(2, $direccion);
        $stmt->bindValue(3, $descripcion);
        $stmt->bindValue(4, $fecha);
        $stmt->bindValue(5, $id_evento, PDO::PARAM_INT); // Especificar que el id_evento es un entero.

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
