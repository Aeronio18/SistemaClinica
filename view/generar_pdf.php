<?php
require_once('../models/PacienteModel.php');
require_once('../helpers/PDFHelper.php');
require_once('../libs/phpqrcode/qrlib.php'); // Librería para generar el código QR

// Verifica si el parámetro "id" está presente en la URL
if (isset($_GET['id'])) {
    $idPaciente = $_GET['id'];

    // Obtener la información del paciente desde la base de datos
    $paciente = PacienteModel::getPacienteById($idPaciente);

    if ($paciente) {
        // Datos del paciente
        $fullName = $paciente['full_name'];
        $birthDate = $paciente['birth_date'];
        $age = $paciente['age'];
        $gender = $paciente['gender'];
        $occupation = $paciente['occupation'];
        $contactNumber = $paciente['contact_number'];
        $email = $paciente['email'];
        $codigoUnico = $paciente['codigo_unico'];

        // Crear el código QR para el paciente
        $qrData = $codigoUnico;
        $qrFile = '../qr_codes/qr_' . $codigoUnico . '.png';
        QRcode::png($qrData, $qrFile, 'L', 4, 4); // Genera el QR y lo guarda como un archivo

        // Generar el PDF usando el PDFHelper
        $pdfFile = PDFHelper::generatePatientCard(
            $fullName, 
            $birthDate, 
            $age, 
            $gender, 
            $occupation, 
            $contactNumber, 
            $email, 
            $codigoUnico, 
            $qrFile
        );

        // Forzar la descarga del PDF generado
        if (file_exists($pdfFile)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($pdfFile) . '"');
            readfile($pdfFile);
            exit;
        } else {
            echo "Error al generar el archivo PDF.";
        }

        // Eliminar el archivo QR después de la descarga
        unlink($qrFile);
    } else {
        echo "Paciente no encontrado.";
    }
} else {
    echo "ID de paciente no proporcionado.";
}
?>
