<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listar Eventos</title>
    <link rel="stylesheet" href="../../../style/listar_invitaciones.css">

</head>

<body>
    <?php include '../src/Presentacion/views/topbar.php';

    if (session_status() == PHP_SESSION_NONE)
        session_start();

    $sillas = isset($sillas) ? $sillas : array();
    $totalSillas = count($sillas);
    ?>
    <script>
        var totalSillas = <?php echo json_encode($totalSillas); ?>;
    </script>

    <div class="container">
        <?php
        if (isset($_SESSION['silla_actualizada']) && $_SESSION['silla_actualizada'] === true) {
            unset($_SESSION['silla_actualizada']);
            ?>
            <script>
                alert("Las sillas se crearon correctamente.");
            </script>
            <?php
        }
        ?>
        <h1>Listado de Sillas</h1>
        <button onclick="actualizarSillas(<?php echo $_GET['id']; ?>)">Actualizar Sillas</button>
        <br>
        <br>
        <label for="cant">Cantidad total de sillas: <?php echo $totalSillas; ?></label>
        <br>
        <label for="cant">Sillas disponibles: <?php echo $_GET['disp']; ?></label>
        <table>
            <thead>
                <tr>
                    <th>Nro de Silla</th>

                    <!-- Agrega más columnas si es necesario -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sillas as $silla): ?>
                    <tr>
                        <td>
                            <?php echo $silla['id']; ?> <!-- hacer por nro de mesa-->
                        </td>

                        <!-- Agrega más columnas si es necesario -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <div id="editarSillasModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('editarSillasModal')">&times;</span>
            <h2>Editar Sillas de la Mesa</h2>
            <form id="editarSillaForm" action="/eventos/mesas/sillas/editar" method="POST"
                onsubmit="return validarCantidadSillas()">
                <input type="hidden" id="id_mesa" name="id_mesa">

                <div class="form-group">
                    <label for="cant">Cantidad de Sillas:</label>
                    <input type="number" id="cant" name="cant" required>
                </div>
                <!-- Agrega más campos según sea necesario -->
                <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <script>
        function validarCantidadSillas() {
            var nuevaCantidad = parseInt(document.getElementById('cant').value);
            var disponibles = <?php echo isset($_GET['disp']) ? $_GET['disp'] : 0; ?>;
            console.log("Total de sillas:", totalSillas);
            console.log("Sillas disponibles:", disponibles);

            if (nuevaCantidad < (totalSillas - disponibles)) {
                alert('La nueva cantidad de sillas debe ser mayor o igual a la cantidad de sillas ocupadas.');
                return false; // Evita que se envíe el formulario si la validación falla
            }
            return true; // Permite enviar el formulario si la validación es exitosa
        }


        function actualizarSillas(mesaId) {

            if (mesaId) {
                // Si se encuentra el evento, completa el formulario de edición con sus datos

                document.getElementById('id_mesa').value = mesaId;
                document.getElementById('cant').value = totalSillas;

                // Muestra la ventana emergente de edición
                document.getElementById('editarSillasModal').style.display = 'block';
            } else {
                // Si no se encuentra el evento, muestra un mensaje de error
                alert('Mesa no encontrado');
            }
        }
        function cerrarModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = 'none';
        }

    </script>
</body>

</html>