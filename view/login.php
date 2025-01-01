<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'Iniciar sesión'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f8f9; /* Fondo suave, adecuado para una clínica */
            font-family: 'Arial', sans-serif;
        }

        .form-floating .form-control {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }

        .form-floating label {
            color: #6c757d;
            font-size: 1rem;
            pointer-events: none;
            transition: all 0.2s ease-in-out;
        }

        .form-floating .form-control:focus ~ label,
        .form-floating .form-control:not(:placeholder-shown) ~ label {
            transform: translateY(-1.5rem);
            font-size: 0.875rem;
            color: #007bff;
        }

        .btn-primary {
            background-color: #28a745; /* Verde, simbolizando salud */
            border: none;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .alert {
            margin-bottom: 1rem;
        }

        /* Estilos específicos para la imagen */
        .login-logo {
            max-width: 100%;
            height: auto;
        }

        .login-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .login-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        @media (max-width: 768px) {
            .login-logo {
                max-width: 80%;
            }

            .login-container {
                margin-top: 30px;
            }

            .form-floating label {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<?php
// Definir consejos de salud
$health_tips = [
    "Recuerda mantenerte hidratado y tomar agua durante el día.",
    "Realiza chequeos médicos periódicos para prevenir enfermedades.",
    "Come una dieta balanceada y realiza actividad física regularmente.",
    "Mantén un horario de sueño adecuado para descansar bien.",
    "No olvides tomar tus medicamentos según lo indicado por tu médico."
];

// Seleccionar un consejo aleatorio
$random_tip = $health_tips[array_rand($health_tips)];
?>

<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="../img/logo.png" class="login-logo img-fluid" alt="Logo Clínica">
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <div class="login-card p-4">
                    <!-- Título -->
                    <h2 class="text-center mb-4">Iniciar sesión</h2>

                    <!-- Consejo de salud -->
                    <div class="p-3 mb-4 text-center" style="background-color: rgba(40, 167, 69, 0.1); border: 1px solid #28a745; border-radius: 8px;">
                        <span style="font-style: italic; color: #495057;">
                            <strong>Consejo de salud:</strong> <?php echo $random_tip; ?>
                        </span>
                    </div>

                    <form id="loginForm" action="../controllers/LoginController.php" method="POST">
                        <!-- Username input -->
                        <div class="form-floating mb-4">
                            <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="Nombre de usuario" required>
                            <label for="username">Nombre de usuario</label>
                        </div>

                        <!-- Password input -->
                        <div class="form-floating mb-4">
                            <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Contraseña" required>
                            <label for="password">Contraseña</label>
                        </div>

                        <!-- Submit button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Iniciar sesión</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.all.min.js"></script>

<?php if (isset($_GET['error'])): ?>
    <script>
        let errorMessage = '';
        switch ('<?php echo $_GET['error']; ?>') {
            case 'empty_fields':
                errorMessage = 'Por favor, completa todos los campos.';
                break;
            case 'invalid_credentials':
                errorMessage = 'Credenciales inválidas. Verifica tu nombre de usuario y contraseña.';
                break;
        }
        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: '¡Oops!',
                text: errorMessage
            });
        }
    </script>
<?php endif; ?>

</body>
</html>
