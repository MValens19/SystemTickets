<?php
// api/all_tickets.php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

// Solo admin y técnico pueden ver todos los tickets
$roles_permitidos = ['admin', 'tecnico'];
if (!in_array($_SESSION['rol'], $roles_permitidos)) {
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}

// Consulta completa con JOIN para nombre del usuario y asignado
$sql = "SELECT 
            t.id_ticket,
            t.titulo,
            t.descripcion,
            u.nombre AS usuario_nombre,
            t.area,
            t.prioridad,
            t.estado,
            a.nombre AS asignado_nombre,
            t.fecha_creacion
        FROM tickets t
        LEFT JOIN usuarios u ON t.id_usuario = u.id_usuario
        LEFT JOIN usuarios a ON t.id_asignado = a.id_usuario
        ORDER BY t.fecha_creacion DESC";

$stmt = $pdo->query($sql);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Formatear fecha y nombre asignado
foreach ($tickets as &$t) {
    $t['fecha_formateada'] = date('d/m/Y H:i', strtotime($t['fecha_creacion']));
    $t['asignado_nombre'] = $t['asignado_nombre'] ?? '—';
}

echo json_encode($tickets);
exit;
?>