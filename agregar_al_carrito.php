<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Debes iniciar sesión para agregar productos al carrito.']);
    exit();
}

// Configuración de la base de datos
$servername = "sql110.infinityfree.com";
$username = "if0_37108824";
$password = "BjpIYhEjhN";
$dbname = "if0_37108824_fdex";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Conexión fallida: ' . $conn->connect_error]);
    exit();
}

// Obtener el producto_id del formulario
$producto_id = isset($_POST['producto_id']) ? intval($_POST['producto_id']) : 0;

// Obtener el usuario_id de la sesión
$usuario_id = $_SESSION['usuario_id'];

// Verificar que el producto_id es válido
if ($producto_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID de producto no válido.']);
    exit();
}

// Preparar y ejecutar la consulta para insertar un nuevo registro en pendientes
$stmt = $conn->prepare("INSERT INTO pendientes (estado, producto_id, usuario_id) VALUES ('carrito', ?, ?)");
$stmt->bind_param("ii", $producto_id, $usuario_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Producto agregado al carrito.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al agregar el producto al carrito: ' . $stmt->error]);
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
