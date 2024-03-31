<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listar Eventos</title>
    <link rel="stylesheet" href="../style/listar_eventos.css">
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
    <script>
        var eventos = <?php echo json_encode($eventos); ?>;
    </script>

    <div class="container">
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
                            <button onclick="editarEvento(<?php echo $evento['id']; ?>)">Editar</button>
                            <!-- Botón para eliminar -->
                            <button onclick="eliminarEvento(<?php echo $evento['id']; ?>)">Eliminar</button>

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
            <form id="editarEventoForm">
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
            // Lógica para eliminar el evento con el ID correspondiente
            // Se omite por simplicidad
        }

        function guardarCambios() {
            // Lógica para guardar los cambios del evento mediante AJAX
            // Se omite por simplicidad
            // Después de guardar los cambios, cerrar la ventana emergente
            cerrarModal('editarEventoModal');
        }

        function cerrarModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = 'none';
        }
    </script>
</body>

</html>