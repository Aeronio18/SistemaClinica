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
$cajeros = UserModel::getAllUsersByType('Cajero'); // Método para obtener cajeros
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
    <div class="col-md-3 col-sm-6">
        <div class="card text-bg-danger h-100 d-flex flex-column justify-content-between">
            <div class="card-body text-center">
                <i class="fas fa-cogs fa-3x mb-4"></i>
                <h5 class="card-title">CRUD Pacientes</h5>
                <p class="card-text">Gestión de pacientes registrados.</p>
            </div>
            <div class="card-footer bg-transparent border-0 text-center">
                <a href="crud_pacientes.php" class="btn btn-light btn-sm">Ver más</a>
            </div>
        </div>
    </div>
</div>';

include '../templates/dashboard_layout.php';
?>
