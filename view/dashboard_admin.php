<?php
session_start();
require_once('../models/UserModel.php');
require_once('../models/PacienteModel.php');
require_once('../models/VisitaModel.php');

// Verifica si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Obtener el número de cajeros
$cajeros = UserModel::getUsersByRole('Cajero'); // Llama al método para obtener todos los cajeros
$cajerosCount = count($cajeros);

// Obtener el número de pacientes registrados
$pacientes = PacienteModel::getAllPacientes();
$pacientesCount = count($pacientes);

// Obtener las visitas de hoy
$visitas = VisitaModel::getVisitasHoy();
$visitasCount = count($visitas);

// Pasar los datos a la vista
$pageTitle = "Dashboard Admin";
$content = '
<div class="row g-4">
    <!-- Cajeros -->
    <div class="col-md-3 col-sm-6">
        <div class="card text-bg-primary h-100 d-flex flex-column justify-content-between">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-4"></i>
                <h5 class="card-title">Cajeros</h5>
                <p class="card-text">Gestión de todos los cajeros.</p>
                <p>' . $cajerosCount . ' Cajeros</p>
            </div>
            <div class="card-footer bg-transparent border-0 text-center">
                <a href="#" class="btn btn-light btn-sm">Ver más</a>
            </div>
        </div>
    </div>
    
    <!-- Pacientes -->
    <div class="col-md-3 col-sm-6">
        <div class="card text-bg-success h-100 d-flex flex-column justify-content-between">
            <div class="card-body text-center">
                <i class="fas fa-user fa-3x mb-4"></i>
                <h5 class="card-title">Pacientes</h5>
                <p class="card-text">Pacientes registrados en la clínica.</p>
                <p>' . $pacientesCount . ' Pacientes</p>
            </div>
            <div class="card-footer bg-transparent border-0 text-center">
                <a href="#" class="btn btn-light btn-sm">Ver más</a>
            </div>
        </div>
    </div>
    
    <!-- Visitas -->
    <div class="col-md-3 col-sm-6">
        <div class="card text-bg-warning h-100 d-flex flex-column justify-content-between">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-3x mb-4"></i>
                <h5 class="card-title">Visitas Hoy</h5>
                <p class="card-text">Visitas registradas hoy.</p>
                <p>' . $visitasCount . ' Visitas</p>
            </div>
            <div class="card-footer bg-transparent border-0 text-center">
                <a href="#" class="btn btn-light btn-sm">Ver más</a>
            </div>
        </div>
    </div>

    <!-- Registrar Pacientes -->
    <div class="col-md-3 col-sm-6">
        <div class="card text-bg-info h-100 d-flex flex-column justify-content-between"> <!-- Cambié el color a azul -->
            <div class="card-body text-center">
                <i class="fas fa-user-plus fa-3x mb-4"></i> <!-- Cambié el icono -->
                <h5 class="card-title">Registrar Pacientes</h5>
                <p class="card-text">Agregar nuevos pacientes.</p>
            </div>
            <div class="card-footer bg-transparent border-0 text-center">
                <a href="crud_pacientes.php" class="btn btn-light btn-sm">Iniciar Registro</a> <!-- Cambié el texto del botón -->
            </div>
        </div>
    </div>
</div>

<!-- Detalles adicionales -->
<div class="row mt-4">
    <!-- Visitas de hoy -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Visitas Registradas Hoy</h5>
            </div>
            <div class="card-body">
                <ul>';

foreach ($visitas as $visita) {
    $content .= '
                    <li>Visita de ' . htmlspecialchars($visita['paciente_id']) . ' a las ' . htmlspecialchars($visita['fecha_visita']) . '</li>';
}

$content .= '
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Pacientes Registrados -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Pacientes Registrados</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Contacto</th>
                            <th>Número de Visitas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';

foreach ($pacientes as $paciente) {
    // Obtener número de visitas del paciente
    $numVisitas = VisitaModel::getVisitasCountByPaciente($paciente['id']); // Método para obtener visitas del paciente
    $content .= '
                        <tr>
                            <td>' . htmlspecialchars($paciente['full_name']) . '</td>
                            <td>' . htmlspecialchars($paciente['email']) . '</td>
                            <td>' . htmlspecialchars($paciente['contact_number']) . '</td>
                            <td>' . $numVisitas . '</td>
                            <td>
    <!-- Ver Información del Paciente -->
    <a href="view_paciente.php?id=' . $paciente['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Información" class="btn btn-primary btn-sm me-2">
        <i class="fas fa-eye"></i>
    </a>
    
    <!-- Registrar Visita -->
    <a href="registrar_visita.php?id=' . $paciente['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Registrar Visita" class="btn btn-info btn-sm me-2">
        <i class="fas fa-calendar-plus"></i>
    </a>
    
    <!-- Editar Paciente -->
    <a href="edit_paciente.php?id=' . $paciente['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Paciente" class="btn btn-warning btn-sm me-2">
        <i class="fas fa-user-edit"></i>
    </a>
    
    <!-- Eliminar Paciente -->
    <a href="delete_paciente.php?id=' . $paciente['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar Paciente" onclick="return confirm(\'¿Estás seguro de eliminar este paciente?\')" class="btn btn-danger btn-sm me-2">
        <i class="fas fa-trash-alt"></i>
    </a>
    
    <!-- Descargar Tarjeta del Paciente -->
    <a href="generar_pdf.php?id=' . $paciente['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Descargar Tarjeta" class="btn btn-secondary btn-sm me-2">
        <i class="fas fa-download"></i>
    </a>
</td>
                        </tr>

                        <!-- Modal para escanear código QR -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="qrModalLabel">Escanear Código QR</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <button id="start-camera" class="btn btn-primary w-100">Iniciar Cámara</button>
          <video id="video" width="100%" style="display:none;"></video>
          <canvas id="canvas" width="100%" style="display:none;"></canvas>
          <p id="scan-result" class="mt-2"></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" id="register-visit" class="btn btn-success" disabled>Registrar Visita</button>
      </div>
    </div>
  </div>
</div>';
                        
}

$content .= '
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>';

include '../templates/dashboard_layout.php';
?>
