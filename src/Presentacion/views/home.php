<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Mi Proyecto</title>
    <style>

    </style>
    <link rel="stylesheet" href="../style/home.css">

</head>

<body>

    <!-- Barra de navegación -->
    <?php include '../src/Presentacion/views/topbar.php'; ?>


    <!-- Contenido principal -->
    <div class="content">
        <h1>Bienvenido a Mi Proyecto</h1>
        <p>¡Explora nuestro proyecto a través de estas imágenes!</p>

        <!-- Galería de imágenes -->
        <div class="gallery">
            <div class="image">
                <img src="../image/evento.jpeg" alt="Descripción de la imagen 1">
                <div class="desc">Gestiona tus eventos</div>
            </div>
            <div class="image">
                <img src="../image/invitacion.jpeg" alt="Descripción de la imagen 2">
                <div class="desc">Gestiona tus invitaciones</div>
            </div>
            <div class="image">
                <img src="../image/asistencia.jpeg" alt="Descripción de la imagen 3">
                <div class="desc">Toma el control con pases QR</div>
            </div>
            <!-- Agrega más imágenes según sea necesario -->
        </div>
    </div>
    </div>

</body>

</html>