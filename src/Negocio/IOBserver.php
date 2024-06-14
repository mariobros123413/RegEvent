<?php
interface IObserver
{
    public function update($id_evento, $titulo, $direccion, $descripcion, $fechaHora);
}

?>