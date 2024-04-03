<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Invitaciones del Evento</title>
    <link rel="stylesheet" href="../style/listar_invitaciones.css">
    <style>
        /* Estilos para la ventana emergente */
        /* Estilos para el formulario de edición */
        .modal {
            display: none;
            /* Ocultar el modal por defecto */
            position: fixed;
            /* Posición fija */
            z-index: 1;
            /* Asegurar que el modal esté por encima del resto del contenido */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            /* Habilitar el desplazamiento si es necesario */
            background-color: rgba(0, 0, 0, 0.4);
            /* Fondo oscuro semitransparente */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            /* Centrar verticalmente y colocar 10% desde la parte superior */
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
            /* Ancho del contenido */
            border-radius: 5px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            /* Sombra */
        }

        /* Estilo para el botón de cerrar */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Estilo para los títulos */
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Estilos para los campos del formulario */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #2d3d2e;
        }
    </style>
</head>

<body>
    <?php include '../src/Presentacion/views/topbar.php';
    if (session_status() == PHP_SESSION_NONE)
        session_start();
    ?>
    <div class="container">
        <h2>Invitaciones del Evento</h2>
        <button onclick="crearInvitacion(<?php echo $_GET['id']; ?>)">Crear Invitación</button>
        <!-- Reemplaza rutaParaCrearInvitacion con tu ruta correcta -->
        <table>
            <thead>
                <tr>
                    <th>ID Invitación</th>
                    <th>Nombre del Invitado</th>
                    <th>Nro Celular del Invitado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invitaciones as $invitacion): ?>
                    <tr>
                        <td>
                            <?php echo ($invitacion['id_invitacion']); ?>
                        </td>
                        <td>
                            <?php echo ($invitacion['nombre_invitado']); ?>
                        </td>
                        <td>
                            <?php echo ($invitacion['nro_celular']); ?>
                        </td>
                        <td>
                            <button onclick="compartirInvitacion('<?php echo $invitacion['id_invitacion']; ?>', '<?php echo $invitacion['nro_celular']; ?>',
                                '<?php echo $invitacion['titulo']; ?>','<?php echo $invitacion['direccion']; ?> ','<?php echo $invitacion['descripcion']; ?>' , '<?php echo $invitacion['fecha']; ?>',
                                '<?php echo $invitacion['nombre_invitado']; ?> ' )">Compartir QR</button>
                            <button
                                onclick="eliminarInvitacion(<?php echo $invitacion['id_invitacion']; ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="crearInvitacionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('crearInvitacionModal')">&times;</span>
            <h2>Crear Invitación</h2>
            <form id="crearInvitacionForm" action="/eventos/invitaciones/crear" method="POST">
                <input type="hidden" id="id_evento" name="id_evento" value="<?php echo $_GET['id']; ?>">
                <div class="form-group">
                    <label for="nombre">Nombre del Invitado:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="nro_celular">Número de Celular:</label>
                    <input type="number" id="nro_celular" name="nro_celular" required>
                </div>
                <!-- Agrega más campos según sea necesario -->
                <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <div id="eliminarInvitacionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('eliminarInvitacionModal')">&times;</span>
            <h2>Eliminar Evento</h2>
            <form id="eliminarInvitacionForm" action="/eventos/invitaciones/eliminar" method="POST">
                <input type="hidden" id="id_invitacion" name="id_invitacion">
                <p>¿Estás seguro de querer eliminar esta invitacion?</p>
                <button type="submit">Eliminar</button>
                <button type="button" onclick="cerrarModal('eliminarInvitacionModal')">Cancelar</button>
            </form>
        </div>
    </div>

    <script>
        function compartirInvitacion(invitacionId, numeroCelular, titulo, direccion, descripcion, fecha, nombre_invitado) {
            // Genera el código QR
            var qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" + encodeURIComponent(invitacionId);
            console.log(descripcion);
            // Formatea el número de celular para WhatsApp (elimina los caracteres no numéricos)
            var mensaje = "¡Hola " + nombre_invitado + ", estás invitado a mi evento!\n" +
                "Título: " + titulo + "\n" +
                "Dirección: " + direccion + "\n" +
                "Descripción: " + descripcion + "\n" +
                "Fecha: " + fecha + "\n" +
                qrCodeUrl;

            // Genera el enlace con el número de celular y el código QR
            var enlace = "https://wa.me/" + numeroCelular + "?text=" + encodeURIComponent(mensaje);

            // Abre WhatsApp con el enlace generado
            window.open(enlace, '_blank');
        }

        function crearInvitacion(eventoId) {

            if (eventoId) {

                // Muestra la ventana emergente de edición
                document.getElementById('crearInvitacionModal').style.display = 'block';
            } else {
                // Si no se encuentra el evento, muestra un mensaje de error
                alert('Evento no encontrado');
            }
        }

        function eliminarInvitacion(invitacionId) {

            document.getElementById('id_invitacion').value = invitacionId;
            document.getElementById('eliminarInvitacionModal').style.display = 'block';
        }

        function cerrarModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = 'none';
        }
    </script>
</body>

</html>