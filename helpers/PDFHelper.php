<?php
require_once('../libs/fpdf/fpdf.php'); // Ajusta la ruta según tu estructura
require_once('QRHelper.php'); // Asegúrate de tener el archivo QRHelper.php

class PDFHelper extends FPDF {
    public static function generatePatientCard($fullName, $birthDate, $age, $gender, $occupation, $contactNumber, $email, $codigoUnico, $qrFile) {
        // Crear el objeto PDF
        $pdf = new self();
        
        // Agregar página
        $pdf->AddPage();
        
        // Configurar título y formato
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(200, 10, 'Tarjeta del Paciente', 0, 1, 'C');
        
        // Información del paciente
        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, mb_convert_encoding("Nombre: " . $fullName, 'ISO-8859-1', 'UTF-8'));  // Usar mb_convert_encoding()
        $pdf->Ln(6);
        $pdf->Cell(0, 10, mb_convert_encoding("Fecha de Nacimiento: " . $birthDate, 'ISO-8859-1', 'UTF-8'));  // Usar mb_convert_encoding()
        $pdf->Ln(6);
        $pdf->Cell(0, 10, "Edad: " . $age);
        $pdf->Ln(6);
        $pdf->Cell(0, 10, mb_convert_encoding("Género: " . $gender, 'ISO-8859-1', 'UTF-8'));  // Usar mb_convert_encoding()
        $pdf->Ln(6);
        $pdf->Cell(0, 10, mb_convert_encoding("Ocupación: " . $occupation, 'ISO-8859-1', 'UTF-8'));  // Usar mb_convert_encoding()
        $pdf->Ln(6);
        $pdf->Cell(0, 10, mb_convert_encoding("Número de Contacto: " . $contactNumber, 'ISO-8859-1', 'UTF-8'));  // Usar mb_convert_encoding()
        $pdf->Ln(6);
        $pdf->Cell(0, 10, mb_convert_encoding("Correo: " . $email, 'ISO-8859-1', 'UTF-8'));  // Usar mb_convert_encoding()
        $pdf->Ln(6);
        $pdf->Cell(0, 10, mb_convert_encoding("Código Único: " . $codigoUnico, 'ISO-8859-1', 'UTF-8'));  // Usar mb_convert_encoding()
        
        // Insertar el código QR (asegurándote de que la ruta es correcta)
        if (file_exists($qrFile)) {
            $pdf->Image($qrFile, 10, 80, 30, 30);
        } else {
            $pdf->Cell(0, 10, "Error: No se pudo cargar el código QR.");
        }
        
        // Guardar el PDF en un archivo
        $outputFile = '../pdfs/tarjeta_paciente_' . $codigoUnico . '.pdf';
        $pdf->Output('F', $outputFile);
        
        return $outputFile; // Retorna la ruta del PDF generado
    }
}
?>
