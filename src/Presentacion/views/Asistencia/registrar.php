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


    <div class="container" id="reader"
        style="display: flex; justify-content: center; align-items: center;  height: 30%;">
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
                    <th>ID Invitación</th>
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
                            <?php echo $asistencia['idInvitacion']; ?>
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
                            // Asignar valores detectados a los campos del formulario
                            document.getElementById('codigoQR').value = decodedText;
                            const urlParams = new URLSearchParams(window.location.search);
                            const eventId = urlParams.get('id');
                            document.getElementById('id').value = eventId;

                            // Prevenir el envío tradicional del formulario
                            enviarDatosAsistencia(decodedText, eventId);
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
        function enviarDatosAsistencia(codigoQR, idEvento) {
            fetch('/eventos/asistencia/registrar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `codigoQR=${codigoQR}&id=${idEvento}`
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Asistencia registrada:', data);
                })
                .catch((error) => {
                    console.error('Error al registrar la asistencia:', error);
                });
        }
        leerCodigoQR();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const urlParams = new URLSearchParams(window.location.search);
            const idEvento = urlParams.get('id');
            const url = '/eventos/asistencia/sse?id=' + idEvento;
            const eventSource = new EventSource(url);

            eventSource.addEventListener('asistencia', function (e) {
                const asistencias = JSON.parse(e.data);
                console.log(asistencias);
                const tbody = document.querySelector('table > tbody');
                tbody.innerHTML = '';
                asistencias.forEach(asistencia => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                <td>${asistencia.id}</td>
                <td>${asistencia.fecha_llegada}</td>
                <td>${asistencia.nombre_invitado}</td>
                <td>${asistencia.nro_celular}</td>
            `;
                    tbody.appendChild(tr);
                });
            }, false);
        });
    </script>

</body>

</html>