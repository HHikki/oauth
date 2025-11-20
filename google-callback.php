<?php
// Callback para manejar la autenticación con Google
require_once 'includes/session.php';
require_once 'config/firebase-config.php';
require_once 'includes/auth.php';

header('Content-Type: application/json');

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit();
}

// Obtener los datos del POST
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['idToken']) || !isset($data['email']) || !isset($data['uid'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit();
}

// Verificar el token con Firebase (opcional pero recomendado para producción)
$config = require 'config/firebase-config.php';
$auth = new FirebaseAuth($config['apiKey']);

$verification = $auth->verifyIdToken($data['idToken']);

if (!$verification['success']) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Token inválido']);
    exit();
}

// Crear sesión del usuario
setUserSession(
    $data['uid'],
    $data['email'],
    $data['idToken'],
    $data['displayName'] ?? null,
    $data['photoURL'] ?? null
);

echo json_encode([
    'success' => true,
    'message' => 'Sesión iniciada correctamente'
]);
