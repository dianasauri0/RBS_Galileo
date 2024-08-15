<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir a la página de inicio de sesión si no está logueado
    header("Location: login.php");
    exit();
}

// Obtener el rol del usuario
$rol = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
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
    <div class="header-right">
        <button id="loginBtn" onclick="logoutUser()">
            <span class="icon">&#128100;</span> Cerrar Sesión
        </button>
        <button id="cartBtn" onclick="window.location.href='carrito.php'">
            <span class="icon">&#128722;</span> Carrito
        </button>
    </div>
</header>

<!-- Menú desplegable -->
<div id="menuDropdown">
    <ul id="menuOptions">
        <?php if ($rol === 'admin'): ?>
            <li><a href="dashboard.php">Panel de Control</a></li>
            <li><a href="usuarios.php">Gestionar Usuarios</a></li>
            <li><a href="productos.php">Gestionar Productos</a></li>
        <?php elseif ($rol === 'usuario'): ?>
            <li><a href="perfil.php">Mi Perfil</a></li>
            <li><a href="mis_pedidos.php">Mis Pedidos</a></li>
        <?php endif; ?>
    </ul>
</div>

<div class="container">
    <h1>Tu Carrito de Compras</h1>

    <div id="cartContent">
        <?php
        // Aquí deberías mostrar el contenido del carrito que está almacenado en la base de datos o en la sesión
        // Ejemplo:
        if (!empty($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $producto) {
                echo "<div class='product'>";
                echo "<span>{$producto['nombre']}</span>";
                echo "<span>{$producto['precio']}</span>";
                echo "<button onclick='eliminarProducto({$producto['id']})'>Eliminar</button>";
                echo "</div>";
            }
        } else {
            echo "<p>Tu carrito está vacío.</p>";
        }
        ?>
    </div>
</div>

<script>
    function toggleMenu() {
        var menu = document.getElementById("menuDropdown");
        menu.style.display = menu.style.display === "block" ? "none" : "block";
    }

    function logoutUser() {
        window.location.href = 'logout.php'; // Redirigir a una página que maneje el logout en PHP
    }
</script>

</body>
</html>
