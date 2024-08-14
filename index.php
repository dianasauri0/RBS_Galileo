<!DOCTYPE html>
<html lang="es">
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

// Procesar la adición de productos al carrito
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id']) && isset($_POST['cantidad'])) {
    $usuario_id = $_SESSION['usuario_id']; // Asegúrate de que el usuario esté logueado
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    // Verificar si el producto ya está en el carrito
    $stmt = $conn->prepare("SELECT * FROM carrito WHERE usuario_id = ? AND producto_id = ?");
    $stmt->bind_param("ii", $usuario_id, $producto_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si ya está en el carrito, actualizar la cantidad
        $stmt = $conn->prepare("UPDATE carrito SET cantidad = cantidad + ? WHERE usuario_id = ? AND producto_id = ?");
        $stmt->bind_param("iii", $cantidad, $usuario_id, $producto_id);
    } else {
        // Si no está en el carrito, agregarlo
        $stmt = $conn->prepare("INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $usuario_id, $producto_id, $cantidad);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Producto añadido al carrito correctamente');</script>";
    } else {
        echo "<script>alert('Error al añadir el producto al carrito');</script>";
    }

    $stmt->close();
}

$conn->close();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <style>
        /* Configuración de Flexbox para la página completa */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: white;
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

        /* Contenedor de contenido para empujar el footer hacia abajo */
        .content {
            flex: 1;
        }

        footer {
            background-color: #333;
            color: white;
            padding: 40px 20px;
        }

        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .footer-section {
            flex: 1;
            min-width: 200px;
            margin-right: 20px;
        }

        .footer-section h5 {
            border-bottom: 2px solid #f4f4f4;
            font-size: 1.8em;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .footer-section p {
            margin: 10px 0;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin: 10px 0;
        }

        .footer-section ul li a {
            color: #ddd;
            text-decoration: none;
        }

        .footer-section ul li a:hover {
            text-decoration: underline;
        }

        .social-icon {
            display: block;
            margin: 5px;
        }

        .social-media a {
            margin-right: 15px;
            color: #ddd;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .social-media i {
            margin-right: 8px;
        }

        .social-media a:hover {
            text-decoration: underline;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 20px;
        }

        .footer-bottom a {
            color: #ddd;
            text-decoration: none;
        }

        .footer-bottom a:hover {
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
    </div>
    <div class="header-right">
        <button id="loginBtn" onclick="window.location.href='login.php'">
            <span class="icon">&#128100;</span> 
            Iniciar Sesión
        </button>
        <button id="registerBtn" onclick="window.location.href='registro.php'">
            <span class="icon">&#9997;</span> 
            Registrarse
        </button>
        <button id="cartBtn" onclick="window.location.href='carrito.php'">
            <span class="icon">&#128722;</span> 
            Carrito
        </button>
    </div>
</header>

<!-- Menú desplegable -->
<div id="menuDropdown">
    <ul id="menuOptions">
        <!-- Aquí se agregarán las opciones según el rol -->
    </ul>
</div>

<!-- Contenido principal -->
<div class="content">
    <!-- Aquí va tu contenido principal -->
</div>

<script>
    function toggleMenu() {
        var menu = document.getElementById("menuDropdown");
        menu.style.display = menu.style.display === "block" ? "none" : "block";
    }

    function cargarOpcionesMenu(rol) {
        var menuOptions = document.getElementById("menuOptions");
        menuOptions.innerHTML = ''; // Limpiar el menú

        if (rol === "admin") {
            menuOptions.innerHTML += '<li><a href="dashboard.html">Panel de Control</a></li>';
            menuOptions.innerHTML += '<li><a href="usuarios.html">Gestionar Usuarios</a></li>';
            menuOptions.innerHTML += '<li><a href="productos.html">Gestionar Productos</a></li>';
        } else if (rol === "usuario") {
            menuOptions.innerHTML += '<li><a href="perfil.html">Mi Perfil</a></li>';
            menuOptions.innerHTML += '<li><a href="mis_pedidos.html">Mis Pedidos</a></li>';
        }
    }

    // Verificar si ya hay un usuario logueado al cargar la página
    window.onload = function() {
        var rol = sessionStorage.getItem("rolUsuario");
        if (rol) {
            cargarOpcionesMenu(rol);

            // Cambiar el botón de "Iniciar Sesión" a "Cerrar Sesión"
            var loginBtn = document.getElementById("loginBtn");
            loginBtn.innerHTML = '<span class="icon">&#128100;</span> Cerrar Sesión';
            loginBtn.onclick = function() {
                logoutUser();
            };

            // Ocultar el botón de registro
            var registerBtn = document.getElementById("registerBtn");
            registerBtn.style.display = 'none';
        }
    }

    function logoutUser() {
        // Limpiar la sesión
        sessionStorage.removeItem("rolUsuario");

        // Cambiar el botón de vuelta a "Iniciar Sesión"
        var loginBtn = document.getElementById("loginBtn");
        loginBtn.innerHTML = '<span class="icon">&#128100;</span> Iniciar Sesión';
        loginBtn.onclick = function() {
            window.location.href = 'login.php';
        };

        // Mostrar el botón de registro
        var registerBtn = document.getElementById("registerBtn");
        registerBtn.style.display = 'inline-block';

        // Limpiar las opciones del menú
        var menuOptions = document.getElementById("menuOptions");
        menuOptions.innerHTML = '';

        alert("Sesión cerrada");
    }
</script>

<footer>
    <div class="footer-content">
        <div class="footer-section about">
            <h5>Sobre Nosotros</h5>
            <p>Mi Tienda Online es tu destino para las mejores ofertas en moda y accesorios. Estamos comprometidos a ofrecerte una experiencia de compra única y productos de alta calidad.</p>
        </div>
        <div class="footer-section contact">
            <h5>Contacto</h5>
            <p>Teléfono: +123 456 7890</p>
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
    <div class="footer-bottom">
        <p>&copy; 2024 Mi Tienda Online. Todos los derechos reservados.</p>
        <p><a href="#">Términos y Condiciones</a></p>
        <a href="#">Política de Privacidad</a>
    </div>
</footer>
</body>
</html>
