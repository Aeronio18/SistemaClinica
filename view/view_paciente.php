<?php
session_start();
require_once('../models/PacienteModel.php');
require_once('../models/VisitaModel.php');

// Verifica si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Verificar si se pasó un ID de paciente en la URL
if (!isset($_GET['id'])) {
    header('Location: dashboard.php'); // Redirigir si no se pasa un ID
    exit();
}

// Obtener el ID del paciente
$pacienteId = $_GET['id'];

// Obtener los detalles del paciente desde la base de datos
$paciente = PacienteModel::getPacienteById($pacienteId);

// Verificar si el paciente existe
if (!$paciente) {
    echo 'Paciente no encontrado.';
    exit();
}

// Obtener el historial de visitas del paciente
$visitas = VisitaModel::getVisitasCountByPaciente($pacienteId);

// Mostrar los detalles del paciente
$pageTitle = "Ver Información del Paciente: " . htmlspecialchars($paciente['full_name']);
$content = '
<h2>Información del Paciente: ' . htmlspecialchars($paciente['full_name']) . '</h2>
<p><strong>Email:</strong> ' . htmlspecialchars($paciente['email']) . '</p>
<p><strong>Teléfono de contacto:</strong> ' . htmlspecialchars($paciente['contact_number']) . '</p>
<p><strong>Fecha de nacimiento:</strong> ' . htmlspecialchars($paciente['birth_date']) . '</p>
<p><strong>Edad:</strong> ' . htmlspecialchars($paciente['age']) . '</p>
<p><strong>Género:</strong> ' . htmlspecialchars($paciente['gender']) . '</p>
<p><strong>Ocupación:</strong> ' . htmlspecialchars($paciente['occupation']) . '</p>
<p><strong>Alergias:</strong> ' . htmlspecialchars($paciente['allergies']) . '</p>

<h4>Historial de Visitas</h4>
<p>Número de visitas: ' . $visitas . '</p>

<!-- Botón de Volver usando JavaScript para regresar a la página anterior -->
<button onclick="window.history.back()" class="btn btn-secondary">Volver</button>
';

include '../templates/dashboard_layout.php';
?>
