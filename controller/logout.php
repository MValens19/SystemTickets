<?php
// logout.php
session_start();

// Destruimos todas las variables de sesión
$_SESSION = array();

// Borramos la cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruimos la sesión
session_destroy();

// Redirigimos al login
header("Location: http://localhost/Tickets/views/login.php");
exit;
?>