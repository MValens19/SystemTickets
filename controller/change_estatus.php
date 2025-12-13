<?php
// api/cambiar_estado.php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin'])) {
    echo json_encode(['success' => false]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id_ticket'] ?? 0;
$estado = $data['estado'] ?? '';

if ($id && in_array($estado, ['Pendiente','En proceso','Resuelto','Cerrado'])) {
    $sql = "UPDATE tickets SET estado = ? WHERE id_ticket = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$estado, $id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>