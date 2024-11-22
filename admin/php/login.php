<?php
header('Content-Type: application/json');

// Leer el cuerpo de la solicitud
$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody, true);

// Validar campos
if (!isset($data['username']) || !isset($data['password'])) {
    echo json_encode(array('success' => false, 'message' => 'Faltan datos.'));
    exit;
}

$username = $data['username'];
$password = $data['password'];

// Leer usuarios desde users.json
$usersFile = '../../data/users.json';
if (!file_exists($usersFile)) {
    echo json_encode(array('success' => false, 'message' => 'Archivo de usuarios no encontrado.'));
    exit;
}

$users = json_decode(file_get_contents($usersFile), true);
$validUser = false;

// Validar credenciales
foreach ($users as $user) {
    if ($user['username'] === $username && $user['password'] === $password) {
        $validUser = true;
        break;
    }
}

if ($validUser) {
    session_start();
    $_SESSION['logged_in'] = true;
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false, 'message' => 'Usuario o contraseña incorrectos.'));
}
?>
