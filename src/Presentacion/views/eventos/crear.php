<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Evento</title>
    <link rel="stylesheet" href="../style/crear_evento.css">
    <link rel="stylesheet" href="../style/buttons.css">

</head>

<body>
    <?php
    if (session_status() == PHP_SESSION_NONE)
        session_start();


    include '../src/Presentacion/views/topbar.php'; ?>

    <div class="container">
        <?php
        if (isset($_SESSION['evento_creado']) && $_SESSION['evento_creado'] === true) {
            unset($_SESSION['evento_creado']);
            ?>
            <script>
                alert("El evento se creó correctamente.");
            </script>
            <?php
        }
        ?>
        <h1>Crear Evento</h1>
        <form id="crearEventoForm" action='/eventos/crear' method="post">
            <div class="form-group">
                <label for="nombre">Nombre del Evento:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección del Evento:</label>
                <input type="text" id="direccion" name="direccion" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" required>
            </div>
            <div class="form-group">
                <label for="fecha">Fecha del Evento:</label>
                <input type="date" id="fecha" name="fecha" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label for="hora">Hora del Evento:</label>
                <input type="time" id="hora" name="hora" required>
            </div>
            <!-- Agrega más campos según sea necesario -->
            <button type="submit" onclick="crearEvento()">Crear Evento</button>
        </form>
    </div>

    <script>
        function crearEvento() {
            // Ejemplo de validación
            const titulo = document.getElementById('titulo').value;
            const direccion = document.getElementById('direccion').value;
            const descripcion = document.getElementById('descripcion').value;
            const fecha = document.getElementById('fecha').value;
            const hora = document.getElementById('hora').value;

            // Aquí puedes añadir más validaciones según sea necesario
            if (titulo === "" || direccion === "" || descripcion === "" || fecha === "" || hora === "") {
                
                return false;
            }

            // Si todo está bien, enviar el formulario
            document.getElementById('crearEventoForm').submit();
        }
    </script>
    </div>


</body>

</html>