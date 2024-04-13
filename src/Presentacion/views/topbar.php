<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Mi Proyecto</title>
    <link rel="stylesheet" href="../../../style/topbar.css">
</head>

<body>

    <!-- Barra de navegación -->
    <div class="navbar">
        <?php $current_page = $_SERVER['REQUEST_URI']; ?>
        <a href="/" class="<?= ($current_page == '/') ? 'active' : '' ?>">Inicio</a>
        <a href="/eventos/crear" class="<?= ($current_page == '/eventos/crear') ? 'active' : '' ?>">Crear Evento</a>
        <a href="/eventos/listar" class="<?= ($current_page == '/eventos/listar') ? 'active' : '' ?>">Listar Eventos</a>
    </div>

</body>

</html>