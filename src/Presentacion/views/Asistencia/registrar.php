<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asistencia</title>
    <!-- Agregar la librería para leer códigos QR -->
    <script src="https://cdn.jsdelivr.net/npm/@zxing/library@0.18.5/umd@5.3.0/zxing.umd.min.js"></script>
    <link rel="stylesheet" href="../style/asistencias.css">

</head>

<body>
    <?php include '../src/Presentacion/views/topbar.php';
    if (session_status() == PHP_SESSION_NONE)
        session_start();
    ?>
    <script>
        var eventos = <?php echo json_encode($eventos); ?>;
    </script>
    <h1>Registro de Asistencia</h1>
    <div style="display: flex; justify-content: center;">
        <video id="qr-video" width="40%" height="30%" autoplay></video>
    </div>
    <form id="asistencia-form" action="/eventos/asistencia/registrar" method="POST" style="display: none;">
        <input type="hidden" id="codigoQR" name="codigoQR">
    </form>
    <div class="container">
        <h1>Listado de Asistentes</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hora de Asistencia</th>
                    <th>Nombre</th>
                    <th>Nro Celular</th>

                    <!-- Agrega más columnas si es necesario -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asistencias as $asistencia): ?>
                    <tr>
                        <td>
                            <?php echo $asistencia['id']; ?>
                        </td>
                        <td>
                            <?php echo $asistencia['fecha_llegada']; ?>
                        </td>
                        <td>
                            <?php echo $asistencia['nombre_invitado']; ?>
                        </td>
                        <td>
                            <?php echo $asistencia['nro_celular']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        // Obtener el video y el canvas
        const video = document.getElementById('qr-video');

        // Función para leer el código QR
        async function leerCodigoQR() {
            // Obtener el stream de la cámara
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            video.srcObject = stream;

            // Inicializar el lector de códigos QR
            const codeReader = new ZXing.BrowserQRCodeReader();

            // Leer el código QR continuamente
            codeReader.decodeFromVideoDevice(null, 'qr-video', (result, err) => {
                if (result) {
                    // Enviar el código QR al servidor
                    document.getElementById('codigoQR').value = result.text;
                    document.getElementById('asistencia-form').submit();
                }
                if (err && !(err instanceof ZXing.NotFoundException)) {
                    console.error(err);
                }
            });
        }

        // Llamar a la función para leer el código QR
        leerCodigoQR();
    </script>
</body>

</html>