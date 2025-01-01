<?php
require_once('../config/database.php');

class VisitaModel {

    // Método para obtener las visitas de hoy
    public static function getVisitasHoy() {
        $db = Database::getConnection();
        $today = date('Y-m-d');
        $stmt = $db->prepare("SELECT * FROM visitas WHERE DATE(fecha_visita) = ?");
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Método para obtener el número de visitas de un paciente
    public static function getVisitasCountByPaciente($pacienteId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) AS visitas_count FROM visitas WHERE paciente_id = ?");
        $stmt->bind_param("i", $pacienteId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Retorna el número de visitas
        $row = $result->fetch_assoc();
        return $row['visitas_count'];
    }

    // Método para registrar una nueva visita
    public static function registrarVisita($paciente_id, $user_id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO visitas (paciente_id, registrado_por) VALUES (?, ?)");
        $stmt->bind_param("ii", $paciente_id, $user_id);
        $stmt->execute();
        return $stmt->insert_id;  // Retorna el ID de la visita insertada
    }    
}
?>
