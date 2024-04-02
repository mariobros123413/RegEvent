<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Invitaciones del Evento</title>
    <link rel="stylesheet" href="../style/listar_invitaciones.css">
</head>

<body>
    <?php include '../src/Presentacion/views/topbar.php';
    if (session_status() == PHP_SESSION_NONE)
        session_start();
    ?>
    <div class="container">
        <h2>Invitaciones del Evento</h2>
        <a href="rutaParaCrearInvitacion">Añadir Nueva Invitación</a>
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
                            <?php echo htmlspecialchars($invitacion['id']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($invitacion['nombre_invitado']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($invitacion['nro_celular']); ?>
                        </td>
                        <td>
                            <!-- Aquí puedes añadir botones o enlaces para editar y eliminar invitaciones -->
                            <a href="rutaParaEditarInvitacion?id=<?php echo $invitacion['id']; ?>">Editar</a>
                            <!-- Reemplaza rutaParaEditarInvitacion con tu ruta correcta -->
                            <a href="rutaParaEliminarInvitacion?id=<?php echo $invitacion['id']; ?>"
                                onclick="return confirm('¿Estás seguro de querer eliminar esta invitación?');">Eliminar</a>
                            <!-- Reemplaza rutaParaEliminarInvitacion con tu ruta correcta -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>