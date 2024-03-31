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
        $sql = "SELECT * FROM evento";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEventoPorId($id_evento){
        $sql = "SELECT * FROM evento WHERE id=$id_evento";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Agrega métodos para leer, actualizar y eliminar eventos según sea necesario
}
