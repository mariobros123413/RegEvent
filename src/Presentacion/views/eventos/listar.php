<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listar Eventos</title>
    <link rel="stylesheet" href="../style/listar_eventos.css">
    <link rel="stylesheet" href="../style/buttons.css">

</head>

<body>
    <?php include '../src/Presentacion/views/topbar.php';
    if (session_status() == PHP_SESSION_NONE)
        session_start();
    ?>
    <script>
        var eventos = <?php echo json_encode($eventos); ?>;
    </script>

    <div class="container">
        <?php
        // Verificar si el evento se creó correctamente y mostrar un mensaje
        if (isset($_SESSION['evento_actualizado']) && $_SESSION['evento_actualizado'] === true) {
            // Eliminar la variable de sesión para que el mensaje no aparezca en futuras visitas a la página
            unset($_SESSION['evento_actualizado']);
            ?>
            <script>
                // Muestra una ventana emergente con el mensaje
                alert("El evento se actualizó correctamente.");
            </script>
            <?php
        }
        ?>
        <?php
        // Verificar si el evento se creó correctamente y mostrar un mensaje
        if (isset($_SESSION['evento_eliminado']) && $_SESSION['evento_eliminado'] === true) {
            // Eliminar la variable de sesión para que el mensaje no aparezca en futuras visitas a la página
            unset($_SESSION['evento_eliminado']);
            ?>
            <script>
                // Muestra una ventana emergente con el mensaje
                alert("El evento se eliminó correctamente.");
            </script>
            <?php
        }
        ?>
        <h1>Listado de Eventos</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Dirección</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Acciones</th> <!-- Nueva columna para los botones de editar y eliminar -->

                    <!-- Agrega más columnas si es necesario -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($eventos as $evento): ?>
                    <tr>
                        <td>
                            <?php echo $evento['id']; ?>
                        </td>
                        <td>
                            <?php echo $evento['titulo']; ?>
                        </td>
                        <td>
                            <?php echo $evento['direccion']; ?>
                        </td>
                        <td>
                            <?php echo $evento['descripcion']; ?>
                        </td>
                        <td>
                            <?php echo $evento['fecha']; ?>
                        </td>
                        <td>
                            <button class="button button-edit"onclick="editarEvento(<?php echo $evento['id']; ?>)">Editar</button>
                            <!-- Botón para eliminar -->
                            <button class="button button-delete" onclick="eliminarEvento(<?php echo $evento['id']; ?>)">Eliminar</button>
                            <button
                            type="submit" onclick="verInvitaciones('<?php echo $evento['id']; ?>','<?php echo $evento['titulo']; ?>',
                                '<?php echo $evento['direccion']; ?>', '<?php echo $evento['descripcion']; ?>', '<?php echo $evento['fecha']; ?>')">
                                Ver Invitaciones
                            </button>
                            <button  type="submit" onclick="registrarAsistencia(<?php echo $evento['id']; ?>)">Registrar
                                Asistencia
                            </button>
                            <button  type="submit" onclick="verMesas(<?php echo $evento['id']; ?>)">Gestionar Mesas
                            </button>
                        </td>
                        <!-- Agrega más columnas si es necesario -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Ventana emergente para editar evento -->
    <div id="editarEventoModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('editarEventoModal')">&times;</span>
            <h2>Editar Evento</h2>
            <form id="editarEventoForm" action="/eventos/editar" method="POST">
                <input type="hidden" id="id_evento" name="id_evento">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" id="descripcion" name="descripcion" required>
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha del Evento:</label>
                    <input type="date" id="fecha" name="fecha" required>
                </div>
                <div class="form-group">
                    <label for="hora">Hora del Evento:</label>
                    <input type="time" id="hora" name="hora" required>
                </div>
                <!-- Agrega más campos según sea necesario -->
                <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <!-- Ventana emergente para eliminar evento -->
    <div id="eliminarEventoModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('eliminarEventoModal')">&times;</span>
            <h2>Eliminar Evento</h2>
            <form id="eliminarEventoForm" action="/eventos/eliminar" method="POST">
                <input type="hidden" id="id_evento_eliminar" name="id_evento_eliminar">
                <p>¿Estás seguro de querer eliminar este evento?</p>
                <button class="button button-delete">Eliminar</button>
                <button type="button" class="button button-cancel" onclick="cerrarModal('eliminarEventoModal')">Cancelar</button>
            </form>
        </div>
    </div>


    <script>
        function editarEvento(eventoId) {
            // Busca el evento correspondiente utilizando su ID
            var evento = eventos.find(function (item) {
                return item.id === eventoId;
            });
            console.log(evento.titulo);
            if (evento) {
                var fechaHora = evento.fecha.split(' ');
                var fecha = fechaHora[0];
                var hora = fechaHora[1];
                console.log(fecha);
                // Si se encuentra el evento, completa el formulario de edición con sus datos
                document.getElementById('id_evento').value = eventoId;
                document.getElementById('titulo').value = evento.titulo;
                document.getElementById('direccion').value = evento.direccion;
                document.getElementById('descripcion').value = evento.descripcion;
                document.getElementById('fecha').value = fecha;
                document.getElementById('hora').value = hora;

                // Muestra la ventana emergente de edición
                document.getElementById('editarEventoModal').style.display = 'block';
            } else {
                // Si no se encuentra el evento, muestra un mensaje de error
                alert('Evento no encontrado');
            }
        }

        function eliminarEvento(eventoId) {
            // Setea el ID del evento a eliminar en el formulario de eliminación
            document.getElementById('id_evento_eliminar').value = eventoId;
            // Muestra el modal de confirmación para eliminar el evento
            document.getElementById('eliminarEventoModal').style.display = 'block';
        }

        function guardarCambios() {
            cerrarModal('editarEventoModal');
        }

        function verInvitaciones(eventoId, nombreEvento, lugar, descripcion, hora) {
            // Redirige al usuario a la página de invitaciones para el evento específico con información adicional
            window.location.href = '/eventos/invitaciones?id=' + eventoId + '&titulo=' + encodeURIComponent(nombreEvento) + '&direccion=' + encodeURIComponent(lugar) + '&descripcion=' + encodeURIComponent(nombreEvento) + '&fecha=' + encodeURIComponent(hora);
        }

        function registrarAsistencia(eventoId) {
            window.location.href = '/eventos/asistencia?id=' + eventoId;
        }

        function verMesas(eventoId) {
            window.location.href = '/eventos/mesas?id=' + eventoId;
        }

        function cerrarModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = 'none';
        }
    </script>
</body>

</html>