<?php
// crear_ticket.php
session_start();
require_once '../config/db.php';  // Tu conexión PDO

// Protección: solo usuarios logueados
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: ../views/login.php");
    exit;
}

// Verificar que venga por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/requester/ticket.php");
    exit;
}

$titulo       = trim($_POST['titulo'] ?? '');
$descripcion  = $_POST['descripcion'] ?? '';   // HTML del editor TipTap (con imágenes en base64)
$prioridad    = $_POST['prioridad'] ?? 'Normal';
$id_usuario   = $_SESSION['id_usuario'];
$area         = $_SESSION['area'] ?? 'Sin área';

// Validación básica
if (empty($titulo) || empty($descripcion)) {
    header("Location: ../views/requester/ticket.php?error=campos");
    exit;
}

// Validar prioridad permitida
$prioridades_validas = ['Normal', 'Alta', 'Urgente'];
if (!in_array($prioridad, $prioridades_validas)) {
    $prioridad = 'Normal';
}

try {
    $sql = "INSERT INTO tickets 
            (titulo, descripcion, id_usuario, area, prioridad, estado, fecha_creacion) 
            VALUES 
            (?, ?, ?, ?, ?, 'Pendiente', NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $descripcion, $id_usuario, $area, $prioridad]);

    // Redirigir con éxito
    header("Location: ../views/requester/ticket.php?success=1");
    exit;

} catch (PDOException $e) {
    // En producción, registra el error en un log (no lo muestres al usuario)
    error_log("Error al crear ticket: " . $e->getMessage());
    header("Location: ../views/requester/ticket.php?error=bd");
    exit;
}
?>