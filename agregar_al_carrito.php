<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit();
}

// Configuración de la base de datos
$servername = "sql312.byethost4.com";
$username = "b4_36189857";
$password = "name12341";
$dbname = "b4_36189857_galileo";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die('Conexión fallida: ' . $conn->connect_error);
}

// Obtener el producto_id del formulario
$producto_id = $_POST['producto_id'];

// Obtener el usuario_id de la sesión
$usuario_id = $_SESSION['usuario_id'];

// Preparar y ejecutar la consulta para insertar un nuevo registro en pendientes
$stmt = $conn->prepare("INSERT INTO pendientes (estado, producto_id, usuario_id) VALUES ('carrito', ?, ?)");
$stmt->bind_param("ii", $producto_id, $usuario_id);

if ($stmt->execute()) {
    // No hacer nada en caso de éxito
    exit(); // Asegura que el script termine aquí
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
