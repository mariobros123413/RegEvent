<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Evento</title>
    <link rel="stylesheet" href="../style/crear_evento.css">
</head>

<body>
    <?php
    if (session_status() == PHP_SESSION_NONE)
        session_start();
    
    
    include '../src/Presentacion/views/topbar.php'; ?>

    <div class="container">
        <?php
        // Verificar si el evento se creó correctamente y mostrar un mensaje
        if (isset($_SESSION['evento_creado']) && $_SESSION['evento_creado'] === true) {
            // Eliminar la variable de sesión para que el mensaje no aparezca en futuras visitas a la página
            unset($_SESSION['evento_creado']);
            ?>
            <script>
                // Muestra una ventana emergente con el mensaje
                alert("El evento se creó correctamente.");
            </script>
            <?php
        }
        ?>
        <h1>Crear Evento</h1>
        <form action='/eventos/crearController' method="post">
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
            <button type="submit" name="enviar">Crear Evento</button>
        </form>
    </div>


</body>

</html>