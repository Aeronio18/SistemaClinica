<?php
session_start();
require_once('../models/PacienteModel.php');
require_once('../models/VisitaModel.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header('Location: ../view/login.php');
    exit();
}

$message = '';
$message_type = '';

// Procesar si ya se envió un ID de paciente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paciente_id'])) {
    $paciente_id = $_POST['paciente_id'];
    $user_id = $_SESSION['user_id']; // Suponiendo que se guarda el ID del usuario en la sesión

    // Registrar la visita
    $visita_id = VisitaModel::registrarVisita($paciente_id, $user_id);

    if ($visita_id) {
        $message = "Visita registrada con éxito.";
        $message_type = "success";
    } else {
        $message = "Error al registrar la visita.";
        $message_type = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Visita</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script> <!-- Incluir jsQR -->
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h5><i class="fas fa-user-check"></i> Registrar Visita</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?= $message_type; ?>"><?= $message; ?></div>
                        <?php endif; ?>

                        <div id="scan-result" class="mb-3 text-center">
                            <p class="text-muted">Escanee un código QR para obtener información del paciente.</p>
                        </div>
                        
                        <!-- Botón para iniciar el escaneo -->
                        <button id="scan-btn" class="btn btn-info w-100 mb-3">
                            <i class="fas fa-qrcode"></i> Escanear Código
                        </button>

                        <!-- Botón para registrar visita (se muestra después del escaneo) -->
                        <form id="register-visit-form" method="POST" style="display: none;">
                            <input type="hidden" name="paciente_id" id="paciente-id">
                            <div id="patient-info" class="alert alert-secondary"></div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check-circle"></i> Registrar Visita
                            </button>
                        </form>

                        <!-- Espacio para el lector QR (video de la cámara) -->
                        <div id="reader-container" style="display: none; width: 100%; text-align: center;">
                            <video id="reader" style="width: 100%; max-width: 500px; border: 1px solid #ccc;" autoplay></video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const scanButton = document.getElementById("scan-btn");
    const readerContainer = document.getElementById("reader-container");
    const scanResult = document.getElementById("scan-result");
    const registerForm = document.getElementById("register-visit-form");
    const patientInfo = document.getElementById("patient-info");
    const pacienteIdInput = document.getElementById("paciente-id");

    let video = document.getElementById("reader");
    let canvasElement = document.createElement('canvas');
    let canvas = canvasElement.getContext('2d');
    let stream = null; // Variable para almacenar el flujo de video

    // Crear una función para detener la cámara
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop()); // Detener todos los tracks
            stream = null;
        }
    }

    // Crear una función para capturar y escanear el código QR
    function scanQRCode() {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            // Ajustar el tamaño del canvas al del video
            canvasElement.height = video.videoHeight;
            canvasElement.width = video.videoWidth;

            // Dibujar el video en el canvas
            canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
            let imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);

            // Usar jsQR para leer el código QR de la imagen capturada
            let code = jsQR(imageData.data, canvasElement.width, canvasElement.height, {
                inversionAttempts: "dontInvert",
            });

            if (code) {
                console.log("Código QR detectado: ", code.data); // Verificar en consola
                pacienteIdInput.value = code.data;

                fetch(`../controllers/getPaciente.php?id=${code.data}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            patientInfo.innerHTML = `
                                <strong>Nombre:</strong> ${data.nombre}<br>
                                <strong>Teléfono:</strong> ${data.telefono}
                            `;
                            registerForm.style.display = "block";
                            stopCamera(); // Detener la cámara
                            readerContainer.style.display = "none";
                        } else {
                            scanResult.innerHTML = `<p class="text-danger">Paciente no encontrado.</p>`;
                            scanButton.style.display = "block";
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener datos del paciente:', error);
                        scanResult.innerHTML = `<p class="text-danger">Error al obtener datos del paciente.</p>`;
                        scanButton.style.display = "block";
                    });
            }
        }
        requestAnimationFrame(scanQRCode); // Continuar el ciclo de escaneo
    }

    scanButton.addEventListener("click", function () {
        Swal.fire({
            title: 'Permiso para usar la cámara',
            text: 'Este sitio necesita acceder a la cámara de tu dispositivo para escanear el código QR. ¿Deseas continuar?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    scanButton.style.display = "none";
                    readerContainer.style.display = "block";

                    // Acceder a la cámara
                    stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
                    video.srcObject = stream;
                    video.setAttribute("playsinline", true); // Necesario para iPhone
                    video.play();
                    scanQRCode(); // Iniciar el ciclo de escaneo
                } catch (error) {
                    console.error("Error al acceder a la cámara: ", error);
                    scanResult.innerHTML = `<p class="text-danger">No se pudo acceder a la cámara.</p>`;
                    scanButton.style.display = "block";
                }
            } else {
                Swal.fire('Acceso denegado', 'No se puede continuar sin permisos para la cámara.', 'error');
            }
        });
    });
});
</script>


</body>
</html>
