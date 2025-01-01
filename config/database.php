<?php
class Database {
    private static $connection;

    // Método para obtener la conexión a la base de datos
    public static function getConnection() {
        if (!self::$connection) {
            // Parámetros de conexión a la base de datos
            // $host = 'localhost';  // Cambia por el host de tu base de datos
            // $username = 'u327767040_tetsa';   // Cambia por tu nombre de usuario de base de datos
            // $password = 'Z0b5tPQ4Fp^';       // Cambia por tu contraseña de base de datos
            // $dbname = 'u327767040_dclinica'; // Cambia por el nombre de tu base de datos
            //localhost conexion
            $host = 'localhost';  // Cambia por el host de tu base de datos
            $username = 'u327767040_Aeronio';   // Cambia por tu nombre de usuario de base de datos
            $password = 'Z0b5tPQ4Fp^';       // Cambia por tu contraseña de base de datos
            $dbname = 'clinica_db'; // Cambia por el nombre de tu base de datos

            // Crear conexión
            self::$connection = new mysqli($host, $username, $password, $dbname);

            // Comprobar si hubo error en la conexión
            if (self::$connection->connect_error) {
                die("Conexión fallida: " . self::$connection->connect_error);
            }
        }
        return self::$connection;
    }

    // Método para ejecutar una consulta (INSERT, UPDATE, DELETE)
    public static function executeQuery($query, $params = []) {
        $conn = self::getConnection();

        // Preparar la consulta
        $stmt = $conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }

        // Si hay parámetros, los vinculamos a la consulta
        if (!empty($params)) {
            $types = ''; // Tipo de los parámetros (s: string, i: integer, d: double, b: blob)
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_string($param)) {
                    $types .= 's';
                } elseif (is_double($param)) {
                    $types .= 'd';
                } elseif (is_resource($param)) {
                    $types .= 'b';
                }
            }
            $stmt->bind_param($types, ...$params);
        }

        // Ejecutar la consulta
        return $stmt->execute();
    }

    // Método para obtener resultados de una consulta SELECT
    public static function fetchResults($query, $params = []) {
        $conn = self::getConnection();

        // Preparar la consulta
        $stmt = $conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }

        // Si hay parámetros, los vinculamos a la consulta
        if (!empty($params)) {
            $types = ''; // Tipo de los parámetros (s: string, i: integer, d: double, b: blob)
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_string($param)) {
                    $types .= 's';
                } elseif (is_double($param)) {
                    $types .= 'd';
                } elseif (is_resource($param)) {
                    $types .= 'b';
                }
            }
            $stmt->bind_param($types, ...$params);
        }

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); // Retorna los resultados como un arreglo asociativo
        
    }
}
?>
