<?php
require_once('../libs/fpdf/fpdf.php'); // Ajusta la ruta según tu estructura
require_once('QRHelper.php'); // Asegúrate de tener el archivo QRHelper.php

class PDFHelper extends FPDF {
    public static function generatePatientCard($fullName, $birthDate, $age, $gender, $occupation, $contactNumber, $email, $allergies, $codigoUnico, $qrFile) {
        // Crear el objeto PDF
        $pdf = new self();
        
        // Agregar página
        $pdf->AddPage();
        
        // Configurar título y formato
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Tarjeta del Paciente', 0, 1, 'C');
        
        // Insertar el código QR
        if (file_exists($qrFile)) {
            $qrX = 140; // Posición X del QR
            $qrY = 30; // Posición Y del QR
            $qrSize = 50; // Tamaño del QR
            $pdf->Image($qrFile, $qrX, $qrY, $qrSize, $qrSize);
        } else {
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->Cell(0, 10, 'Error: No se pudo cargar el código QR.', 0, 1, 'C');
        }

        // Información del paciente
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(10);

        $dataX = 10; // Posición inicial X del texto
        $dataY = 30; // Posición inicial Y del texto
        $lineHeight = 8; // Altura entre líneas

        $fields = [
            "Nombre" => $fullName,
            "Fecha de Nacimiento" => $birthDate,
            "Edad" => $age,
            "Género" => $gender,
            "Ocupación" => $occupation,
            "Número de Contacto" => $contactNumber,
            "Correo" => $email,
            "Alergias" => $allergies,
            "Código Único" => $codigoUnico
        ];

        foreach ($fields as $label => $value) {
            $pdf->SetXY($dataX, $dataY);
            $pdf->Cell(0, $lineHeight, mb_convert_encoding("$label: $value", 'ISO-8859-1', 'UTF-8'), 0, 1);
            $dataY += $lineHeight;
        }

        // Guardar el PDF en un archivo
        $outputFile = '../pdfs/tarjeta_paciente_' . $codigoUnico . '.pdf';
        $pdf->Output('F', $outputFile);
        
        return $outputFile; // Retorna la ruta del PDF generado
    }
}
?>
