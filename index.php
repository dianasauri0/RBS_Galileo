<!DOCTYPE html>
<html lang="es">
<?php
session_start();

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

// Verificar si el usuario está logueado y obtener el rol si es necesario
$usuario_rol = '';
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    
    // Consultar el rol del usuario en la base de datos
    $stmt = $conn->prepare("SELECT rol FROM usuarios WHERE usuario_id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $usuario_rol = $row['rol'];
    } else {
        echo "<script>alert('Usuario no encontrado.'); window.location.href='login.php';</script>";
        exit();
    }

    $stmt->close();
}

// Manejar el logout
if (isset($_GET['logout'])) {
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    echo "<script>window.location.href='index.php';</script>"; // Refresca la página
    exit();
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($_SESSION['usuario_id']) ? 'Página Principal' : 'Cursos En Línea'; ?></title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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
        .courses {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }
        .course {
            background-color: white;
            margin: 10px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 250px;
            text-align: center;
        }
        .course-title {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }
        .course-instructor {
            color: #777;
            margin-bottom: 15px;
        }
        .course-price {
            font-size: 20px;
            margin-bottom: 10px;
            color: #007bff;
        }
        .course button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .course button:hover {
            background-color: #0056b3;
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

        <div id="menuDropdown">
            <ul id="menuOptions">
                <?php if ($usuario_rol === 'admin'): ?>
                    <li><a href="perfil.php">Mi Perfil</a></li>
                    <li><a href="mis_pedidos.php">Mis Pedidos</a></li>
                    <li><a href="gestionar_productos.php">Gestionar Productos</a></li>
                <?php elseif ($usuario_rol === 'usuario'): ?>
                    <li><a href="perfil.php">Mi Perfil</a></li>
                    <li><a href="mis_pedidos.php">Mis Pedidos</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="header-right">
        <button id="loginBtn" onclick="handleLoginLogout()">
            <span class="icon">&#128100;</span> 
            <?php echo isset($_SESSION['usuario_id']) ? 'Cerrar Sesión' : 'Iniciar Sesión'; ?>
        </button>
        <?php if (!isset($_SESSION['usuario_id'])): ?>
            <button id="registerBtn" onclick="window.location.href='registro.html'">
                <span class="icon">&#9997;</span> 
                Registrarse
            </button>
        <?php endif; ?>
        <button id="cartBtn" onclick="window.location.href='carrito.php'" style="<?php echo isset($_SESSION['usuario_id']) ? 'display:inline-block;' : 'display:none;'; ?>">
            <span class="icon">&#128722;</span> 
            Carrito
        </button>
    </div>
</header>
</head>

    <script>
        function showLoginAlert() {
            alert("Debes iniciar sesión para añadir productos al carrito.");
        }
    </script>

<body>
<!-- Lista de Productos -->
<div class="Productos">
<?php
// Obtener los productos desde la base de datos
$sql = "SELECT producto_id, descripcion, precio FROM productos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='Producto'>";
        echo "<h3 class='Descripcion-Producto'>" . $row['descripcion'] . "</h3>";
        echo "<p class='Precio-Producto'>$" . $row['precio'] . "</p>";

        // Mostrar el botón según el estado de la sesión
        if (isset($_SESSION['usuario_id'])) {
            echo "<button onclick='addToCart(" . $row['producto_id'] . ")'>Añadir al Carrito</button>";
        } else {
            echo "<button onclick='showLoginAlert()'>Añadir al Carrito</button>";
        }

        echo "</div>";
    }
} else {
    echo "<p>No hay productos disponibles.</p>";
}
?>
</div>

<footer>
    <div class="footer-content">
        <div class="footer-section">
            <h5>Sobre Nosotros</h5>
            <p>Somos una plataforma dedicada a ofrecer cursos en línea de alta calidad.</p>
        </div>
        <div class="footer-section">
            <h5>Enlaces</h5>
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Cursos</a></li>
                <li><a href="#">Contacto</a></li>
                <li><a href="#">Términos y Condiciones</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h5>Contacto</h5>
            <p>Dirección: Calle Falsa 123, Ciudad, País</p>
            <p>Teléfono: +123 456 7890</p>
            <p>Email: info@ejemplo.com</p>
        </div>
        <div class="footer-section social-media">
            <h5>Síguenos</h5>
            <a href="#" class="social-icon"><i class="fa fa-facebook"></i> Facebook</a>
            <a href="#" class="social-icon"><i class="fa fa-twitter"></i> Twitter</a>
            <a href="#" class="social-icon"><i class="fa fa-instagram"></i> Instagram</a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Todos los derechos reservados. <a href="#">Política de Privacidad</a></p>
    </div>
</footer>

<script>
function toggleMenu() {
    var menu = document.getElementById("menuDropdown");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

function handleLoginLogout() {
    var isLoggedIn = <?php echo isset($_SESSION['usuario_id']) ? 'true' : 'false'; ?>;
    if (isLoggedIn) {
        window.location.href = 'index.php?logout=true';
    } else {
        window.location.href = 'login.html';
    }
}

function addToCart(productId) {
    // Aquí puedes agregar el código para añadir el producto al carrito
    alert('Producto ' + productId + ' añadido al carrito.');
}
</script>

</body>
</html>
