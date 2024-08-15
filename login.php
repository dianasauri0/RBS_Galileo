<?php
// Iniciar la sesión
session_start();

// Datos de conexión a la base de datos
$servername = "sql312.byethost4.com";
$db_username = "b4_36189857";
$db_password = "name12341";
$dbname = "b4_36189857_galileo";

// Crear conexión
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die('Conexión fallida: ' . $conn->connect_error);
}

// Obtener datos del formulario
$input_username = trim($_POST['username'] ?? '');
$input_password = trim($_POST['password'] ?? '');

// Validar los datos del formulario
if (empty($input_username) || empty($input_password)) {
    echo '<script>alert("Nombre de usuario y contraseña son obligatorios"); window.location.href = "login.html";</script>';
    exit();
}

// Preparar la consulta SQL para obtener la contraseña del usuario
$stmt = $conn->prepare("SELECT usuario_id, contrasenia, rol FROM usuarios WHERE nombre = ?");
if (!$stmt) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}

// Vincular el parámetro y ejecutar la consulta
$stmt->bind_param("s", $input_username);
$stmt->execute();
$stmt->store_result();

// Verificar si el usuario existe
if ($stmt->num_rows > 0) {
    // Vincular el resultado a una variable
    $stmt->bind_result($usuario_id, $hashed_password, $rol);
    $stmt->fetch();

    // Verificar la contraseña
    if (password_verify($input_password, $hashed_password)) {
        // Inicio de sesión exitoso
        $_SESSION['usuario_id'] = $usuario_id;  // Guardar ID del usuario en la sesión
        $_SESSION['rol'] = $rol;  // Guardar rol en la sesión
        header('Location: index.php');  // Redirigir a la página principal
        exit();
    } else {
        // Contraseña incorrecta
        echo '<script>alert("Contraseña incorrecta"); window.location.href = "login.html";</script>';
    }
} else {
    // Nombre de usuario no encontrado
    echo '<script>alert("Nombre de usuario no encontrado"); window.location.href = "login.html";</script>';
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
