<?php
require_once('../config/database.php');

class PacienteModel {

    // Obtener todos los pacientes
    public static function getAllPacientes() {
        $db = Database::getConnection();
        $result = $db->query("SELECT * FROM pacientes");
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener paciente por ID
    public static function getPacienteById($idPaciente) {
        $db = Database::getConnection();
        
        // Prevenir inyecciones SQL usando consultas preparadas
        $query = "SELECT * FROM pacientes WHERE id = ?";
        
        // Preparar y ejecutar la consulta
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $idPaciente); // "i" es para un parámetro entero
        $stmt->execute();
        
        // Obtener el resultado
        $result = $stmt->get_result();
        $paciente = $result->fetch_assoc();
        
        // Retornar el paciente o null si no se encuentra
        return $paciente ? $paciente : null;
    }

   // Método para eliminar paciente
   public static function eliminarPaciente($idPaciente) {
    $db = Database::getConnection();
    $stmt = $db->prepare("DELETE FROM pacientes WHERE id = ?");
    $stmt->bind_param("i", $idPaciente); // "i" para entero
    $stmt->execute();
}
public static function obtenerPacientePorCodigoUnico($codigo_unico) {
    // Consulta SQL
    $query = "SELECT full_name AS nombre, contact_number AS telefono FROM pacientes WHERE codigo_unico = ?";
    
    // Ejecutar la consulta y retornar el primer resultado
    $result = Database::fetchResults($query, [$codigo_unico]);
    
    // Verificar si se encontró un paciente
    return !empty($result) ? $result[0] : null;
}
    
}
?>
