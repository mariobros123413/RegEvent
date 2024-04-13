<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();



require_once './../src/Datos/DSilla.php';

class NSilla
{
    private $dSilla;

    public function __construct($conexion)
    {
        $this->dSilla = new DSilla($conexion);
    }

    public function listarSillas($mesaId)
    {
        if ($mesaId) {
            $sillas = $this->dSilla->listarSillas($mesaId);
            $_SESSION['mesa_actual'] = [
                'id' => $_GET['id'],
                'disp' => $_GET['disp'],
            ];
            return $sillas;
        } else {
            echo "No se han encontrado sillas.";
            return false;
        }
    }

    public function actualizarSillas($mesaId, $cant)
    {
        $resultado = $this->dSilla->actualizarSilla($mesaId, $cant);
        if ($resultado) {
            $_SESSION['sillas_actualizadas'] = true;
            echo "<script>window.location.href = '/eventos/mesas?id={$_SESSION['evento_mesa_actual']['id']}';</script>";
            return true;
        } else {
            echo "Hubo un error al actualizar las sillas";
            return false;
        }
    }

    public function agregarSillas($mesaId, $cant)
    {
        $resultado = $this->dSilla->agregarSillas($mesaId, $cant);
        if ($resultado) {
            // header("Location: /eventos/mesas");
            return true;
        } else {
            echo "Hubo un error al agregar las sillas";
            return false;
        }
    }
}
$nSilla = new NSilla($conexion);

?>