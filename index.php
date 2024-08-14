<!DOCTYPE html>
<html lang="es">
<?php
$servername = "sql312.byethost4.com";
$username = "b4_36189857";
$password = "name12341";
$dbname = "b4_36189857_galileo";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "Conectado exitosamente";

$conn->close();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
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

</body>
</html>
