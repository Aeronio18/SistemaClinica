<?php
require_once('../models/PacienteModel.php');
require_once('../helpers/PDFHelper.php');
require_once('../libs/phpqrcode/qrlib.php'); // Librería para generar el código QR

// Verificar si el parámetro "id" está presente en la URL
if (!isset($_GET['id'])) {
    die("ID de paciente no proporcionado.");
}

$idPaciente = $_GET['id'];

// Obtener la información del paciente desde la base de datos
$paciente = PacienteModel::getPacienteById($idPaciente);

if (!$paciente) {
    die("Paciente no encontrado.");
}

// Datos del paciente
$fullName = $paciente['full_name'];
$birthDate = $paciente['birth_date'];
$age = $paciente['age'];
$gender = $paciente['gender'];
$occupation = $paciente['occupation'];
$contactNumber = $paciente['contact_number'];
$email = $paciente['email'];
$allergies = $paciente['allergies'];
$codigoUnico = $paciente['codigo_unico'];

try {
    // Crear el código QR para el paciente
    $qrData = $codigoUnico;
    $qrFile = '../qr_codes/qr_' . $codigoUnico . '.png';
    QRcode::png($qrData, $qrFile, 'L', 4, 4); // Generar el QR y guardarlo como archivo

    // Generar el PDF usando el PDFHelper
    $pdfFile = PDFHelper::generatePatientCard(
        $fullName,
        $birthDate,
        $age,
        $gender,
        $occupation,
        $contactNumber,
        $email,
        $allergies,
        $codigoUnico,
        $qrFile
    );

    // Verificar y forzar la descarga del PDF generado
    if (file_exists($pdfFile)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($pdfFile) . '"');
        readfile($pdfFile);
    } else {
        throw new Exception("Error al generar el archivo PDF.");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    // Limpiar archivos temporales
    if (file_exists($qrFile)) {
        unlink($qrFile);
    }
}

exit;
?>
