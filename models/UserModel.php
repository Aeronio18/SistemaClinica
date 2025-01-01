<?php
require_once('../config/database.php'); // Asegúrate de incluir el archivo de conexión a la base de datos

class UserModel {

    // Método para obtener un usuario por nombre de usuario
    public static function getUserByUsername($username) {
        // Conectar a la base de datos
        $db = Database::getConnection();
        
        // Consulta SQL para buscar al usuario
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ?");
        $stmt->bind_param("s", $username); // Vinculamos el parámetro de la consulta
        $stmt->execute();
        
        // Ejecutamos la consulta y obtenemos el resultado
        $result = $stmt->get_result();
        
        // Si encontramos un usuario, lo retornamos
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // No se encontró el usuario
        }
    }

    // Método para obtener todos los usuarios (por si lo necesitas en el futuro)
    public static function getAllUsers() {
        $db = Database::getConnection();
        $result = $db->query("SELECT * FROM usuarios");
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Método para obtener usuarios por tipo de usuario (Administrador o Cajero)
    public static function getUsersByRole($role) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE tipo_usuario = ?");
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
