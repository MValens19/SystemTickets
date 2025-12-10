<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reportar Incidencia · Soporte TI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- TipTap Editor -->
  <link rel="stylesheet" href="https://unpkg.com/@tiptap/starter-kit@2.0.0/dist/css/tiptap.css">
  <script src="https://unpkg.com/@tiptap/core@2.0.0/dist/tiptap.umd.min.js"></script>
  <script src="https://unpkg.com/@tiptap/starter-kit@2.0.0/dist/starter-kit.umd.min.js"></script>

  <style>
    body {font-family:'Inter',sans-serif;background:#fff;color:#1f1f1f;margin:0;}
    
    /* Navbar superior */
    .topbar {
      position:fixed;top:0;left:0;right:0;height:70px;background:#fff;border-bottom:1px solid #eaeaea;
      display:flex;align-items:center;justify-content:space-between;padding:0 2rem;z-index:1000;
      box-shadow:0 2px 10px rgba(0,0,0,0.05);
    }
    .topbar .logo {font-weight:700;font-size:1.4rem;color:#000;}
    .topbar .user-info {display:flex;align-items:center;gap:14px;}
    .topbar .user-name {font-weight:600;font-size:0.95rem;}
    .topbar .user-area {font-size:0.85rem;color:#666;}
    .topbar .avatar {width:42px;height:42px;background:#000;color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:bold;}

    /* Layout principal */
    .main-layout {
      display:flex;height:calc(100vh - 70px);margin-top:70px;
    }
    .form-section, .history-section {
      flex:1;padding:3rem;overflow-y:auto;
    }
    .divider {
      width:1px;background:#eaeaea;flex-shrink:0;
    }

    .page-title {font-size:2.3rem;font-weight:700;letter-spacing:-0.02em;margin-bottom:0.5rem;}
    .page-subtitle {font-size:1.1rem;color:#666;margin-bottom:2rem;}

    .btn-create {background:#000;color:white;padding:12px 28px;border-radius:10px;font-weight:500;border:none;cursor:pointer;}
    .btn-create:hover {background:#333;}

    /* Editor */
    .tiptap {min-height:340px;padding:1.2rem;border:1px solid #eaeaea;border-radius:10px;background:#fff;font-size:1rem;}
    .tiptap:focus {border-color:#000;}
    .tiptap img {max-width:100%;border-radius:10px;margin:1rem 0;box-shadow:0 4px 16px rgba(0,0,0,0.1);}
    .editor-toolbar {
      background:#fafafa;border:1px solid #eaeaea;border-bottom:none;border-radius:10px 10px 0 0;
      padding:0.7rem 1rem;display:flex;gap:10px;flex-wrap:wrap;position:sticky;top:70px;z-index:10;
    }
    .toolbar-btn {background:none;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;font-size:1.1rem;transition:.2s;}
    .toolbar-btn:hover, .toolbar-btn.is-active {background:#000;color:white;}

    /* Historial */
    .ticket-item {
      background:#fff;border:1px solid #eaeaea;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;
      transition:.2s;box-shadow:0 2px 8px rgba(0,0,0,0.05);
    }
    .ticket-item:hover {box-shadow:0 8px 24px rgba(0,0,0,0.1);}
    .ticket-title {font-weight:600;font-size:1.1rem;margin-bottom:0.5rem;}
    .ticket-meta {font-size:0.9rem;color:#666;margin-bottom:1rem;}
    .status {font-size:0.8rem;padding:4px 10px;border-radius:6px;font-weight:500;}

    @media (max-width:992px) {
      .main-layout {flex-direction:column;}
      .divider {height:1px;width:100%;margin:0;}
      .form-section, .history-section {padding:2rem;}
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <div class="topbar">
    <div class="logo">Soporte TI</div>
    <div class="user-info">
      <div class="text-end">
        <div class="user-name">Ana Torres</div>
        <div class="user-area">Contabilidad</div>
      </div>
      <div class="avatar">AT</div>
    </div>
  </div>

  <!-- Layout dividido -->
  <div class="main-layout">
    <!-- LADO IZQUIERDO: Formulario nuevo ticket -->
    <div class="form-section">
      <h1 class="page-title">Reportar incidencia</h1>
      <p class="page-subtitle">Describe tu problema y te ayudaremos lo antes posible</p>

      <input type="text" class="form-control form-control-lg border-0 border-bottom mb-4" placeholder="Título del problema" required>

      <div>
        <div class="editor-toolbar">
          <button type="button" class="toolbar-btn" data-cmd="bold"><strong>B</strong></button>
          <button type="button" class="toolbar-btn" data-cmd="italic"><em>I</em></button>
          <button type="button" class="toolbar-btn" data-cmd="bulletList">• Lista</button>
          <button type="button" class="toolbar-btn" data-cmd="orderedList">1. Lista</button>
          <button type="button" class="toolbar-btn" data-cmd="heading" data-level="2">H2</button>
          <span class="text-muted ms-auto small">Pega capturas con Ctrl+V</span>
        </div>
        <div id="editor" class="tiptap" contenteditable="true" ></div>
      </div>

      <div class="mt-4 d-flex justify-content-between align-items-center" >
        <select class="form-select w-auto">
          <option>Normal</option>
          <option>Alta</option>
          <option>Urgente</option>
        </select>
        <button class="btn-create">Crear ticket</button>
      </div>
    </div>

    <!-- Línea divisoria -->
    <div class="divider"></div>

    <!-- LADO DERECHO: Historial de tickets -->
    <div class="history-section">
      <h2 class="page-title" style="font-size:2rem;">Mis tickets</h2>

      <div class="ticket-item">
        <div class="ticket-title">#1001 · Servidor producción DOWN</div>
        <div class="ticket-meta">Hace 15 minutos · Prioridad Urgente</div>
        <span class="status" style="background:#fff2f0;color:#ff4d4f;">En proceso</span>
      </div>

      <div class="ticket-item">
        <div class="ticket-title">#999 · Solicitud de nuevo mouse ergonómico</div>
        <div class="ticket-meta">Ayer · Prioridad Normal</div>
        <span class="status" style="background:#f0fff4;color:#22c55e;">Resuelto</span>
      </div>

      <div class="ticket-item">
        <div class="ticket-title">#995 · Impresora no responde</div>
        <div class="ticket-meta">Hace 3 días · Prioridad Alta</div>
        <span class="status" style="background:#f0fff4;color:#22c55e;">Resuelto</span>
      </div>

      <div class="ticket-item">
        <div class="ticket-title">#990 · Error al abrir Outlook</div>
        <div class="ticket-meta">Hace 1 semana · Prioridad Normal</div>
        <span class="status" style="background:#f0fff4;color:#22c55e;">Resuelto</span>
      </div>
    </div>
  </div>

<script>
  const editor = new window.Tiptap.Editor({
    element: document.querySelector('#editor'),
    extensions: [window.Tiptap.StarterKit],
    content: '<p>Escribe aquí los detalles del problema...</p>',
  });

  document.querySelectorAll('.toolbar-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const cmd = btn.dataset.cmd;
      if (cmd === 'bold') editor.chain().focus().toggleBold().run();
      if (cmd === 'italic') editor.chain().focus().toggleItalic().run();
      if (cmd === 'bulletList') editor.chain().focus().toggleBulletList().run();
      if (cmd === 'orderedList') editor.chain().focus().toggleOrderedList().run();
      if (cmd === 'heading') editor.chain().focus().toggleHeading({ level: btn.dataset.level }).run();
    });
  });

  // Pegar imágenes con Ctrl+V
  document.getElementById('editor').addEventListener('paste', (e) => {
    for (let item of e.clipboardData.items) {
      if (item.type.includes('image')) {
        const blob = item.getAsFile();
        const reader = new FileReader();
        reader.onload = (ev) => editor.chain().focus().setImage({ src: ev.target.result }).run();
        reader.readAsDataURL(blob);
      }
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>