<?php
require_once('../config/database.php');

// Clase PatientModel
class PatientModel {

    // Verificar si el paciente ya existe en la base de datos
    public static function checkExistingPatient($email, $contactNumber) {
        // Obtener la conexión a la base de datos
        $conn = Database::getConnection();
        
        try {
            // Consulta para verificar si ya existe un paciente con el mismo correo o número de contacto
            $query = "SELECT 1 FROM pacientes WHERE email = ? OR contact_number = ? LIMIT 1";
            
            // Preparar la consulta
            $stmt = $conn->prepare($query);
            
            // Vincular los parámetros (email y número de contacto)
            $stmt->bind_param('ss', $email, $contactNumber);
            
            // Ejecutar la consulta
            $stmt->execute();
            
            // Comprobar si hay resultados
            $stmt->store_result();
            
            // Si existe al menos una fila, el paciente ya está registrado
            return $stmt->num_rows > 0;
        } catch (Exception $e) {
            // Manejo de errores
            error_log("Error al verificar paciente existente: " . $e->getMessage());
            return false; // Devuelve false si ocurre un error
        }
    }

    // Método para insertar un nuevo paciente
    public static function createPatient($fullName, $birthDate, $age, $gender, $occupation, $contactNumber, $email, $codigoUnico, $allergies) {
        // Insertar el nuevo paciente
        $query = "INSERT INTO pacientes (full_name, birth_date, age, gender, occupation, contact_number, email, codigo_unico, allergies) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $conn = Database::getConnection();
        try {
            // Preparar la consulta
            $stmt = $conn->prepare($query);

            // Vincular los parámetros
            $stmt->bind_param('ssissssss', $fullName, $birthDate, $age, $gender, $occupation, $contactNumber, $email, $codigoUnico, $allergies);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Retorna el ID del nuevo paciente
                return $conn->insert_id;
            } else {
                // En caso de error, puedes devolver false o un mensaje específico
                return false;
            }
        } catch (Exception $e) {
            // Manejo de errores
            error_log("Error al crear paciente: " . $e->getMessage());
            return false;
        }
    }
}
?>
