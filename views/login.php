<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login · Soporte TI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      position: relative;
      background: #f5f5f5;
    }

    /* FONDO PERLA ANIMADO */
    .pearl-bg {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: linear-gradient(135deg, #e0e0e0 0%, #f8f8f8 50%, #e8e8e8 100%);
      opacity: 0.7;
      z-index: 1;
    }
    .pearl-bg::before, .pearl-bg::after {
      content: '';
      position: absolute;
      width: 600px;
      height: 600px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      filter: blur(120px);
      animation: float 20s infinite ease-in-out;
    }
    .pearl-bg::before {
      top: -200px;
      left: -200px;
      animation-delay: 0s;
    }
    .pearl-bg::after {
      bottom: -200px;
      right: -200px;
      animation-delay: 10s;
    }
    @keyframes float {
      0%, 100% { transform: translate(0, 0) rotate(0deg); }
      50% { transform: translate(100px, -100px) rotate(180deg); }
    }

    .login-container {
      width: 100%;
      max-width: 420px;
      padding: 3rem 2rem;
      text-align: center;
      position: relative;
      z-index: 10;
    }
    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(12px);
      padding: 3rem 2.5rem;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.12);
      border: 1px solid rgba(255,255,255,0.3);
    }
    .logo {
      font-weight: 700;
      font-size: 2.1rem;
      margin-bottom: 0.5rem;
      color: #000;
    }
    .subtitle {
      color: #555;
      font-size: 1.1rem;
      margin-bottom: 2.5rem;
    }
    .form-control {
      border: none;
      border-bottom: 2px solid #ddd;
      border-radius: 0;
      padding: 14px 0;
      font-size: 1rem;
      background: transparent;
      transition: border-color 0.3s;
    }
    .form-control:focus {
      border-color: #000;
      box-shadow: none;
    }
    .btn-login {
      background: #000;
      color: white;
      width: 100%;
      padding: 16px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 1.1rem;
      margin-top: 2rem;
      transition: all 0.3s;
    }
    .btn-login:hover {
      background: #333;
      transform: translateY(-2px);
    }
    .footer {
      margin-top: 3rem;
      color: #888;
      font-size: 0.9rem;
    }
    .footer a {
      color: #000;
      text-decoration: none;
      font-weight: 600;
    }
    .footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <!-- Fondo perla animado -->
  <div class="pearl-bg"></div>

  <div class="login-container">
    <div class="login-card">
      <div class="logo">Soporte TI</div>
      <div class="subtitle">Ingresa tus credenciales para continuar</div>

      <form action="index.php" method="POST">
        <div class="mb-4">
          <input type="text" name="usuario" class="form-control" placeholder="Usuario" required autofocus>
        </div>
        <div class="mb-4">
          <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
        </div>
        <button type="submit" class="btn-login">Iniciar sesión</button>
      </form>

      <div class="footer mt-5">
        Sistema diseñado y desarrollado por <a href="https://tuweb.com" target="_blank">Moisés Valencia</a>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>