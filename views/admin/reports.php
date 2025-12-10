<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reportes · Soporte TI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

  <!-- Grid.js + tema bonito -->
  <link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
  <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>

  <style>
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
    .stat-card{background:#f7f7f7;padding:1.8rem;border-radius:14px;text-align:center;}
    .stat-number{font-size:2.4rem;font-weight:700;margin-bottom:0.5rem;}

    .report-card{background:#fff;border:1px solid #eaeaea;border-radius:14px;padding:1.5rem;transition:.2s;cursor:pointer;}
    .report-card:hover{box-shadow:0 8px 32px rgba(0,0,0,0.1);transform:translateY(-4px);}
    .report-card h3{font-size:1.3rem;font-weight:600;margin:0 0 0.5rem 0;}
    .report-card p{font-size:0.95rem;color:#666;}

    #reportes-grid{margin-top:2rem;}
    .gridjs-container{border-radius:14px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.08);}
    .gridjs-head{background:white;color:white;font-weight:600; margin-top:1rem; margin-left:1rem;}
  </style>
</head>
<body>

<div class="app-container">

  <!-- Sidebar (el mismo de siempre) -->
  <?php include '../../components/sidebar.html'; ?>

  <div class="main-area">
    <!-- Toggle -->


    <!-- Navbar -->
    <div class="topbar">
      <div class="d-flex align-items-center gap-3">
        <div class="text-end">
          <div style="font-weight:600;font-size:0.95rem;">Carlos Mendoza</div>
          <small style="color:#666;">Área de Sistemas</small>
        </div>
        <div style="width:40px;height:40px;background:#000;color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:bold;">CM</div>
      </div>
    </div>

    <div class="main-content">
      <h1 class="page-title">Reportes y estadísticas</h1>
      <p class="page-subtitle">Análisis completo del rendimiento del área de TI</p>

      <!-- Estadísticas rápidas -->
      <div class="stats-grid">
        <div class="stat-card"><div class="stat-number text-danger">6</div><div>Tickets urgentes este mes</div></div>
        <div class="stat-card"><div class="stat-number text-success">142</div><div>Resueltos (2025)</div></div>
        <div class="stat-card"><div class="stat-number">3.2 h</div><div>Tiempo promedio de resolución</div></div>
        <div class="stat-card"><div class="stat-number text-primary">94%</div><div>Satisfacción usuarios</div></div>
      </div>

      <!-- Reportes disponibles -->
      <h2 style="font-size:1.5rem;font-weight:600;margin:3rem 0 1.5rem;">Reportes disponibles <i data-lucide="square-menu"></i></h2>
      <div class="row g-4">
        <div class="col-md-6 col-lg-4"><div class="report-card"><h3>Tickets por área</h3><p>Distribución de incidencias por departamento</p></div></div>
        <div class="col-md-6 col-lg-4"><div class="report-card"><h3>Tiempo de resolución</h3><p>Promedio y mediana por prioridad</p></div></div>
        <div class="col-md-6 col-lg-4"><div class="report-card"><h3>Técnicos más activos</h3><p>Ranking de resolución y carga de trabajo</p></div></div>
        <div class="col-md-6 col-lg-4"><div class="report-card"><h3>Incidencias recurrentes</h3><p>Problemas que se repiten más de 3 veces</p></div></div>
        <div class="col-md-6 col-lg-4"><div class="report-card"><h3>SLA cumplimiento</h3><p>Porcentaje de tickets resueltos dentro del plazo</p></div></div>
        <div class="col-md-6 col-lg-4"><div class="report-card"><h3>Evolución mensual</h3><p>Gráfica de tickets creados vs resueltos</p></div></div>
      </div>

      <!-- Tabla de ejemplo: Tickets del mes actual -->
      <h2 style="font-size:1.5rem;font-weight:600;margin:4rem 0 1rem;">Detalle mensual (diciembre 2025)</h2>
      <div id="reportes-grid"></div>
    </div>
  </div>
</div>

<script>
  lucide.createIcons();
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  }

  const dataMes = [
    ["2025-12-09", "Servidor DOWN", "Contabilidad", "Urgente", "1.2 h", "Carlos"],
    ["2025-12-09", "Impresora offline", "Ventas", "Alta", "2.8 h", "Juan"],
    ["2025-12-08", "Acceso VPN", "RRHH", "Normal", "0.5 h", "María"],
    ["2025-12-07", "Monitor no enciende", "Logística", "Alta", "4.1 h", "Carlos"],
    ["2025-12-06", "Error software X", "Contabilidad", "Normal", "6.3 h", "Juan"],
  ];

  new gridjs.Grid({
    columns: ["Fecha", "Asunto", "Área", "Prioridad", "Tiempo resolución", "Técnico"],
    data: dataMes,
    search: true,
    sort: true,
    pagination: { limit: 15 },
    resizable: true,
    language: { search: { placeholder: "Buscar en el mes…" } }
  }).render(document.getElementById("reportes-grid"));
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>