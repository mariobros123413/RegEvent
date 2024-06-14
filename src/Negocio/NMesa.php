<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();



require_once './../src/Datos/DMesa.php';
require_once './../src/Negocio/NSilla.php';
class NMesa
{
    private $dMesa;
    private $nSilla;
    public function __construct($conexion)
    {
        $this->dMesa = new DMesa($conexion);
        $this->nSilla = new NSilla($conexion);
    }

    public function listarMesas($eventoId)
    {
        if ($eventoId) {
            $mesas = $this->dMesa->listarMesas($eventoId);
            $_SESSION['evento_mesa_actual'] = [
                'id' => $_GET['id'],
            ];
            return $mesas;
        } else {
            echo "No se han encontrado mesas.";
            return false;
        }
    }

    public function crearMesa($eventoId, $tipo, $cant_sillas)
    {
        $resultado = $this->dMesa->crearMesa($eventoId, $tipo, $cant_sillas);
        $resultado_silla = $this->nSilla->agregarSillas($resultado, $cant_sillas);

        if ($resultado_silla) {
            $_SESSION['mesa_creada'] = true;
            header("Location: /eventos/mesas?id={$_SESSION['evento_mesa_actual']['id']}");

            return true;
        } else {
            echo "Hubo un error al crear la mesa";
            return false;
        }
    }

    public function eliminarMesa($mesaId)
    {

        $resultado = $this->dMesa->eliminarMesa($mesaId);
        $resultado2 = $this->setMesaCero($mesaId);
        if ($resultado && $resultado2) {
            $_SESSION["mesa_eliminada"] = true;
            header("Location: /eventos/mesas?id={$_SESSION['evento_mesa_actual']['id']}");
            return true;
        } else {
            echo "Hubo un error al eliminar la mesa";
            return false;
        }
    }

    public function actualizarMesa($eventoId, $tipo)
    {
        $resultado = $this->dMesa->actualizarMesa($eventoId, $tipo);
        if ($resultado) {
            $_SESSION["mesa_actualizada"] = true;
            header("Location: /eventos/mesas?id={$_SESSION['evento_mesa_actual']['id']}");
            return true;
        } else {
            echo "Hubo un error al actualizar la mesa";
            return false;
        }
    }

    public function setMesaCero($idMesa)
    {
        $resultado = $this->dMesa->cancelarMesa($idMesa);
        if ($resultado) {
            return true;
        } else {
            echo "Hubo un error al setMesaCero la mesa";
            return false;
        }
    }
}

?>