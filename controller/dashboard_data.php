<?php
// api/dashboard_data.php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

// Estadísticas generales
$total_tickets = $pdo->query("SELECT COUNT(*) FROM tickets")->fetchColumn();
$urgentes_hoy = $pdo->query("SELECT COUNT(*) FROM tickets WHERE prioridad = 'Urgente' AND DATE(fecha_creacion) = CURDATE()")->fetchColumn();
$pendientes = $pdo->query("SELECT COUNT(*) FROM tickets WHERE estado = 'Pendiente'")->fetchColumn();

// Porcentaje resueltos este mes
$mes_actual = date('Y-m');
$resueltos_mes = $pdo->query("SELECT COUNT(*) FROM tickets WHERE estado = 'Resuelto' AND DATE_FORMAT(fecha_creacion, '%Y-%m') = '$mes_actual'")->fetchColumn();
$total_mes = $pdo->query("SELECT COUNT(*) FROM tickets WHERE DATE_FORMAT(fecha_creacion, '%Y-%m') = '$mes_actual'")->fetchColumn();
$porcentaje_resueltos = $total_mes > 0 ? round(($resueltos_mes / $total_mes) * 100) : 0;

// Tickets críticos hoy (Urgente + Alta)
$sql_criticos = "SELECT t.id_ticket, t.titulo, u.nombre AS usuario_nombre, t.prioridad, t.estado, t.fecha_creacion
                 FROM tickets t
                 LEFT JOIN usuarios u ON t.id_usuario = u.id_usuario
                 WHERE t.prioridad IN ('Urgente', 'Alta')
                 AND DATE(t.fecha_creacion) = CURDATE()
                 ORDER BY t.prioridad DESC, t.fecha_creacion DESC
                 LIMIT 10";
$stmt = $pdo->query($sql_criticos);
$criticos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Formatear fecha
foreach ($criticos as &$c) {
    $c['tiempo'] = tiempo_transcurrido($c['fecha_creacion']);
}

function tiempo_transcurrido($fecha) {
    $ahora = new DateTime();
    $creado = new DateTime($fecha);
    $diff = $ahora->diff($creado);

    if ($diff->days > 0) return "hace {$diff->days} día" . ($diff->days > 1 ? 's' : '');
    if ($diff->h > 0) return "hace {$diff->h} hora" . ($diff->h > 1 ? 's' : '');
    if ($diff->i > 0) return "hace {$diff->i} minuto" . ($diff->i > 1 ? 's' : '');
    return "hace unos segundos";
}

echo json_encode([
    'total_tickets' => $total_tickets,
    'urgentes_hoy' => $urgentes_hoy,
    'pendientes' => $pendientes,
    'porcentaje_resueltos' => $porcentaje_resueltos,
    'criticos' => $criticos
]);
exit;
?>