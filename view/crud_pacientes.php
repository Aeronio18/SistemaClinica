<?php
session_start();
require_once('../models/PatientModel.php'); // Modelo para manejar pacientes
require_once('../helpers/QRHelper.php'); // Helper para generar QR
require_once('../helpers/PDFHelper.php'); // Helper para generar el PDF

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    $_SESSION['user_id'] = $usuario['id'];  // 'id' es el ID del usuario en la base de datos
$_SESSION['username'] = $usuario['nombre_usuario'];
$_SESSION['role'] = $usuario['tipo_usuario'];
    // Redirigir al login si no hay sesión activa
    header('Location: ../view/login.php');
    exit;
}

// Obtener el user_id de la sesión
$user_id = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $fullName = htmlspecialchars(trim($_POST['full_name']));
    $birthDate = htmlspecialchars(trim($_POST['birth_date']));
    $age = (int) $_POST['age'];
    $gender = htmlspecialchars(trim($_POST['gender']));
    $occupation = htmlspecialchars(trim($_POST['occupation']));
    $contactNumber = htmlspecialchars(trim($_POST['contact_number']));
    $email = htmlspecialchars(trim($_POST['email']));
    $allergies = htmlspecialchars(trim($_POST['allergies']));

    if (!empty($fullName) && !empty($birthDate) && !empty($age) && !empty($gender) && !empty($occupation) && !empty($contactNumber) && !empty($email)) {
        // Verificar si el paciente ya existe
        if (PatientModel::checkExistingPatient($email, $contactNumber)) {
            // Si el paciente existe, mostrar el SweetAlert
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Paciente Existente',
                        text: 'Ya existe un paciente con ese correo o número de contacto.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.history.back();
                    });
                  </script>";
        } else {
            // Generar un código único para el paciente
            $codigoUnico = uniqid('patient_', true);

            // Insertar el paciente en la base de datos
            $patientId = PatientModel::createPatient($fullName, $birthDate, $age, $gender, $occupation, $contactNumber, $email, $codigoUnico, $allergies);

            if ($patientId) {
                // Generar el código QR
                $qrFile = QRHelper::generateQRCode($codigoUnico);

                // Generar el PDF con la tarjeta del paciente
                $pdfFile = PDFHelper::generatePatientCard($fullName, $birthDate, $age, $gender, $occupation, $contactNumber, $email, $allergies, $codigoUnico, $qrFile);

                // Descargar el PDF con la tarjeta
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="tarjeta_paciente_' . $codigoUnico . '.pdf"');
                readfile($pdfFile);
                exit;
            } else {
                echo "Error al registrar el paciente.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Paciente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.9/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/style.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f9f4; /* Fondo suave de clínica */
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            background-color: #5f9ea0; /* Color verde suave */
        }

        .navbar a {
            color: white !important;
        }

        .container {
            background-color: #ffffff; /* Fondo blanco */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
            color: #4CAF50; /* Color verde para las etiquetas */
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #4CAF50; /* Color verde en el foco */
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.5);
        }

        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
            font-size: 1.1rem;
            padding: 10px 15px;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }

        .sidebar {
            background-color: #f1f1f1;
            padding-top: 30px;
        }

        .sidebar .nav-link {
            font-size: 1.1rem;
            padding: 10px 15px;
        }

        .main-content {
            margin-left: 0;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .navbar .navbar-toggler {
                border-color: #4CAF50;
            }

            .form-control {
                font-size: 0.9rem;
            }

            .btn-primary {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav d-flex w-100 justify-content-between">
            <li class="nav-item ms-3">
                <a class="navbar-brand d-flex align-items-center" href="">
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

<!-- Sidebar -->
<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="../view/dashboard_admin.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="crud_pacientes.php">
                            <i class="fas fa-users"></i> Pacientes
                        </a>
                    </li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-users"></i> Cobradores
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Registrar Nuevo Paciente</h1>
            </div>

           <!-- Formulario para crear paciente -->
<div class="container">
    <h4 class="mb-4">Formulario de Registro de Paciente</h4>
    <form action="crud_pacientes.php" method="POST" id="patientForm">
        <!-- Fila combinada de Nombre Completo, Fecha de Nacimiento, Edad y Género -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="full_name" class="form-label"><i class="fas fa-user"></i> Nombre Completo</label>
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Ingresa el nombre completo" required>
            </div>
            <div class="col-md-3">
                <label for="birth_date" class="form-label"><i class="fas fa-calendar-alt"></i> Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="birth_date" name="birth_date" required>
            </div>
            <div class="col-md-2">
                <label for="age" class="form-label"><i class="fas fa-birthday-cake"></i> Edad</label>
                <input type="number" class="form-control" id="age" name="age" placeholder="Edad" required>
            </div>
            <div class="col-md-3">
                <label for="gender" class="form-label"><i class="fas fa-venus-mars"></i> Género</label>
                <select class="form-select" id="gender" name="gender" required>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
        </div>
        
        <!-- Fila combinada de Ocupación, Número de Contacto y Correo Electrónico -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="occupation" class="form-label"><i class="fas fa-briefcase"></i> Ocupación</label>
                <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Ocupación" required>
            </div>
            <div class="col-md-4">
                <label for="contact_number" class="form-label"><i class="fas fa-phone"></i> Número de Contacto</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Número de Contacto" required>
            </div>
            <div class="col-md-4">
                <label for="email" class="form-label"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Correo Electrónico" required>
            </div>
        </div>

        <!-- Campo adicional para Alergias -->
        <div class="mb-3">
            <label for="allergies" class="form-label"><i class="fas fa-notes-medical"></i> Alergias</label>
            <textarea class="form-control" id="allergies" name="allergies" rows="3" placeholder="Describa las alergias (si aplica)"></textarea>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Registrar Paciente</button>
    </form>
</div>

        </main>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.9/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
