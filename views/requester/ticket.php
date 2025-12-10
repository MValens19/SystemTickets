<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reportar Incidencia · Soporte TI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Quill.js -->
  <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

  <style>
    body {font-family:'Inter',sans-serif;background:#fff;color:#1f1f1f;margin:0;}
    .topbar {position:fixed;top:0;left:0;right:0;height:70px;background:#fff;border-bottom:1px solid #eaeaea;display:flex;align-items:center;justify-content:space-between;padding:0 2rem;z-index:1000;box-shadow:0 2px 10px rgba(0,0,0,0.05);}
    .topbar .logo {font-weight:700;font-size:1.4rem;color:#000;}
    .topbar .user-info {display:flex;align-items:center;gap:14px;}
    .topbar .user-name {font-weight:600;font-size:0.95rem;}
    .topbar .user-area {font-size:0.85rem;color:#666;}
    .topbar .avatar {width:42px;height:42px;background:#000;color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:bold;}
    .main-layout {display:flex;height:calc(100vh - 70px);margin-top:70px;}
    .form-section, .history-section {flex:1;padding:3rem;overflow-y:auto;}
    .divider {width:1px;background:#eaeaea;flex-shrink:0;}
    .page-title {font-size:2.3rem;font-weight:700;letter-spacing:-0.02em;margin-bottom:0.5rem;}
    .page-subtitle {font-size:1.1rem;color:#666;margin-bottom:2rem;}
    .btn-create {background:#000;color:white;padding:12px 28px;border-radius:10px;font-weight:500;border:none;cursor:pointer;}
    .btn-create:hover {background:#333;}
    #quill-container {min-height:380px;border-radius:10px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);}
    #quill-container .ql-editor {min-height:340px;font-size:1rem;padding:1.2rem;}
    #quill-container .ql-editor img {max-width:100%;border-radius:10px;margin:1rem 0;box-shadow:0 4px 16px rgba(0,0,0,0.1);}
    #quill-container .ql-toolbar {background:#fafafa;border-bottom:none;border-radius:10px 10px 0 0;}
    .tip-text {font-size:0.9rem;color:#666;margin-top:0.5rem;}
    .ticket-item {background:#fff;border:1px solid #eaeaea;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;transition:.2s;box-shadow:0 2px 8px rgba(0,0,0,0.05);}
    .ticket-item:hover {box-shadow:0 8px 24px rgba(0,0,0,0.1);}
    .ticket-title {font-weight:600;font-size:1.1rem;margin-bottom:0.5rem;}
    .ticket-meta {font-size:0.9rem;color:#666;margin-bottom:1rem;}
    .status {font-size:0.8rem;padding:4px 10px;border-radius:6px;font-weight:500;}
    .loading {text-align:center;color:#666;padding:2rem;}
    @media (max-width:992px) {.main-layout {flex-direction:column;}.divider {height:1px;width:100%;margin:0;}.form-section, .history-section {padding:2rem;}}
  </style>
</head>
<body>

  <!-- Navbar -->
  <div class="topbar">
    <div class="logo">Soporte TI</div>
    <div class="user-info">
      <div class="text-end">
        <div class="user-name" id="user-name">Cargando...</div>
        <div class="user-area" id="user-area"></div>
      </div>
      <div class="avatar" id="user-avatar">??</div>
    </div>
  </div>

  <!-- Layout dividido -->
  <div class="main-layout">
    <!-- Formulario -->
    <div class="form-section">
      <h1 class="page-title">Reportar incidencia</h1>
      <p class="page-subtitle">Describe tu problema y te ayudaremos lo antes posible</p>

      <div id="mensaje"></div>

      <form id="form-ticket" action="../../controller/crear_ticket.php" method="POST">
        <input type="text" name="titulo" id="titulo" class="form-control form-control-lg border-0 border-bottom mb-4" 
               placeholder="Título del problema" required>

        <!-- Editor Quill -->
        <div id="quill-container">
          <div id="toolbar">
            <!-- Barra de herramientas completa -->
            <span class="ql-formats">
              <select class="ql-header">
                <option selected></option>
                <option value="1">Título 1</option>
                <option value="2">Título 2</option>
                <option value="3">Título 3</option>
              </select>
            </span>
            <span class="ql-formats">
              <button class="ql-bold"></button>
              <button class="ql-italic"></button>
              <button class="ql-underline"></button>
            </span>
            <span class="ql-formats">
              <button class="ql-list" value="ordered"></button>
              <button class="ql-list" value="bullet"></button>
            </span>
            <span class="ql-formats">
              <button class="ql-link"></button>
              <button class="ql-image"></button>
            </span>
            <span class="ql-formats">
              <button class="ql-clean"></button>
            </span>
          </div>
          <div id="editor">
           
          </div>
        </div>
        <div class="tip-text text-center mt-2">
          💡 <strong>Pega capturas con Ctrl+V o arrástralas directamente al editor</strong>
        </div>
        <input type="hidden" name="descripcion" id="descripcion">

        <div class="mt-4 d-flex justify-content-between align-items-center">
          <select name="prioridad" class="form-select w-auto">
            <option>Normal</option>
            <option>Alta</option>
            <option>Urgente</option>
          </select>
          <button type="submit" class="btn-create">Crear ticket</button>
        </div>
      </form>
    </div>

    <!-- Divisor -->
    <div class="divider"></div>

    <!-- Historial -->
    <div class="history-section">
      <h2 class="page-title" style="font-size:2rem;">Mis tickets</h2>
      <div id="historial-container">
        <p class="loading">Cargando tickets...</p>
      </div>
    </div>
  </div>

<script>
  // Quill editor con barra completa
  const quill = new Quill('#editor', {
    theme: 'snow',
    placeholder: 'Escribe aquí los detalles del problema...',
    modules: {
      toolbar: '#toolbar'
    }
  });

  // Pegar imágenes directamente (Ctrl+V desde recortador de Windows – funciona perfecto)
  quill.clipboard.addMatcher(Node.ELEMENT_NODE, (node, delta) => {
    if (node.tagName === 'IMG') {
      delta.ops = [{ insert: { image: node.src } }];
    }
    return delta;
  });

  // Cargar datos del usuario y tickets
  fetch('../../controller/pulltickets.php')
    .then(r => r.json())
    .then(data => {
      if (data.error) {
        document.body.innerHTML = '<p>Error de autenticación. <a href="login.php">Volver al login</a></p>';
        return;
      }

      // Navbar
      document.getElementById('user-name').textContent = data.nombre;
      document.getElementById('user-area').textContent = data.area;
      document.getElementById('user-avatar').textContent = data.nombre.split(' ').map(n => n[0]).join('').toUpperCase().substring(0,2);

      // Historial
      const container = document.getElementById('historial-container');
      container.innerHTML = '';

      if (data.tickets.length === 0) {
        container.innerHTML = '<p class="text-muted">Aún no has creado ningún ticket.</p>';
        return;
      }

      data.tickets.forEach(t => {
        const colorBg = t.estado === 'Resuelto' ? '#f0fff4' : 
                        (t.prioridad === 'Urgente' ? '#fff2f0' : '#f5f5f5');
        const colorText = t.estado === 'Resuelto' ? '#22c55e' : 
                          (t.prioridad === 'Urgente' ? '#ff4d4f' : '#666');

        container.innerHTML += `
          <div class="ticket-item">
            <div class="ticket-title">#${t.id_ticket} · ${t.titulo}</div>
            <div class="ticket-meta">${t.fecha_formateada} · Prioridad ${t.prioridad}</div>
            <span class="status" style="background:${colorBg};color:${colorText};">
              ${t.estado}
            </span>
          </div>
        `;
      });
    })
    .catch(err => {
      document.getElementById('historial-container').innerHTML = '<p class="text-danger">Error al cargar datos</p>';
    });

  // Capturar HTML del editor al enviar
  document.getElementById('form-ticket').addEventListener('submit', function(e) {
    document.getElementById('descripcion').value = quill.root.innerHTML;
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>