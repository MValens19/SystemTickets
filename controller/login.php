<?php
// auth.php
session_start();
require_once '../config/db.php';  // Tu archivo de conexión PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($usuario) || empty($password)) {
        header("Location: ../views/login.php?error=campos");
        exit;
    }

    try {
        // Consulta directa (contraseñas sin cifrar, como tienes ahora)
        $sql = "SELECT id_usuario, nombre, area, rol FROM usuarios 
                WHERE usuario = ? AND password = ? AND activo = 1 
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario, $password]);
        $user = $stmt->fetch();

        if ($user) {
            // Login exitoso → creamos sesión
            $_SESSION['loggedin'] = true;
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['area'] = $user['area'];
            $_SESSION['rol'] = $user['rol'];

            // Redirigir según rol
            if ($user['rol'] === 'admin' || $user['rol'] === 'tecnico') {
                header("Location: ../index.php");        // Panel de técnicos
            } else {
                header("Location: ../views/requester/ticket.php");      // Portal de empleados
            }
            exit;
        } else {
            // Credenciales incorrectas
            header("Location: ../views/login.php?error=credenciales");
            exit;
        }
    } catch (Exception $e) {
        // Error de base de datos
        header("Location: ../views/login.php?error=servidor");
        exit;
    }
} else {
    // Acceso directo no permitido
    header("Location: ../views/login.php");
    exit;
}
?>