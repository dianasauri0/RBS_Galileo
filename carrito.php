<?php
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

// Verificar si el usuario está logueado
$usuario_logueado = isset($_SESSION['usuario_id']);
$usuario_rol = $usuario_logueado ? $_SESSION['rol'] : '';

// Redirigir a la página de inicio si no está logueado
if (!$usuario_logueado) {
    header("Location: index.php");
    exit();
}

// Obtener el ID del usuario
$usuario_id = $_SESSION['usuario_id'];

// Obtener los productos del carrito
$productos = [];
$sql = "SELECT p.producto_id, p.descripcion AS nombre, p.precio, pe.pendiente_id, COUNT(pe.pendiente_id) AS cantidad
        FROM pendientes pe
        JOIN productos p ON pe.producto_id = p.producto_id
        WHERE pe.usuario_id = ? AND pe.estado = 'carrito'
        GROUP BY p.producto_id, p.descripcion, p.precio
        ORDER BY p.descripcion";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $usuario_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['subtotal'] = $row['precio'] * $row['cantidad'];
        $productos[] = $row;
    }
    $stmt->close();
} else {
    echo "Error en la consulta: " . $stmt->error;
}

// Manejo de eliminación de productos
if (isset($_GET['eliminar'])) {
    $pendiente_id = intval($_GET['eliminar']);
    
    // Verificar la existencia del producto con el pendiente_id
    $sql = "SELECT COUNT(*) AS total FROM pendientes WHERE pendiente_id = ? AND usuario_id = ? AND estado = 'carrito'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $pendiente_id, $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['total'];
    $stmt->close();

    // Eliminar solo una instancia si existe
    if ($count > 0) {
        $sql = "DELETE FROM pendientes WHERE pendiente_id = ? AND usuario_id = ? AND estado = 'carrito' LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $pendiente_id, $usuario_id);

        if ($stmt->execute()) {
            // Redirigir de vuelta al carrito
            header("Location: carrito.php");
            exit();
        } else {
            echo "Error en la eliminación: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <style>
        html, body {
    height: 100%;
    margin: 0;
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
}

body {
    flex: 1;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background-color: #333;
    color: white;
    position: relative;
}

.header-left,
.header-right {
    display: flex;
    align-items: center;
}

.header-left button,
.header-right button {
    background-color: transparent;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 16px;
    margin-right: 15px;
    display: flex;
    align-items: center;
}

.header-left button:hover,
.header-right button:hover {
    color: #ccc;
}

.header-right button:last-child {
    margin-right: 0;
}

.header-right button .icon {
    margin-right: 5px;
}

.header-left button .icon-slider {
    font-size: 20px;
    margin-right: 10px;
}

/* Estilos del menú desplegable */
#menuDropdown {
    position: absolute;
    top: 50px;
    left: 20px;
    background-color: #fff;
    color: #333;
    border-radius: 4px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    display: none;
    z-index: 1000;
}

#menuDropdown ul {
    list-style: none;
    padding: 10px;
    margin: 0;
}

#menuDropdown ul li {
    margin-bottom: 10px;
}

#menuDropdown ul li:last-child {
    margin-bottom: 0;
}

#menuDropdown ul li a {
    text-decoration: none;
    color: #333;
}

#menuDropdown ul li a:hover {
    color: #555;
}

.container {
    width: 80%;
    margin: 0 auto;
    padding-top: 20px;
    flex: 1; /* Permite que el contenedor principal expanda su tamaño para ocupar el espacio restante */
}

h1 {
    text-align: center;
    color: #333;
}

.product {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #ccc;
}

.product button {
    background-color: #f44336;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}

.product button:hover {
    background-color: #d32f2f;
}

.total {
    font-weight: bold;
    font-size: 18px;
    text-align: right;
    padding: 10px;
    border-top: 1px solid #ccc;
}

.buy-now {
    display: block;
    width: 100%;
    text-align: center;
    background-color: #4CAF50;
    color: white;
    padding: 10px;
    margin-top: 20px;
    cursor: pointer;
    text-decoration: none;
}

.buy-now:hover {
    background-color: #45a049;
}

footer {
    background-color: #111;
    color: #ddd;
    padding: 20px;
    font-family: Arial, sans-serif;
    width: 100%;
    bottom: 0;
    position: relative;
    box-sizing: border-box; /* Asegura que el padding y el borde no aumenten el ancho total */
}

.footer-top {
    border-bottom: 1px solid #444;
    padding-bottom: 10px;
}

.footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    max-width: 100%; /* Asegura que el contenido no exceda el ancho del contenedor */
    box-sizing: border-box;
}

.footer-section {
    flex: 1;
    min-width: 150px;
    margin-right: 15px;
    box-sizing: border-box;
}

.footer-section h5 {
    border-bottom: 1px solid #888;
    font-size: 1.2em;
    padding-bottom: 5px;
    margin-bottom: 10px;
    color: #fff;
}

.footer-section p {
    margin: 5px 0;
    font-size: 14px;
}

.footer-section a {
    color: #ddd;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section a:hover {
    color: #fff;
    text-decoration: underline;
}

.social-icon {
    display: block;
    margin: 5px 0;
}

.social-media a {
    margin-right: 10px;
    display: flex;
    align-items: center;
    font-size: 14px;
}

.social-media i {
    margin-right: 6px;
}

.footer-bottom {
    text-align: center;
    padding-top: 10px;
    border-top: 1px solid #444;
}

.footer-bottom p {
    margin: 5px 0;
    font-size: 14px;
}

.footer-bottom a {
    color: #ddd;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-bottom a:hover {
    color: #fff;
    text-decoration: underline;
}
    </style>
</head>
<body>

<header>
    <div class="header-left">
        <button id="sliderBtn" onclick="toggleMenu()">
            <span class="icon-slider">&#9776;</span> 
            Menú
        </button>
        <button onclick="window.location.href='index.php'">Inicio</button>
    </div>

    <div id="menuDropdown">
            <ul id="menuOptions">
                <?php if ($usuario_logueado && $usuario_rol === 'administrador'): ?>
                    <li><a href="perfil.php">Mi Perfil</a></li>
                    <li><a href="mis_pedidos.php">Mis Pedidos</a></li>
                    <li><a href="gestionar_productos.php">Gestionar Productos y Ventas</a></li>
                <?php elseif ($usuario_logueado && $usuario_rol === 'cliente'): ?>
                    <li><a href="perfil.php">Mi Perfil</a></li>
                    <li><a href="mis_pedidos.php">Mis Pedidos</a></li>
                <?php else: ?>
                    <!-- Opciones disponibles para usuarios no logueados -->
                    <li><a href="login.html">Iniciar Sesión</a></li>
                    <li><a href="registro.html">Registrarse</a></li>
                <?php endif; ?>
            </ul>
    </div>

    <div class="header-right">
        <button id="loginBtn" onclick="handleLoginLogout()">
            <span class="icon">&#128100;</span> 
            <?php echo $usuario_logueado ? 'Cerrar Sesión' : 'Iniciar Sesión'; ?>
        </button>
        <button id="cartBtn" onclick="window.location.href='carrito.php'">
            <span class="icon">&#128722;</span> Carrito
        </button>
    </div>
</header>

<div class="container">
    <h1>Tu Carrito de Compras</h1>

    <div id="cartContent">
        <?php if (!empty($productos)): ?>
            <?php 
            $total = 0;
            foreach ($productos as $producto): 
                $total += $producto['subtotal'];
            ?>
                <div class='product'>
                    <span><?php echo htmlspecialchars($producto['nombre']); ?> (x<?php echo $producto['cantidad']; ?>)</span>
                    <span><?php echo htmlspecialchars($producto['subtotal']); ?>€</span>
                    <button onclick='eliminarProducto(<?php echo $producto['pendiente_id']; ?>)'>Eliminar</button>
                </div>
            <?php endforeach; ?>
            <div class="total">Total: <?php echo $total; ?>€</div>
            <a href="comprar.php" class="buy-now">Comprar Ahora</a>
        <?php else: ?>
            <p>Tu carrito está vacío.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    <div class="footer-top">
        <div class="footer-content">
            <div class="footer-section about">
                <h5>Sobre Nosotros</h5>
                <p>Mi Tienda Online es tu destino para las mejores ofertas en moda y accesorios. Estamos comprometidos a ofrecerte una experiencia de compra única y productos de alta calidad.</p>
            </div>
            <div class="footer-section help">
                <h5>Ayuda</h5>
                <p><a href="preguntas.html">Preguntas Frecuentes</a></p>
                <p><a href="stecnico.html">Soporte Técnico</a></p>
                <p><a href="privacidad.html">Política de Privacidad</a></p> <!-- Actualizado a Política de Privacidad -->
            </div>
            <div class="footer-section contact">
                <h5>Contacto</h5>
                <p>Teléfono: +11 41929678</p>
                <p>Email: <a href="mailto:contacto@mitiendaonline.com">contacto@mitiendaonline.com</a></p>
            </div>
            <div class="footer-section social-media">
                <h5>Síguenos</h5>
                <a href="https://facebook.com" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i> Facebook</a>
                <a href="https://instagram.com" target="_blank" class="social-icon"><i class="fab fa-instagram"></i> Instagram</a>
                <a href="https://twitter.com" target="_blank" class="social-icon"><i class="fab fa-twitter"></i> Twitter</a>
                <a href="https://linkedin.com" target="_blank" class="social-icon"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Mi Tienda Online. Todos los derechos reservados.</p>
        <p><a href="#">Términos y Condiciones</a> | <a href="politica.html">Política de Privacidad</a></p> <!-- Actualizado -->
    </div>
</footer>

<script>
    function toggleMenu() {
        var menu = document.getElementById("menuDropdown");
        menu.style.display = menu.style.display === "block" ? "none" : "block";
    }

    function handleLoginLogout() {
        var isLoggedIn = <?php echo $usuario_logueado ? 'true' : 'false'; ?>;
        if (isLoggedIn) {
            window.location.href = 'index.php?logout=true';
        } else {
            window.location.href = 'login.html';
        }
    }

    function eliminarProducto(id) {
        window.location.href = 'carrito.php?eliminar=' + id;
    }
</script>

</body>
</html>