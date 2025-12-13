<?php
// api/all_users.php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$roles_permitidos = ['admin', 'tecnico'];
if (!in_array($_SESSION['rol'], $roles_permitidos)) {
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}

// Consulta todos los usuarios
$sql = "SELECT nombre, puesto, area, usuario, rol 
        FROM usuarios 
        WHERE activo = 1 
        ORDER BY nombre ASC";
$stmt = $pdo->query($sql);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Formatear rol bonito
foreach ($usuarios as &$u) {
    $rol_map = [
        'admin' => 'Administrador',
        'tecnico' => 'Técnico',
        'usuario' => 'Usuario'
    ];
    $u['rol'] = $rol_map[$u['rol']] ?? $u['rol'];
}

echo json_encode($usuarios);
exit;
?>