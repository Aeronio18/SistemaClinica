<?php
require_once('../config/database.php'); // Asegúrate de que el path sea correcto
require_once('../models/PacienteModel.php');

header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

if (isset($_GET['id'])) {
    $codigo_unico = $_GET['id']; // Aquí asumimos que el código único viene en el parámetro 'id'
    $paciente = PacienteModel::obtenerPacientePorCodigoUnico($codigo_unico);

    if ($paciente) {
        echo json_encode(['success' => true, 'nombre' => $paciente['nombre'], 'telefono' => $paciente['telefono']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Paciente no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Falta el parámetro de código único']);
}

