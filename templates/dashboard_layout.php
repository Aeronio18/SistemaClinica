<?php


// Verificar que la sesión esté activa
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}

// Obtener los datos de sesión
$role = $_SESSION['role'];
$username = $_SESSION['username'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/style.css" rel="stylesheet">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f9f4; /* Fondo suave de clínica */
        }

        .navbar {
            background-color: #5f9ea0; /* Verde suave */
        }

        .navbar-brand {
            font-size: 1.3rem;
        }

        .navbar a {
            color: white !important;
        }

        /* Estilo personalizado para el sidebar */
        #sidebarMenu {
            background-color: #ffffff;
            color: #5f9ea0;
            min-height: 100vh;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #sidebarMenu .nav-link {
            color: #5f9ea0;
        }

        #sidebarMenu .nav-link:hover {
            background-color: #e8f5e9;
            color: #4CAF50;
        }

        /* Estilo para las tarjetas */
        .card {
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card .card-body {
            padding: 1.5rem;
        }

        .card .card-title {
            font-weight: bold;
        }

        .card.text-bg-primary {
            background-color: #0d6efd;
            color: white;
        }

        .card.text-bg-success {
            background-color: #198754;
            color: white;
        }

        .card.text-bg-warning {
            background-color: #ffc107;
            color: black;
        }

        .card.text-bg-danger {
            background-color: #dc3545;
            color: white;
        }

        .main-content {
            margin-left: 0;
            padding: 20px;
        }

        /* Ajustes para dispositivos pequeños */
        @media (max-width: 768px) {
            #sidebarMenu {
                display: none;
            }

            .navbar .navbar-toggler {
                display: block;
            }

            .main-content {
                padding-left: 0;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav d-flex w-100 justify-content-between">
            <li class="nav-item ms-3">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="../img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-top">
                    <span class="ms-4">MiSistema</span>
                </a>
            </li>
            <li class="nav-item ms-auto me-3">
                <a class="nav-link text-danger" href="../controllers/LogoutController.php">
                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <?php if ($role === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-users"></i> Cobradores
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-wallet"></i> Créditos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-file-alt"></i> Solicitudes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../view/patients.php">
                                <i class="fas fa-user-injured"></i> Pacientes
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($role === 'cajero'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../view/visits.php">
                                <i class="fas fa-calendar-check"></i> Visitas
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 text-center text-md-start">Bienvenido <?php echo htmlspecialchars($username); ?></h1>
            </div>
            <div class="container">
                <!-- Contenido dinámico -->
                <?php if (isset($content)) echo $content; ?>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>
<script>
  // Variables
  let video = document.getElementById('video');
  let canvas = document.getElementById('canvas');
  let context = canvas.getContext('2d');
  let startCameraButton = document.getElementById('start-camera');
  let registerVisitButton = document.getElementById('register-visit');
  let scanResult = document.getElementById('scan-result');
  let qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
  let patientId = null;

  // Función para iniciar la cámara
  startCameraButton.addEventListener('click', () => {
    // Solicitar permiso para acceder a la cámara
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
      .then(stream => {
        // Mostrar el video en la pantalla
        video.srcObject = stream;
        video.style.display = "block";
        video.play();
        startCameraButton.style.display = "none";
        // Comenzar el escaneo
        scanQRCode();
      })
      .catch(err => {
        console.error("Error al acceder a la cámara: ", err);
      });
  });

  // Función para escanear el código QR
  function scanQRCode() {
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
      // Establecer el tamaño del canvas y dibujar el video
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      // Intentar leer el QR
      let imageData = context.getImageData(0, 0, canvas.width, canvas.height);
      let code = jsQR(imageData.data, canvas.width, canvas.height);

      if (code) {
        // Mostrar el resultado y guardar el id del paciente
        scanResult.textContent = "Código QR detectado: " + code.data;
        patientId = code.data;
        registerVisitButton.disabled = false; // Habilitar el botón de registro
      }
    }

    // Continuar escaneando
    requestAnimationFrame(scanQRCode);
  }

  // Al hacer clic en el botón de registrar visita
  registerVisitButton.addEventListener('click', () => {
    if (patientId) {
      // Llamar a la función para registrar la visita en la base de datos
      registerVisit(patientId);
    }
  });

  // Función para registrar la visita
function registerVisit(patientId) {
  // Obtener el id del usuario logueado
  let userId = <?php echo $_SESSION['user_id']; ?>; 
  if (!userId) {
    alert("No se ha encontrado el ID de usuario.");
    return;
  }

  if (!patientId) {
    alert("No se ha escaneado un código QR válido.");
    return;
  }

  let url = "registrar_visita.php";
  let data = {
    paciente_id: patientId,
    registrado_por: userId
  };

  fetch(url, {
    method: 'POST',
    body: JSON.stringify(data),
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("Visita registrada con éxito.");
      qrModal.hide();
    } else {
      alert("Error al registrar la visita: " + (data.message || "Desconocido"));
    }
  })
  .catch(error => {
    console.error("Error:", error);
    alert("Error al registrar la visita.");
  });
}
</script>

<!-- Agregar el script para inicializar los tooltips -->
<script>
  // Activar tooltips de Bootstrap en la página
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
</script>
</body>
</html>
