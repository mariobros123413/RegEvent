<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Invitaciones del Evento</title>
    <link rel="stylesheet" href="../style/listar_invitaciones.css">
    <link rel="stylesheet" href="../style/buttons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

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
    input[type="number"],
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
        <?php
        // Verificar si el evento se creó correctamente y mostrar un mensaje
        if (isset($_SESSION['invitacion_creada']) && $_SESSION['invitacion_creada'] === true) {
            // Eliminar la variable de sesión para que el mensaje no aparezca en futuras visitas a la página
            unset($_SESSION['invitacion_creada']);
            ?>
            <script>
                // Muestra una ventana emergente con el mensaje
                alert("La invitación se creó correctamente.");
            </script>
            <?php
        }
        ?>
        <?php
        // Verificar si el evento se creó correctamente y mostrar un mensaje
        if (isset($_SESSION['invitacion_eliminada']) && $_SESSION['invitacion_eliminada'] === true) {
            // Eliminar la variable de sesión para que el mensaje no aparezca en futuras visitas a la página
            unset($_SESSION['invitacion_eliminada']);
            ?>
            <script>
                // Muestra una ventana emergente con el mensaje
                alert("La invitación se eliminó correctamente.");
            </script>
            <?php
        }

        ?>

        <h2>Invitaciones del Evento</h2>
        <button type="submit" onclick="crearInvitacion(<?php echo $_GET['id']; ?>)">Crear Invitación</button>
        <!-- Reemplaza rutaParaCrearInvitacion con tu ruta correcta -->
        <table>
            <thead>
                <tr>
                    <th>ID Invitación</th>
                    <th>Nombre del Invitado</th>
                    <th>Nro Celular del Invitado</th>
                    <th>Mesa Asignada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <?php if (!empty($datos['invitaciones'])): ?>

                <tbody>
                    <?php foreach ($datos['invitaciones'] as $invitacion): ?>
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
                                <?php echo ($invitacion['mesa_asignada']); ?>
                            </td>
                            <td>
                                <button class="button button-qr" onclick="compartirInvitacion('<?php echo $invitacion['id_invitacion']; ?>', '<?php echo $invitacion['nro_celular']; ?>',
    '<?php echo $invitacion['titulo']; ?>','<?php echo $invitacion['direccion']; ?> ','<?php echo $invitacion['descripcion']; ?>' , '<?php echo $invitacion['fecha']; ?>',
    '<?php echo $invitacion['nombre_invitado']; ?> ' )">
                                    <i class="fa fa-whatsapp"></i>Compartir QR
                                </button>
                                <button class="button button-edit"
                                    onclick="editarInvitacion(<?php echo $invitacion['id_invitacion']; ?>)">Editar</button>

                                <button class="button button-delete"
                                    onclick="eliminarInvitacion(<?php echo $invitacion['id_invitacion']; ?>)">
                                    Eliminar
                                </button>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            <?php else: ?>
                <p>No hay invitaciones para mostrar. ¡Crea algunas!</p>
            <?php endif; ?>
        </table>

    </div>

    <div id="crearInvitacionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('crearInvitacionModal')">&times;</span>
            <h2>Formulario para Crear Invitación</h2>
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
                <div class="form-group">
                    <label for="mesa_asignada">Seleccionar Mesa:</label>
                    <select id="mesa_asignada" name="mesa_asignada" required>
                        <?php foreach ($datos['mesas'] as $mesa): ?>
                            <?php
                            // Verificar si hay sillas disponibles y si la mesa no está seleccionada
                            $sillas_disponibles = $mesa['sillas_disponibles'];
                            $disabled = ($sillas_disponibles == 0 ) ? 'disabled' : '';
                            ?>
                            <option value="<?php echo $mesa['id']; ?>" <?php echo $disabled; ?>>
                                Mesa <?php echo $mesa['id']; ?> (Sillas disponibles: <?php echo $sillas_disponibles; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Agrega más campos según sea necesario -->
                <button type="submit">Crear Invitación</button>
            </form>
        </div>
    </div>

    <div id="editarInvitacionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('editarInvitacionModal')">&times;</span>
            <h2>Editar Invitacion</h2>
            <form id="editarInvitacionForm" action="/eventos/invitaciones/editar" method="POST">
                <input type="hidden" id="idInvitacion" name="idInvitacion">
                <div class="form-group">
                    <label for="nombre_invitado">Nombre del Invitado:</label>
                    <input type="text" id="nombre_invitado" name="nombre_invitado" required>
                </div>
                <div class="form-group">
                    <label for="nrocelular">Número de Celular:</label>
                    <input type="number" id="nrocelular" name="nrocelular" required>
                </div>
                <div class="form-group">
                    <label for="mesa_asignada">Seleccionar Mesa:</label>
                    <select id="mesa_asignada" name="mesa_asignada" required>
                        <?php foreach ($datos['mesas'] as $mesa): ?>
                            <?php
                            // Verificar si hay sillas disponibles y si la mesa no está seleccionada
                            $sillas_disponibles = $mesa['sillas_disponibles'];
                            $disabled = ($sillas_disponibles == 0 ) ? 'disabled' : '';
                            ?>
                            <option value="<?php echo $mesa['id']; ?>" <?php echo $disabled; ?>>
                                Mesa <?php echo $mesa['id']; ?> (Sillas disponibles: <?php echo $sillas_disponibles; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Agrega más campos según sea necesario -->
                <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </div>


    <div id="eliminarInvitacionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('eliminarInvitacionModal')">&times;</span>
            <h2>Eliminar Invitación</h2>
            <form id="eliminarInvitacionForm" action="/eventos/invitaciones/eliminar" method="POST">
                <input type="hidden" id="id_invitacion" name="id_invitacion">
                <p>¿Estás seguro de querer eliminar esta invitacion?</p>
                <button class="button button-delete">Eliminar</button>
                <button class="button button-cancel" onclick="cerrarModal('eliminarInvitacionModal')">Cancelar</button>
            </form>
        </div>
    </div>

    <script>
        function compartirInvitacion(invitacionId, numeroCelular, titulo, direccion, descripcion, fecha, nombre_invitado) { //aumentar mesa_asignada, y enviar al whatsapp
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

        function editarInvitacion(invitacionId) {
            var invitaciones = <?php echo json_encode($datos['invitaciones']); ?>; // Obtener el array de invitaciones desde PHP
            var invitacion = invitaciones.find(function (item) {
                return item.id_invitacion === invitacionId; // Buscar la invitación por su ID
            });

            if (invitacion) {
                // Si se encuentra la invitación, completar el formulario de edición con sus datos
                document.getElementById('idInvitacion').value = invitacion.id_invitacion;
                document.getElementById('nombre_invitado').value = invitacion.nombre_invitado;
                document.getElementById('nrocelular').value = invitacion.nro_celular;
                document.getElementById('mesa_asignada').value = invitacion.mesa_asignada;

                // Mostrar la ventana emergente de edición
                document.getElementById('editarInvitacionModal').style.display = 'block';
            } else {
                // Si no se encuentra la invitación, mostrar un mensaje de error
                alert('Invitación no encontrada');
            }
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