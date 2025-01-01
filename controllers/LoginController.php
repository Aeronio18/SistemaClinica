<?php
session_start();
require_once('../models/UserModel.php'); // Incluir el modelo de usuarios

// Función para verificar las credenciales del usuario
function login($username, $password) {
    // Consultar la base de datos para verificar las credenciales
    $user = UserModel::getUserByUsername($username);
    
    if ($user) {
        // Si las contraseñas son iguales, continuar con el inicio de sesión
        if ($password === $user['password']) {
            // Iniciar sesión y almacenar el tipo de usuario
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['tipo_usuario'];
            $_SESSION['id'] = $user['id'];
            
            // Redirigir al dashboard correspondiente basado en el rol del usuario
            if ($user['tipo_usuario'] === 'Administrador') {
                header('Location: ../view/dashboard_admin.php'); // Dashboard del Admin
            } elseif ($user['tipo_usuario'] === 'Cajero') {
                header('Location: ../view/dashboard_cajero.php'); // Dashboard del Cajero
            } else {
                // Redirigir al dashboard por defecto o una página de error si no es un rol reconocido
                header('Location: ../view/dashboard.php');
            }
            exit;
        } else {
            // Si las credenciales no coinciden
            header('Location: ../view/login.php?error=invalid_credentials');
            exit;
        }
    } else {
        // Si el usuario no existe
        header('Location: ../view/login.php?error=invalid_credentials');
        exit;
    }
}

// Comprobar si el formulario de login fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs to prevent XSS
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    
    if (empty($username) || empty($password)) {
        // Si algún campo está vacío
        header('Location: ../view/login.php?error=empty_fields');
        exit;
    }
    
    // Llamada a la función de login
    login($username, $password);
}
?>
