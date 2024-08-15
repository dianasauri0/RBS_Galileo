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
        <button onclick="window.location.href='index.php'">
            Inicio
        </button>
    </div>
    <div class="header-right">
        <button id="loginBtn">
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

<div class="container">
    <h1>Tu Carrito de Compras</h1>
    
    <div id="cartContent">
        <!-- Aquí se mostrarán los productos del carrito -->
    </div>
    
    <div id="loginPrompt" style="display:none;">
        <p>Por favor, <a href="login.php">inicia sesión</a> para ver tu carrito de compras.</p>
    </div>
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

    window.onload = function() {
        var rol = sessionStorage.getItem("rolUsuario");

        if (rol) {
            // Mostrar el botón de "Cerrar Sesión" y ocultar "Registrarse"
            var loginBtn = document.getElementById("loginBtn");
            loginBtn.innerHTML = '<span class="icon">&#128100;</span> Cerrar Sesión';
            loginBtn.onclick = function() {
                logoutUser();
            };

            var registerBtn = document.getElementById("registerBtn");
            registerBtn.style.display = 'none';

            // Cargar opciones del menú según el rol
            cargarOpcionesMenu(rol);

            // Mostrar el contenido del carrito
            document.getElementById('cartContent').style.display = 'block';
            document.getElementById('loginPrompt').style.display = 'none';

            cargarCarrito();
        } else {
            // Si no hay sesión iniciada, ocultar el botón de "Carrito"
            document.getElementById('cartBtn').style.display = 'none';
            document.getElementById('cartContent').style.display = 'none';
            document.getElementById('loginPrompt').style.display = 'block';
        }
    }

    function cargarCarrito() {
        var carrito = JSON.parse(sessionStorage.getItem('carrito')) || [];
        var cartContent = document.getElementById('cartContent');

        if (carrito.length === 0) {
            cartContent.innerHTML = '<p>Tu carrito está vacío.</p>';
        } else {
            cartContent.innerHTML = '';
            carrito.forEach(function(producto, index) {
                cartContent.innerHTML += `
                    <div class="product">
                        <span>${producto.nombre}</span>
                        <span>${producto.precio}</span>
                        <button onclick="eliminarProducto(${index})">Eliminar</button>
                    </div>`;
            });
        }
    }

    function eliminarProducto(index) {
        var carrito = JSON.parse(sessionStorage.getItem('carrito')) || [];
        carrito.splice(index, 1);
        sessionStorage.setItem('carrito', JSON.stringify(carrito));
        cargarCarrito();
    }

    function logoutUser() {
        // Limpiar la sesión
        sessionStorage.removeItem("rolUsuario");
        sessionStorage.removeItem('carrito'); // Limpiar el carrito al cerrar sesión

        // Cambiar el botón de vuelta a "Iniciar Sesión"
        var loginBtn = document.getElementById("loginBtn");
        loginBtn.innerHTML = '<span class="icon">&#128100;</span> Iniciar Sesión';
        loginBtn.onclick = function() {
            window.location.href = 'login.php';
        };

        // Mostrar el botón de registro y ocultar el botón de "Carrito"
        var registerBtn = document.getElementById("registerBtn");
        registerBtn.style.display = 'inline-block';

        document.getElementById('cartBtn').style.display = 'none';
        document.getElementById('cartContent').style.display = 'none';
        document.getElementById('loginPrompt').style.display = 'block';

        // Limpiar las opciones del menú
        var menuOptions = document.getElementById("menuOptions");
        menuOptions.innerHTML = '';

        alert("Sesión cerrada");
    }
</script>

</body>
</html>
