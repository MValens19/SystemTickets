<?php
// api/crear_usuario.php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}

$roles_permitidos = ['admin', 'tecnico'];
if (!in_array($_SESSION['rol'], $roles_permitidos)) {
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$nombre = trim($data['nombre'] ?? '');
$puesto = trim($data['puesto'] ?? '');
$area = trim($data['area'] ?? '');
$usuario = trim($data['usuario'] ?? '');
$password = $data['password'] ?? '';
$password2 = $data['password2'] ?? '';
$rol = $data['rol'] ?? 'usuario';

// Validación
if (empty($nombre) || empty($area) || empty($usuario) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Todos los campos obligatorios']);
    exit;
}

if ($password !== $password2) {
    echo json_encode(['success' => false, 'error' => 'Las contraseñas no coinciden']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'error' => 'La contraseña debe tener al menos 6 caracteres']);
    exit;
}

// Verificar si el usuario ya existe
$sql = "SELECT id_usuario FROM usuarios WHERE usuario = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario]);
if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => false, 'error' => 'El nombre de usuario ya existe']);
    exit;
}

// Insertar usuario (contraseña sin cifrar por ahora – luego cambiamos a password_hash)
try {
    $sql = "INSERT INTO usuarios (nombre, puesto, area, usuario, password, rol) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $puesto, $area, $usuario, $password, $rol]);

    echo json_encode(['success' => true, 'message' => 'Usuario creado correctamente']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error al crear usuario']);
}
exit;
?>