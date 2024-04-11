<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listar Eventos</title>
    <link rel="stylesheet" href="../style/listar_invitaciones.css">
</head>

<body>
    <?php include '../src/Presentacion/views/topbar.php';
    if (session_status() == PHP_SESSION_NONE)
        session_start();
    ?>
    <script>
        var mesas = <?php echo json_encode($mesas); ?>;
    </script>

    <div class="container">
        <?php
        if (isset($_SESSION['mesa_creada']) && $_SESSION['mesa_creada'] === true) {
            unset($_SESSION['mesa_creada']);
            ?>
            <script>
                alert("La mesa se creó correctamente.");
            </script>
            <?php
        }
        ?>
        <?php
        if (isset($_SESSION['mesa_eliminada']) && $_SESSION['mesa_eliminada'] === true) {
            unset($_SESSION['mesa_eliminada']);
            ?>
            <script>
                alert("La mesa se eliminó correctamente.");
            </script>
            <?php
        }
        ?>
        <?php
        if (isset($_SESSION['mesa_actualizada']) && $_SESSION['mesa_actualizada'] === true) {
            unset($_SESSION['mesa_actualizada']);
            ?>
            <script>
                alert("La mesa se actualizó correctamente.");
            </script>
            <?php
        }
        ?>
        <h1>Listado de Mesas</h1>
        <button onclick="crearMesa(<?php echo $_GET['id']; ?>)">Crear Mesa</button>

        <table>
            <thead>
                <tr>
                    <th>Nro de Mesa</th>
                    <th>Tipo</th>
                    <th>Cantidad total de sillas</th>
                    <th>Cantidad disponible de sillas</th>
                    <th>Acciones</th> <!-- Nueva columna para los botones de editar y eliminar -->

                    <!-- Agrega más columnas si es necesario -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mesas as $mesa): ?>
                    <tr>
                        <td>
                            <?php echo $mesa['id']; ?> <!-- hacer por nro de mesa-->
                        </td>
                        <td>
                            <?php echo $mesa['tipo']; ?>
                        </td>
                        <td>
                            <?php echo $mesa['capacidad']; ?>
                        </td>
                        <td>
                            <?php echo $mesa['sillas_disponibles']; ?>
                        </td>
                        <td>
                            <button onclick="gestSillas(<?php echo $mesa['id']; ?>, '<?php echo $mesa['sillas_disponibles']; ?>')">
                                Gestionar Sillas
                            </button>
                            <button onclick="editarMesa(<?php echo $mesa['id']; ?>)">Editar</button>

                            <button onclick="eliminarMesa(<?php echo $mesa['id']; ?>)">Eliminar</button>

                        </td>
                        <!-- Agrega más columnas si es necesario -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Ventana emergente para editar evento -->
    <div id="crearMesaModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('crearMesaModal')">&times;</span>
            <h2>Crear Mesa</h2>
            <form id="editarEventoForm" action="/eventos/mesas/crear" method="POST">
                <input type="hidden" id="id_evento" name="id_evento" value="<?php echo $_GET['id']; ?>">
                <div class="form-group">
                    <label for="tipo">Tipo de Mesa:</label>
                    <input type="text" id="tipo" name="tipo" required>
                </div>
                <div class="form-group">
                    <label for="cant_sillas">Cantidad de Sillas:</label>
                    <input type="number" id="cant_sillas" name="cant_sillas" required>
                </div>
                <!-- Agrega más campos según sea necesario -->
                <button type="submit">Crear Mesa</button>
            </form>
        </div>
    </div>

    <div id="editarMesaModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('editarMesaModal')">&times;</span>
            <h2>Editar Mesa</h2>
            <form id="editarMesaForm" action="/eventos/mesas/editar" method="POST">
                <input type="hidden" id="id_mesa" name="id_mesa">
                <div class="form-group">
                    <label for="tipos">Tipo de Mesa:</label>
                    <input type="text" id="tipos" name="tipos" required>
                </div>
                <div class="form-group">
                    <label>Para actualizar las sillas, pulse el botón "Gestionar Sillas"</label>
                </div>
                <!-- Agrega más campos según sea necesario -->
                <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <!-- Ventana emergente para eliminar evento -->
    <div id="eliminarMesaModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('eliminarMesaModal')">&times;</span>
            <form id="eliminarMesaForm" action="/eventos/mesa/eliminar" method="POST">
                <input type="hidden" id="mesa_id" name="mesa_id">
                <p>¿Estás seguro de querer eliminar esta mesa?</p>
                <button type="submit">Eliminar</button>
            </form>
        </div>
    </div>


    <script>

        function eliminarMesa(mesaId) {
            // Setea el ID del evento a eliminar en el formulario de eliminación
            document.getElementById('mesa_id').value = mesaId;
            console.log("Mesa Elim : " + mesaId);
            // Muestra el modal de confirmación para eliminar el evento
            document.getElementById('eliminarMesaModal').style.display = 'block';
        }

        function guardarCambios() {
            cerrarModal('editarEventoModal');
        }

        function gestSillas(mesaId, disp) {
            // Redirige al usuario a la página de invitaciones para el evento específico con información adicional
            window.location.href = '/eventos/mesas/sillas?id=' + mesaId + '&disp=' + encodeURIComponent(disp);
        }

        function cerrarModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = 'none';
        }

        function editarMesa(mesaId) {
            // Busca el evento correspondiente utilizando su ID

            var mesa = mesas.find(function (item) {
                return item.id === mesaId;
            });
            if (mesa) {
                // Si se encuentra el evento, completa el formulario de edición con sus datos

                document.getElementById('id_mesa').value = mesaId;
                document.getElementById('tipos').value = mesa.tipo;

                // Muestra la ventana emergente de edición
                document.getElementById('editarMesaModal').style.display = 'block';
            } else {
                // Si no se encuentra el evento, muestra un mensaje de error
                alert('Mesa no encontrado');
            }
        }

        function crearMesa(eventoId) {
            if (eventoId) {

                // Muestra la ventana emergente de edición
                document.getElementById('crearMesaModal').style.display = 'block';
            } else {
                // Si no se encuentra el evento, muestra un mensaje de error
                alert('Evento no encontrado');
            }
        }
    </script>
</body>

</html>