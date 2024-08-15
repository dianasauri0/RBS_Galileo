<?php

// Configuración de la base de datos
$servername = "sql312.byethost4.com";
$username = "b4_36189857";
$password = "name12341";
$dbname = "b4_36189857_galileo";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$input_username = trim(isset($_POST['username']) ? $_POST['username'] : '');
$input_password = trim(isset($_POST['password']) ? $_POST['password'] : '');
$input_confirm_password = trim(isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '');

// Verificar que las contraseñas coincidan
if ($input_password !== $input_confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden']);
    $conn->close();
    exit();
}

// Verificar si el nombre de usuario ya existe
$stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE nombre = ?");
$stmt->bind_param("s", $input_username);
$stmt->execute();
$stmt->bind_result($user_count);
$stmt->fetch();
$stmt->close();

if ($user_count > 0) {
    echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya está registrado']);
    $conn->close();
    exit();
}

// Hash de la contraseña
$hashed_password = password_hash($input_password, PASSWORD_DEFAULT);

// Insertar nuevo usuario
$rol="usuario";
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, contrasenia, rol) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $input_username, $hashed_password, $rol);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente']);
    session_start();
    $_SESSION['rol'] = $rol; // Guardar rol en la sesión
    header('Location: login.html'); 
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario']);
}

$stmt->close();
$conn->close();
?>
