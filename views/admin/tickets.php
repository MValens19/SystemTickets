<?php
session_start();

// Protección de acceso
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../views/login.php?error=sesion");
    exit;
}

$roles_permitidos = ['admin', 'tecnico'];
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $roles_permitidos, true)) {
    header("Location: ../../views/login.php?error=permiso");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Todos los tickets · Soporte TI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

  <!-- Grid.js -->
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

    #tickets-grid {margin-top:1rem;}
    .gridjs-container {border-radius:14px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.08);}
    .gridjs-head {background:#000;color:white;font-weight:600;}
    .status {padding:6px 12px;border-radius:6px;font-size:0.85rem;font-weight:500;}
    .urgente {background:#fff2f0;color:#ff4d4f;}
    .alta {background:#fff7e6;color:#fa8c16;}
    .normal {background:#f6ffed;color:#52c41a;}
    .baja {background:#f9f0ff;color:#8b5cf6;}
    .pendiente {background:#f5f5f5;color:#666;}
    .btn-ver-captura {background:#000;color:white;padding:6px 12px;border-radius:8px;font-size:0.85rem;cursor:pointer;transition:.2s;}
    .btn-ver-captura:hover {background:#333;}
    .descripcion-text {font-size:0.9rem;color:#444;max-height:80px;overflow:hidden;text-overflow:ellipsis;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;}
    .no-descripcion {color:#999;font-style:italic;}

    /* Modal de imagen */
    #imageModal .modal-content {background:rgba(0,0,0,0.9);border:none;}
    #imageModal .btn-close {opacity:1;filter:invert(1);}
    #modalImage {max-height:90vh;max-width:90vw;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.6);}
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

      <!-- Tabla dinámica -->
      <div id="tickets-grid"></div>
    </div>
  </div>
</div>

<!-- Modal para imagen completa -->
<div class="modal fade" id="imageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="text-end p-3">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <img id="modalImage" src="" class="img-fluid" alt="Captura del ticket">
    </div>
  </div>
</div>

<script>
  lucide.createIcons();

  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  }

  // Cargar tickets desde la BD
  new gridjs.Grid({
    columns: [
      { name: "ID", width: "80px", sort: true },
      { name: "Asunto", width: "25%", sort: true },
      { name: "Usuario", width: "15%", sort: true },
      { name: "Área", width: "100px", sort: true },
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
          return gridjs.html(`<span class="status pendiente">${cell}</span>`);
        }
      },
      
      { name: "Fecha", width: "140px", sort: true },
      {
        name: "Descripción",
        width: "300px",
        sort: false,
        formatter: (cell) => {
          if (!cell || cell.trim() === '') {
            return gridjs.html('<span class="no-descripcion">Sin descripción</span>');
          }
          // Extraer solo el texto (sin HTML)
          const textOnly = cell.replace(/<[^>]*>/g, '').trim();
          return gridjs.html(`<div class="descripcion-text">${textOnly || 'Sin texto'}</div>`);
        }
      },
      {
        name: "Captura",
        width: "140px",
        sort: false,
        formatter: (cell) => {
          if (!cell || !/<img/i.test(cell)) {
            return gridjs.html('<span class="text-muted">Sin captura</span>');
          }
          return gridjs.html(`
            <button class="btn-ver-captura" data-content="${btoa(unescape(encodeURIComponent(cell)))}">
              Ver captura
            </button>
          `);
        }
      }
    ],
    search: true,
    sort: true,
    pagination: { limit: 15 },
    resizable: true,
    language: {
      search: { placeholder: "Buscar en todos los tickets…" },
      pagination: { previous: "Anterior", next: "Siguiente", showing: "Mostrando", results: () => "tickets" }
    },
    server: {
      url: '../../controller/all_tickets.php',
      then: data => data.map(ticket => [
        ticket.id_ticket,
        ticket.titulo,
        ticket.usuario_nombre,
        ticket.area,
        ticket.prioridad,
        ticket.estado,
        ticket.fecha_formateada,
        ticket.descripcion || '',
        ticket.descripcion || ''
      ])
    }
  }).render(document.getElementById("tickets-grid"));

  // Abrir modal con la primera imagen
  document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-ver-captura')) {
      const btn = e.target.closest('.btn-ver-captura');
      const encoded = btn.dataset.content;
      const html = decodeURIComponent(escape(atob(encoded)));

      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      const img = doc.querySelector('img');

      if (img && img.src) {
        document.getElementById('modalImage').src = img.src;
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
      }
    }
  });

  // Limpiar modal al cerrar
  document.getElementById('imageModal').addEventListener('hidden.bs.modal', () => {
    document.getElementById('modalImage').src = '';
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>