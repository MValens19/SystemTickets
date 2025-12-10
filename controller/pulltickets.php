<?php
// api/requester_data.php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$nombre = $_SESSION['nombre'] ?? 'Usuario';
$area = $_SESSION['area'] ?? 'Área';

// Obtener tickets del usuario
$sql = "SELECT id_ticket, titulo, prioridad, estado, fecha_creacion 
        FROM tickets 
        WHERE id_usuario = ? 
        ORDER BY fecha_creacion DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_usuario]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Formatear fecha para mostrar
foreach ($tickets as &$t) {
    $t['fecha_formateada'] = date('d/m/Y H:i', strtotime($t['fecha_creacion']));
}

echo json_encode([
    'nombre' => $nombre,
    'area' => $area,
    'tickets' => $tickets
]);
exit;
?>