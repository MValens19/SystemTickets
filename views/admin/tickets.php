<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Todos los tickets · Soporte TI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

  <!-- Grid.js (27 KB, tema Notion-like) -->
  <link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
  <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>

  <style>
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
    .page-subtitle{color:#666;font-size:1.1rem;margin-bottom:2rem;}

    /* Estilo Grid.js personalizado */
    #tickets-grid {margin-top:1rem;}
    .gridjs-container {border-radius:14px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.08);}
    .gridjs-head {background:white;color:white;font-weight:600;}
    .gridjs-search {max-width:360px;margin-left:auto; margin-top:1rem; margin-left:1rem;}
    .gridjs-search input {border-radius:10px;border:1px solid #eaeaea;padding:12px 40px 12px 16px;}
    .status {padding:6px 12px;border-radius:6px;font-size:0.85rem;font-weight:500;}
    .urgente {background:#fff2f0;color:#ff4d4f;}
    .alta {background:#fff7e6;color:#fa8c16;}
    .normal {background:#f6ffed;color:#52c41a;}
    .baja {background:#f9f0ff;color:#8b5cf6;}
    .pendiente {background:#f5f5f5;color:#666;}
  </style>
</head>
<body>

<div class="app-container">

  <!-- Sidebar -->
  <?php include '../../components/sidebar.html'; ?>

  <div class="main-area">
     <?php include '../../components/ToggleSidebar.html'; ?> 

    <div class="main-content">
      <h1 class="page-title">Todos los tickets</h1>
      <p class="page-subtitle">Listado completo de incidencias y solicitudes</p>

      <!-- Tabla dinámica con Grid.js -->
      <div id="tickets-grid"></div>
    </div>
  </div>
</div>

<script>
  lucide.createIcons();

  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  }

  // Datos de ejemplo (luego los sacas de MySQL con PHP)
  const tickets = [
    [1001, "Servidor producción DOWN", "Ana Torres", "Contabilidad", "Urgente", "En proceso", "Carlos", "hace 12 min"],
    [1000, "Impresora fiscal offline", "Luis Ramírez", "Ventas", "Alta", "Pendiente", "—", "hace 1 h"],
    [999, "Solicitud acceso VPN", "Sofía López", "RRHH", "Normal", "Resuelto", "María", "hace 3 h"],
    [998, "Cambio contraseña Wi-Fi", "Jorge Morales", "Dirección", "Baja", "Resuelto", "Sistema", "ayer"],
    [997, "Monitor no enciende", "Pedro Gómez", "Logística", "Alta", "En proceso", "Juan", "hace 2 días"],
    [996, "Error al imprimir facturas", "Laura Pérez", "Contabilidad", "Alta", "Pendiente", "—", "hace 4 h"],
  ];

  new gridjs.Grid({
    columns: [
      { name: "ID", width: "80px", sort: true },
      { name: "Asunto", width: "35%", sort: true },
      { name: "Usuario", width: "18%", sort: true },
      { name: "Área", width: "120px", sort: true },
      {
        name: "Prioridad",
        width: "110px",
        sort: true,
        formatter: (cell) => {
          const cls = cell === "Urgente" ? "urgente" :
                      cell === "Alta" ? "alta" :
                      cell === "Normal" ? "normal" : "baja";
          return gridjs.html(`<span class="status ${cls}">${cell}</span>`);
        }
      },
      {
        name: "Estado",
        width: "120px",
        sort: true,
        formatter: (cell) => {
          if (cell === "Resuelto") return gridjs.html('<span class="status normal">Resuelto</span>');
          if (cell === "En proceso") return gridjs.html('<span class="status pendiente">En proceso</span>');
          return gridjs.html(`<span class="status pendiente">${cell}</span>`);
        }
      },
      { name: "Asignado", width: "130px", sort: true },
      { name: "Fecha", width: "140px", sort: true }
    ],
    data: tickets,
    search: true,
    sort: true,
    pagination: { limit: 15 },
    resizable: true,
    language: {
      search: { placeholder: "Buscar en todos los tickets…" },
      pagination: { previous: "Anterior", next: "Siguiente", showing: "Mostrando", results: () => "tickets" }
    },
    className: {
      table: "table table-borderless",
      td: "py-3"
    }
  }).render(document.getElementById("tickets-grid"));
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 