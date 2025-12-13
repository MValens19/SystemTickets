<?php
session_start();

// Protección
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../views/login.php?error=sesion");
    exit;
}
$roles_permitidos = ['admin', 'tecnico'];
if (!in_array($_SESSION['rol'], $roles_permitidos, true)) {
    header("Location: ../../views/login.php?error=permiso");
    exit;
}
require_once '../../config/db.php'; // Tu conexión PDO
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reportes · Soporte TI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

  <style>
    /* Tu estilo original + mejoras */
    body{font-family:'Inter',sans-serif;background:#fff;color:#1f1f1f;margin:0;height:100vh;overflow:hidden;}
    .app-container{display:flex;height:100vh;}
    .sidebar{width:280px;background:#fafafa;border-right:1px solid #eaeaea;padding:1.5rem;overflow-y:auto;flex-shrink:0;transition:width .3s ease;}
    .sidebar.collapsed{width:0;padding:0;overflow:hidden;}
    .logo{font-weight:700;font-size:1.5rem;margin-bottom:2.5rem;display:flex;align-items:center;gap:12px;}
    .nav-item{display:flex;align-items:center;gap:14px;padding:12px 16px;border-radius:10px;cursor:pointer;font-weight:500;transition:.2s;color:inherit;text-decoration:none;}
    .nav-item:hover,.nav-item.active{background:#000;color:white;}
    .main-area{flex:1;display:flex;flex-direction:column;min-width:0;}
    .topbar{height:64px;background:#fff;border-bottom:1px solid #eaeaea;padding:0 2rem;display:flex;align-items:center;justify-content:flex-end;flex-shrink:0;}
    .toggle-btn{position:absolute;left:280px;top:0;height:64px;width:64px;background:transparent;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:left .3s ease;z-index:100;}
    .sidebar.collapsed ~ .main-area .toggle-btn{left:0;}
    .main-content{flex:1;overflow-y:auto;padding:3rem;}
    .page-title{font-size:2.5rem;font-weight:700;letter-spacing:-0.02em;margin-bottom:0.5rem;}
    .page-subtitle{color:#666;font-size:1.1rem;margin-bottom:2rem;}
    .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;margin-bottom:3rem;}
    .stat-card{background:#f7f7f7;padding:2rem;border-radius:16px;text-align:center;transition:.2s;box-shadow:0 4px 16px rgba(0,0,0,0.08);}
    .stat-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(0,0,0,0.12);}
    .stat-number{font-size:2.8rem;font-weight:700;margin-bottom:0.5rem;}
    .report-card{background:#fff;border:1px solid #eaeaea;border-radius:16px;padding:2rem;transition:.3s;cursor:pointer;box-shadow:0 4px 16px rgba(0,0,0,0.08);}
    .report-card:hover{transform:translateY(-8px);box-shadow:0 16px 40px rgba(0,0,0,0.15);border-color:#000;}
    .report-card h3{font-size:1.3rem;font-weight:600;margin-bottom:0.8rem;}
    .report-card p{font-size:1rem;color:#666;line-height:1.5;}
  </style>
</head>
<body>

<div class="app-container">
  <!-- Sidebar -->
  <?php include '../../components/sidebar.html'; ?>

  <div class="main-area">
    <?php include '../../components/ToggleSidebar.html'; ?>

    <div class="main-content">
      <h1 class="page-title">Reportes y estadísticas</h1>
      <p class="page-subtitle">Análisis completo del rendimiento del área de TI – <?php echo date('Y'); ?></p>

      <?php
      // Estadísticas reales
      $total_tickets = $pdo->query("SELECT COUNT(*) FROM tickets")->fetchColumn();
      $urgentes_hoy = $pdo->query("SELECT COUNT(*) FROM tickets WHERE prioridad = 'Urgente' AND DATE(fecha_creacion) = CURDATE()")->fetchColumn();
      $pendientes = $pdo->query("SELECT COUNT(*) FROM tickets WHERE estado != 'Resuelto'")->fetchColumn();

      // Resueltos este mes
      $mes = date('Y-m');
      $resueltos_mes = $pdo->query("SELECT COUNT(*) FROM tickets WHERE estado = 'Resuelto' AND DATE_FORMAT(fecha_creacion, '%Y-%m') = '$mes'")->fetchColumn();
      $total_mes = $pdo->query("SELECT COUNT(*) FROM tickets WHERE DATE_FORMAT(fecha_creacion, '%Y-%m') = '$mes'")->fetchColumn();
      $porcentaje = $total_mes > 0 ? round(($resueltos_mes / $total_mes) * 100) : 0;

      // Tiempo promedio (solo resueltos)
      $stmt = $pdo->query("SELECT AVG(TIMESTAMPDIFF(HOUR, fecha_creacion, fecha_resolucion)) AS avg_hours FROM tickets WHERE estado = 'Resuelto' AND fecha_resolucion IS NOT NULL");
      $avg_hours = round($stmt->fetchColumn(), 1) ?: '0';
      ?>

      <!-- Estadísticas -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-number"><?php echo $total_tickets; ?></div>
          <div>Tickets totales</div>
        </div>
        <div class="stat-card">
          <div class="stat-number text-danger"><?php echo $urgentes_hoy; ?></div>
          <div>Urgentes hoy</div>
        </div>
        <div class="stat-card">
          <div class="stat-number text-warning"><?php echo $pendientes; ?></div>
          <div>Pendientes</div>
        </div>
        <div class="stat-card">
          <div class="stat-number text-success"><?php echo $porcentaje; ?>%</div>
          <div>Resueltos este mes</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?php echo $avg_hours; ?> h</div>
          <div>Tiempo promedio</div>
        </div>
      </div>

      <!-- Reportes disponibles (clicables en futuro) -->
      <h2 style="font-size:1.5rem;font-weight:600;margin:3rem 0 1.5rem;">Reportes disponibles</h2>
      <div class="row g-4">
        <div class="col-md-6 col-lg-4"><div class="report-card"><h3>Tickets por área</h3><p>Distribución de incidencias por departamento</p></div></div>
        <div class="col-md-6 col-lg-4"><div class="report-card"><h3>Tiempo de resolución</h3><p>Promedio y mediana por prioridad</p></div></div>
        <div class="col-md-6 col-lg-4"><div class="report-card"><h3>Técnicos más activos</h3><p>Ranking de resolución y carga de trabajo</p></div></div>
        <div class="col-md-6 col-lg-4"><div class="report-card"><h3>Incidencias recurrentes</h3><p>Problemas que se repiten más de 3 veces</p></div></div>
        <div class="col-md-6 col-lg-4"><div class="report-card"><h3>SLA cumplimiento</h3><p>Porcentaje de tickets resueltos dentro del plazo</p></div></div>
        <div class="col-md-6 col-lg-4"><div class="report-card"><h3>Evolución mensual</h3><p>Gráfica de tickets creados vs resueltos</p></div></div>
      </div>

      <!-- Tabla mensual real -->
      <h2 style="font-size:1.5rem;font-weight:600;margin:4rem 0 1rem;">Detalle mensual (<?php echo date('F Y'); ?>)</h2>
      <div id="reportes-grid"></div>
    </div>
  </div>
</div>

<script>
  lucide.createIcons();
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  }

  // Cargar tickets del mes actual
  fetch('../../controller/month_report.php')
    .then(r => r.json())
    .then(data => {
      new gridjs.Grid({
        columns: ["Fecha", "Asunto", "Área", "Prioridad", "Tiempo resolución", "Técnico"],
        data: data,
        search: true,
        sort: true,
        pagination: { limit: 15 },
        resizable: true,
        language: { search: { placeholder: "Buscar en el mes…" } }
      }).render(document.getElementById("reportes-grid"));
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>