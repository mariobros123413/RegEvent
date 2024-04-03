<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asistencia</title>
    <!-- Agregar la librería para leer códigos QR -->
    <script src="https://unpkg.com/html5-qrcode"></script>


    <link rel="stylesheet" href="../style/asistencias.css">

</head>

<body>

    <?php include '../src/Presentacion/views/topbar.php';
    if (session_status() == PHP_SESSION_NONE)
        session_start();
    ?>
    <script>
        var eventos = <?php echo json_encode($asistencias); ?>;
    </script>
    <h1>Registro de Asistencia</h1>
    <div id="reader" style="display: flex; justify-content: center; align-items: center;  height: 30%;">
        <video id="qr-video" width="40%" height="30%" autoplay></video>
    </div>

    <form id="asistencia-form" action="/eventos/asistencia/registrar" method="POST" style="display: none;">
        <input type="hidden" id="codigoQR" name="codigoQR">
        <input type="hidden" id="id" name="id">

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
    <!-- Modal -->

    <script>
        // Obtener el video y el canvas
        const video = document.getElementById('qr-video');

        async function setupCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                video.srcObject = stream;
                await new Promise(resolve => video.onloadedmetadata = resolve);
            } catch (error) {
                console.error('Error al acceder a la cámara:', error);
            }
        }

        // Función para leer el código QR
        async function leerCodigoQR() {
            await setupCamera();

            const html5QrCode = new Html5Qrcode("reader");
            // This method will trigger user permissions
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    var cameraId = devices[0].id;
                    html5QrCode.start(
                        cameraId,
                        {
                            fps: 100,    // Optional, frame per seconds for qr code scanning
                        },
                        (decodedText, decodedResult) => {
                            document.getElementById('codigoQR').value = decodedText;
                            const urlParams = new URLSearchParams(window.location.search);
                            const eventId = urlParams.get('id');
                            document.getElementById('id').value = eventId;
                            document.getElementById('asistencia-form').submit();
                        },
                        (errorMessage) => {
                            //
                            console.log(errorMessage);
                        })
                        .catch((err) => {
                            console.log(err);
                        });
                }
            }).catch(err => {
                // handle err
            });
        }
        leerCodigoQR();
    </script>
</body>

</html>