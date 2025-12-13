<?php
// api/tickets_mes_actual.php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin'])) exit(json_encode([]));

$mes = date('Y-m');
$sql = "SELECT 
            DATE_FORMAT(t.fecha_creacion, '%Y-%m-%d') AS fecha,
            t.titulo,
            t.area,
            t.prioridad,
            CONCAT(TIMESTAMPDIFF(HOUR, t.fecha_creacion, t.fecha_resolucion), ' h') AS tiempo,
            u.nombre AS tecnico
        FROM tickets t
        LEFT JOIN usuarios u ON t.id_asignado = u.id_usuario
        WHERE DATE_FORMAT(t.fecha_creacion, '%Y-%m') = ?
        ORDER BY t.fecha_creacion DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$mes]);
$data = $stmt->fetchAll(PDO::FETCH_NUM);

echo json_encode($data);
?>