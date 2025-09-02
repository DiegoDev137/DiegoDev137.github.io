<?php
$conex = mysqli_connect("localhost", "root", "", "skillifyregistro");
if (!$conex) {
    die("Error de conexión: " . mysqli_connect_error());
}

header('Content-Type: application/json; charset=utf-8');

// Verificar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
    exit;
}

// Recibir datos
$name  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');

// Validaciones simples
if (strlen($name) < 2) {
    echo json_encode([
        'success' => false,
        'message' => 'Nombre inválido'
    ]);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Email inválido'
    ]);
    exit;
}

// Evitar duplicados (si quieres que no se repita el email)
$check = $conex->prepare("SELECT id FROM usuarios WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Este email ya está registrado'
    ]);
    exit;
}
$check->close();

// Insertar en la DB
$stmt = $conex->prepare("INSERT INTO usuarios (nombre, email) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $email);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Registro exitoso',
        'user'    => [
            'id'     => $stmt->insert_id,
            'name'   => $name,
            'email'  => $email
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al registrar usuario: ' . $stmt->error
    ]);
}

$stmt->close();
$conex->close();
?>
