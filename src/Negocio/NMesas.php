<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();



require_once './../src/Datos/MesaDAO.php';
require_once './../src/Negocio/NSilla.php';
class NMesa
{
    private $DMesa;
    private $NSilla;
    public function __construct($conexion)
    {
        $this->DMesa = new MesaDAO($conexion);
        $this->NSilla = new NSilla($conexion);
    }

    public function listarMesas($eventoId)
    {
        if ($eventoId) {
            $mesas = $this->DMesa->listarMesas($eventoId);
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
        $resultado = $this->DMesa->crearMesa($eventoId, $tipo, $cant_sillas);
        $resultado_silla = $this->NSilla->agregarSillas($resultado, $cant_sillas);

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
        
        $resultado = $this->DMesa->eliminarMesa($mesaId);
        if ($resultado) {
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
        $resultado = $this->DMesa->actualizarMesa($eventoId, $tipo);
        if ($resultado) {
            $_SESSION["mesa_actualizada"] = true;
            header("Location: /eventos/mesas?id={$_SESSION['evento_mesa_actual']['id']}");
            return true;
        } else {
            echo "Hubo un error al actualizar la mesa";
            return false;
        }
    }

    // public function getCantInvitaciones($mesaId)
    // {
    //     return $this->DMesa->getCantInvitacionesDeMesa($mesaId);
    // }

    // public function getCantSillasDisponibles($mesaId){
    //     $cantInvitacionesMesa = $this->DMesa->getCantInvitacionesDeMesa($mesaId);
    //     $capacidadMesa = $this->DMesa->getMesa($mesaId)["capacidad"];
    //     return $capacidadMesa - $cantInvitacionesMesa;
    // }
}
$nMesa = new NMesa($conexion);

?>