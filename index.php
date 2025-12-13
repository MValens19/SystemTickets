<?php
session_start();

// Protección de acceso
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: http://localhost/tickets/views/login.php");
    exit;
}

$roles_permitidos = ['admin', 'tecnico'];
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $roles_permitidos, true)) {
    header("Location: http://localhost/tickets/views/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin · Soporte TI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <style>
    /* Todo tu estilo original (igual que antes) */
    body {font-family:'Inter',sans-serif;background:#fff;color:#1f1f1f;margin:0;height:100vh;overflow:hidden;}
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
    .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1.5rem;margin:3rem 0;}
    .stat-card{background:#f7f7f7;padding:1.8rem;border-radius:14px;text-align:center;}
    .stat-number{font-size:2.4rem;font-weight:700;}
    .priority-dot{width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:12px;}
    .status{font-size:0.85rem;padding:4px 10px;border-radius:6px;font-weight:500;}
    .ticket-row{padding:1rem 0;border-top:1px solid #eaeaea;transition:.2s;}
    .ticket-row:hover{background:#f7f7f7;border-radius:8px;}
    .loading{text-align:center;color:#666;padding:3rem;font-size:1.1rem;}
  </style>
</head>
<body>

<div class="app-container">

  <!-- Sidebar -->
  <?php include 'components/sidebar.html'; ?>

  <div class="main-area">
    <?php include 'components/ToggleSidebar.html'; ?>

    <div class="main-content">
      <h1 class="page-title">Centro de Control TI</h1>
      <p style="color:#666;font-size:1.1rem;">Panel administrativo del área de sistemas</p>

      <!-- Estadísticas -->
      <div class="stats-grid" id="stats-container">
        <div class="loading">Cargando estadísticas...</div>
      </div>

      <!-- Tickets críticos -->
      <h2 style="font-size:1.5rem;font-weight:600;margin:3rem 0 1rem;">Tickets críticos hoy</h2>
      <div id="criticos-container">
        <div class="loading">Cargando tickets críticos...</div>
      </div>
    </div>
  </div>
</div>

<script>
  lucide.createIcons();

  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  }

  // Cargar datos del dashboard
  fetch('controller/dashboard_data.php')
    .then(r => r.json())
    .then(data => {
      if (data.error) {
        document.getElementById('stats-container').innerHTML = '<p class="text-danger">Error de autenticación</p>';
        return;
      }

      // Estadísticas
      document.getElementById('stats-container').innerHTML = `
        <div class="stat-card">
          <div class="stat-number">${data.total_tickets}</div>
          <div>Tickets totales</div>
        </div>
        <div class="stat-card">
          <div class="stat-number text-danger">${data.urgentes_hoy}</div>
          <div>Urgentes hoy</div>
        </div>
        <div class="stat-card">
          <div class="stat-number text-warning">${data.pendientes}</div>
          <div>Pendientes</div>
        </div>
        <div class="stat-card">
          <div class="stat-number text-success">${data.porcentaje_resueltos}%</div>
          <div>Resueltos este mes</div>
        </div>
      `;

      // Tickets críticos
      const criticosContainer = document.getElementById('criticos-container');
      criticosContainer.innerHTML = '';

      if (data.criticos.length === 0) {
        criticosContainer.innerHTML = '<p class="text-muted">No hay tickets críticos hoy</p>';
        return;
      }

      data.criticos.forEach(t => {
        const color = t.prioridad === 'Urgente' ? '#ff4d4f' : '#fa8c16';
        criticosContainer.innerHTML += `
          <div class="ticket-row d-flex align-items-center">
            <span class="priority-dot" style="background:${color};"></span>
            <div class="flex-fill"><strong>${t.titulo}</strong> — ${t.usuario_nombre || 'Usuario'}</div>
            <span class="status me-3" style="background:${t.prioridad === 'Urgente' ? '#fff2f0' : '#fff7e6'};color:${color};">${t.prioridad}</span>
            <span class="status text-muted" style="background:#f5f5f5;">${t.estado}</span>
            <small class="text-muted ms-4">${t.tiempo}</small>
          </div>
        `;
      });
    })
    .catch(err => {
      document.getElementById('stats-container').innerHTML = '<p class="text-danger">Error al cargar datos</p>';
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>