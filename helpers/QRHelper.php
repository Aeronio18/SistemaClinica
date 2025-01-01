<?php
// Asegúrate de tener la librería QRCode en tu proyecto
require_once('../libs/phpqrcode/qrlib.php'); // Ajusta la ruta según tu estructura

class QRHelper {
    public static function generateQRCode($text) {
        // Ruta donde se guardará el archivo QR generado
        $filePath = '../qr_codes/' . uniqid() . '.png';
        
        // Genera el código QR y lo guarda en la ruta especificada
        QRcode::png($text, $filePath, QR_ECLEVEL_L, 10);
        
        return $filePath; // Retorna la ruta del QR generado
    }
}
?>
