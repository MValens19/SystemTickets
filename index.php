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
        body {
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: #1f1f1f;
            margin: 0;
            height: 100vh;
            overflow: hidden;
        }

        .app-container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: #fafafa;
            border-right: 1px solid #eaeaea;
            padding: 1.5rem;
            overflow-y: auto;
            flex-shrink: 0;
            transition: width .3s ease, transform .3s ease;
        }

        .sidebar.collapsed {
            width: 0;
            padding: 0;
            overflow: hidden;
        }

        .logo {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 500;
            transition: .2s;
            white-space: nowrap;
        }

        .nav-item:hover,
        .nav-item.active {
            background: #000;
            color: white;
        }

        /* Área principal */
        .main-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* Navbar superior */
        .topbar {
            height: 64px;
            background: #fff;
            border-bottom: 1px solid #eaeaea;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-shrink: 0;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 10px;
            transition: .2s;
        }

        .user-menu:hover {
            background: #f0f0f0;
        }

        /* Toggle pegado al borde del sidebar */
        .toggle-btn {
            position: absolute;
            left: 280px;
            top: 0;
            height: 64px;
            width: 64px;
            background: transparent;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: left .3s ease;
            z-index: 100;
        }

        .sidebar.collapsed~.main-area .toggle-btn {
            left: 0;
        }

        /* Contenido principal - ahora sí 100% dinámico */
        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 3rem;
            transition: padding-left .3s ease;
        }

        .sidebar.collapsed~.main-area .main-content {
            padding-left: 2rem;
            /* más espacio cuando está colapsado */
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
            margin: 3rem 0;
        }

        .stat-card {
            background: #f7f7f7;
            padding: 1.8rem;
            border-radius: 14px;
            text-align: center;
        }

        .stat-number {
            font-size: 2.4rem;
            font-weight: 700;
        }

        .priority-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 12px;
        }

        .status {
            font-size: 0.85rem;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 500;
        }

        .ticket-row {
            padding: 1rem 0;
            border-top: 1px solid #eaeaea;
        }

        .ticket-row:hover {
            background: #f7f7f7;
            border-radius: 8px;
        }
        .nav-item{display:flex;align-items:center;gap:14px;padding:12px 16px;border-radius:10px;cursor:pointer;font-weight:500;transition:.2s;}
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
   <?php include 'components/sidebar.html'; ?>
        <!-- Área principal -->
        <div class="main-area">

        <?php include 'components/ToggleSidebar.html'; ?> 
           
            <!-- Contenido principal -->
            <div class="main-content">
                <h1 class="page-title">Centro de Control TI</h1>
                <p style="color:#666;font-size:1.1rem;">Panel administrativo del área de sistemas</p>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">24</div>
                        <div>Tickets totales</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number text-danger">6</div>
                        <div>Urgentes hoy</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number text-warning">8</div>
                        <div>Pendientes</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number text-success">89%</div>
                        <div>Resueltos este mes</div>
                    </div>
                </div>

                <h2 style="font-size:1.5rem;font-weight:600;margin:3rem 0 1rem;">Tickets críticos hoy</h2>
                <div>
                    <div class="ticket-row d-flex align-items-center">
                        <span class="priority-dot" style="background:#ff4d4f;"></span>
                        <div class="flex-fill"><strong>Servidor producción DOWN</strong> — Ana Torres</div>
                        <span class="status me-3" style="background:#fff2f0;color:#ff4d4f;">Urgente</span>
                        <span class="status text-muted" style="background:#f5f5f5;">En proceso → tú</span>
                        <small class="text-muted ms-4">hace 12 min</small>
                    </div>
                    <div class="ticket-row d-flex align-items-center">
                        <span class="priority-dot" style="background:#fa8c16;"></span>
                        <div class="flex-fill"><strong>Red corporativa lenta</strong> — Dirección</div>
                        <span class="status me-3" style="background:#fff7e6;color:#fa8c16;">Alta</span>
                        <span class="status text-muted" style="background:#f5f5f5;">Pendiente</span>
                        <small class="text-muted ms-4">hace 45 min</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>