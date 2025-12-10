<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Usuarios · Soporte TI</title>
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
    .btn-new{background:#000;color:white;padding:12px 24px;border-radius:10px;font-weight:500;border:none;}
    .btn-new:hover{background:#333;}

    #usuarios-grid{margin-top:1rem;}
    .gridjs-container{border-radius:14px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.08);}
    .gridjs-head{background:white;color:white;font-weight:600; margin-top:1rem; margin-left:1rem;}
  </style>
</head>
<body>

<div class="app-container">

  <!-- Sidebar -->
  <?php include '../../components/sidebar.html'; ?>

  <div class="main-area">
    <!-- Toggle y navbar -->
    <?php include '../../components/ToggleSidebar.html'; ?>

 <div class="main-content">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="page-title">Usuarios del sistema</h1>
          <p class="page-subtitle">Gestión completa de cuentas y permisos</p>
        </div>
        <button class="btn-new" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
          <i data-lucide="plus"></i> Agregar usuario
        </button>
      </div>

      <!-- Tabla dinámica de usuarios -->
      <div id="usuarios-grid"></div>
    </div>
  </div>
</div>

<!-- Modal: Agregar usuario -->
<div class="modal fade" id="modalNuevoUsuario" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">Nuevo usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formUsuario">
          <div class="mb-3">
            <label class="form-label">Nombre completo</label>
            <input type="text" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Puesto</label>
            <input type="text" class="form-control" placeholder="Ej: Contador, Gerente de ventas">
          </div>
          <div class="mb-3">
            <label class="form-label">Área / Departamento</label>
            <select class="form-select">
              <option>Contabilidad</option>
              <option>Ventas</option>
              <option>Recursos Humanos</option>
              <option>Dirección</option>
              <option>Logística</option>
              <option>Sistemas</option>
              <option>Otros</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Usuario (login)</label>
            <input type="text" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Repetir contraseña</label>
            <input type="password" class="form-control" required>
          </div>
        </form>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-dark">Crear usuario</button>
      </div>
    </div>
  </div>
</div>

<script>
  lucide.createIcons();

  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  }

  // Datos de usuarios (luego vendrán de PHP + MySQL)
  const usuarios = [
    ["Ana Torres", "Contadora", "Contabilidad", "ana.torres", "Activo"],
    ["Luis Ramírez", "Vendedor", "Ventas", "luis.ramirez", "Activo"],
    ["Sofía López", "RRHH", "Recursos Humanos", "sofia.lopez", "Activo"],
    ["Carlos Mendoza", "Técnico TI", "Sistemas", "carlos.mendoza", "Administrador"],
    ["Jorge Morales", "Director General", "Dirección", "jorge.morales", "Activo"],
  ];

  new gridjs.Grid({
    columns: ["Nombre", "Puesto", "Área", "Usuario", {name:"Rol", formatter:(c)=>`${c}`}],
    data: usuarios,
    search: true,
    sort: true,
    pagination: { limit: 15 },
    resizable: true,
    language: { search: { placeholder: "Buscar usuario…" } }
  }).render(document.getElementById("usuarios-grid"));
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>