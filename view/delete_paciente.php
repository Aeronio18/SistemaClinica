<?php
require_once('../models/PacienteModel.php');

// Verificar si el ID del paciente está presente en la URL
if (isset($_GET['id'])) {
    $pacienteId = $_GET['id'];

    // Eliminar el paciente
    PacienteModel::eliminarPaciente($pacienteId);

    // Redirigir de vuelta al dashboard
    header('Location: dashboard_admin.php');
    exit();
}
?>
