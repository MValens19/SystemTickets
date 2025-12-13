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

  <!-- Quill.js (para mostrar descripción completa) -->
  <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

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
    .gridjs-container {border-radius:14px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.08); background: #000;}
    .gridjs-head {background:#000;color:white;font-weight:600; margin-top: 1rem; margin-left: 1rem; margin-bottom: 1rem;}

    .status {padding:6px 12px;border-radius:6px;font-size:0.85rem;font-weight:500;}
    .urgente {background:#fff2f0;color:#ff4d4f;}
    .alta {background:#fff7e6;color:#fa8c16;}
    .normal {background:#f6ffed;color:#52c41a;}
    .pendiente {background:#f5f5f5;color:#666;}

    .btn-detalles {background:#000;color:white;padding:8px 16px;border-radius:8px;font-size:0.9rem;cursor:pointer;transition:.2s;}
    .btn-detalles:hover {background:#333;}

    /* Modal detalles */
    #detallesModal .modal-body {padding:0;}
    #detallesModal .ql-container {border:none;border-radius:0;}
    #detallesModal .ql-editor {padding:2rem;min-height:400px;font-size:1.1rem;}
    #detallesModal img {max-width:100%;border-radius:12px;margin:1.5rem 0;box-shadow:0 8px 30px rgba(0,0,0,0.2);}
    /* Quill en modal – mucho más bonito */
#quill-detalles .ql-container {
  border: none !important;
  font-size: 1.1rem;
  line-height: 1.7;
}
#quill-detalles .ql-editor {
  padding: 2rem;
  min-height: 100%;
}
#quill-detalles .ql-editor p {
  margin-bottom: 1rem;
}
#quill-detalles .ql-editor img {
  max-width: 100%;
  border-radius: 12px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.15);
  margin: 1.5rem 0;
  display: block;
}
#quill-detalles .ql-editor strong {
  font-weight: 600;
}
#quill-detalles .ql-toolbar {
  display: none !important; /* Solo lectura */
}
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

      <!-- Tabla -->
      <div id="tickets-grid"></div>
    </div>
  </div>
</div>

<<!-- Modal Detalles del Ticket (más bonito) -->
<div class="modal fade" id="detallesModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
      <div class="modal-header bg-dark text-white" style="border-radius:16px 16px 0 0;">
        <h5 class="modal-title" id="modal-titulo" style="font-weight:700;font-size:1.4rem;">Ticket #000</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <!-- Estado -->
        <div class="p-4 bg-light border-bottom">
          <div class="d-flex align-items-center gap-3">
            <strong>Estado actual:</strong>
            <select id="estado-select" class="form-select w-auto" style="font-size:1rem;padding:8px 12px; border-radius:8px;">
              <option value="Pendiente">Pendiente  .</option>
              <option value="En proceso">En proceso .</option>
              <option value="Resuelto">Resuelto  .</option>
              <option value="Cerrado">Cerrado  .</option>
            </select>
          </div>
        </div>

        <!-- Editor Quill solo lectura (más bonito) -->
        <div id="quill-detalles" style="background:white;height:60vh;"></div>
      </div>
      <div class="modal-footer bg-light">
        <small class="text-muted me-auto">Puedes cambiar el estado y cerrar</small>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" id="guardarEstadoBtn">Guardar estado</button>
      </div>
    </div>
  </div>
</div>

<script>
  lucide.createIcons();

  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  }

let quillDetails;
  let currentTicketId = null;

  // Inicializar Quill en modo solo lectura
  document.addEventListener('DOMContentLoaded', () => {
    quillDetails = new Quill('#quill-detalles', {
      theme: 'snow',
      readOnly: true,
      modules: {
        toolbar: false
      }
    });
  });

  // Cargar tickets
  new gridjs.Grid({
    columns: [
      { name: "ID", width: "80px", sort: true },
      { name: "Asunto", width: "30%", sort: true },
      { name: "Usuario", width: "18%", sort: true },
      { name: "Área", width: "110px", sort: true },
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
        width: "130px",
        sort: true,
        formatter: (cell) => {
          if (cell === "Resuelto") return gridjs.html('<span class="status normal">Resuelto</span>');
          return gridjs.html(`<span class="status pendiente">${cell}</span>`);
        }
      },
      { name: "Fecha", width: "140px", sort: true },
      {
        name: "Detalles",
        width: "180px",
        sort: false,
        formatter: (cell, row) => {
          const id = row.cells[0].data;
          const descripcion = row.cells[7].data || '';
          return gridjs.html(`
            <button class="btn-detalles" 
                    data-id="${id}" 
                    data-titulo="${row.cells[1].data}"
                    data-estado="${row.cells[5].data}"
                    data-content="${btoa(unescape(encodeURIComponent(descripcion)))}">
              Ver detalles
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
      then: data => data.map(t => [
        t.id_ticket,
        t.titulo,
        t.usuario_nombre,
        t.area,
        t.prioridad,
        t.estado,
        t.fecha_formateada,
        t.descripcion || ''
      ])
    }
  }).render(document.getElementById("tickets-grid"));

  // Abrir modal con detalles
  document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-detalles')) {
      const btn = e.target.closest('.btn-detalles');
      currentTicketId = btn.dataset.id;

      // Título
      document.getElementById('modal-titulo').textContent = 'Ticket #' + btn.dataset.id + ' · ' + btn.dataset.titulo;

      // Estado actual
      document.getElementById('estado-select').value = btn.dataset.estado;

      // Descripción completa (con imágenes)
      const encoded = btn.dataset.content;
      const html = decodeURIComponent(escape(atob(encoded)));
      
      quillDetails.root.innerHTML = html || '<p style="color:#999;font-style:italic;">Sin descripción</p>';

      const modal = new bootstrap.Modal(document.getElementById('detallesModal'));
      modal.show();
    }
  });

  // Guardar cambio de estado
  document.getElementById('guardarEstadoBtn').addEventListener('click', () => {
    if (!currentTicketId) return;

    const nuevoEstado = document.getElementById('estado-select').value;

    fetch('../../controller/change_estatus.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id_ticket: currentTicketId, estado: nuevoEstado })
    })
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        alert('Estado actualizado correctamente');
        location.reload(); // o actualiza la fila sin recargar
      } else {
        alert('Error al actualizar estado');
      }
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>