<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Configuración · Soporte TI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <style>
    body { font-family: 'Inter', sans-serif; background:#fff; color:#1f1f1f; margin:0; height:100vh; overflow:hidden; }
    .app-container { display: flex; height: 100vh; }
    .sidebar { width:280px; background:#fafafa; border-right:1px solid #eaeaea; padding:1.5rem; overflow-y:auto; flex-shrink:0; transition:width .3s ease; }
    .sidebar.collapsed { width:0; padding:0; overflow:hidden; }
    .logo { font-weight:700; font-size:1.5rem; margin-bottom:2.5rem; display:flex; align-items:center; gap:12px; }
    .nav-item { display:flex; align-items:center; gap:14px; padding:12px 16px; border-radius:10px; cursor:pointer; font-weight:500; transition:.2s; }
    .nav-item:hover, .nav-item.active { background:#000; color:white; }

    .main-area { flex:1; display:flex; flex-direction:column; min-width:0; }
    .topbar { height:64px; background:#fff; border-bottom:1px solid #eaeaea; padding:0 2rem; display:flex; align-items:center; justify-content:flex-end; flex-shrink:0; }
    .toggle-btn { position:absolute; left:280px; top:0; height:64px; width:64px; background:transparent; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:left .3s ease; z-index:100; }
    .sidebar.collapsed ~ .main-area .toggle-btn { left:0; }

    .main-content { flex:1; overflow-y:auto; padding:3rem; }
    .page-title { font-size:2.5rem; font-weight:700; letter-spacing:-0.02em; margin-bottom:0.5rem; }
    .section { margin:3rem 0; }
    .section-title { font-size:1.4rem; font-weight:600; margin-bottom:1.5rem; color:#000; font-variant: small-caps; }

    /* Toggle negro moderno */
    .toggle-switch {
      position: relative; display: inline-block; width: 56px; height: 32px;
    }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .slider {
      position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
      background-color: #ccc; transition: .3s; border-radius: 34px;
    }
    .slider:before {
      position: absolute; content: ""; height: 26px; width: 26px; left: 3px; bottom: 3px;
      background-color: white; transition: .3s; border-radius: 50%;
    }
    input:checked + .slider { background-color: #000; }
    input:checked + .slider:before { transform: translateX(24px); }

    .config-item {
      display: flex; align-items: center; justify-content: space-between;
      padding: 1rem 0; border-bottom: 1px solid #eaeaea;
    }
    .config-item:last-child { border-bottom:none; }

     .nav-item,
        .nav-item a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 500;
            transition: .2s;
            color: inherit;
            text-decoration: none;
        }

        .nav-item:hover,
        .nav-item a:hover,
        .nav-item.active,
        .nav-item a.active {
            background: #000;
            color: white;
        }
  </style>
</head>
<body>

<div class="app-container">

  <!-- Sidebar -->
    <?php include '../../components/sidebar.html'; ?>

  <div class="main-area">
    <button class="toggle-btn" onclick="toggleSidebar()">
      <i data-lucide="menu"></i>
    </button>

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
      <h1 class="page-title">Configuración del Sistema</h1>
      <p style="color:#666;font-size:1.1rem;">Ajustes generales del sistema de tickets</p>

      <!-- Notificaciones -->
      <div class="section">
        <h2 class="section-title">Notificaciones</h2>
        <div>
          <div class="config-item">
            <div>
              <strong>Notificar por email al crear ticket nuevo</strong><br>
              <small class="text-muted">El equipo de TI recibe alerta inmediata</small>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" checked>
              <span class="slider"></span>
            </label>
          </div>
          <div class="config-item">
            <div>
              <strong>Sonido de alerta para tickets urgentes</strong><br>
              <small class="text-muted">Reproduce sonido cuando entre un ticket rojo</small>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" checked>
              <span class="slider"></span>
            </label>
          </div>
          <div class="config-item">
            <div>
              <strong>Notificación en navegador (push)</strong><br>
              <small class="text-muted">Incluso si la pestaña está en segundo plano</small>
            </div>
            <label class="toggle-switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>

      <!-- Tickets -->
      <div class="section">
        <h2 class="section-title">Comportamiento de tickets</h2>
        <div>
          <div class="config-item">
            <div>
              <strong>Asignación automática por área</strong><br>
              <small class="text-muted">Ej: Contabilidad → Carlos, Ventas → María</small>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" checked>
              <span class="slider"></span>
            </label>
          </div>
          <div class="config-item">
            <div>
              <strong>Permitir que usuarios cambien prioridad</strong><br>
              <small class="text-muted">Solo recomendado en empresas pequeñas</small>
            </div>
            <label class="toggle-switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
          <div class="config-item">
            <div>
              <strong>Cierre automático tras 48h sin respuesta</strong><br>
              <small class="text-muted">Marca como resuelto si el usuario no contesta</small>
            </div>
            <label class="toggle-switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>

      <!-- Seguridad y apariencia -->
      <div class="section">
        <h2 class="section-title">Apariencia y seguridad</h2>
        <div>
          <div class="config-item">
            <div>
              <strong>Modo oscuro automático</strong><br>
              <small class="text-muted">Se activa según preferencia del sistema</small>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" checked>
              <span class="slider"></span>
            </label>
          </div>
          <div class="config-item">
            <div>
              <strong>Requerir autenticación de dos factores</strong><br>
              <small class="text-muted">Solo para administradores</small>
            </div>
            <label class="toggle-switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>

      <div class="mt-5">
        <button class="btn btn-dark px-4 py-2" style="border-radius:8px;font-weight:500;">Guardar todos los cambios</button>
      </div>
    </div>
  </div>
</div>

<script>
  lucide.createIcons();
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>