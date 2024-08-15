<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos En Línea</title>
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
        <button id="loginBtn" onclick="window.location.href='login.html'">
            <span class="icon">&#128100;</span> 
            Iniciar Sesión
        </button>
        <button id="registerBtn" onclick="window.location.href='registro.html'">
            <span class="icon">&#9997;</span> 
            Registrarse
        </button>
        <button id="cartBtn" onclick="window.location.href='carrito.php'" style="display:none;">
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

<!-- Lista de Cursos -->
<div class="courses">
    <div class="course">
        <h3 class="course-title">HTML5, CSS3, JavaScript para Principiantes</h3>
        <p class="course-instructor">Juan Pedro</p>
        <p class="course-price">$15</p>
        <button>AGREGAR AL CARRITO</button>
    </div>
    <div class="course">
        <h3 class="course-title">Curso de Comida Vegetariana</h3>
        <p class="course-instructor">Juan Pedro</p>
        <p class="course-price">$15</p>
        <button>AGREGAR AL CARRITO</button>
    </div>
    <div class="course">
        <h3 class="course-title">Guitarra para Principiantes</h3>
        <p class="course-instructor">Juan Pedro</p>
        <p class="course-price">$15</p>
        <button>AGREGAR AL CARRITO</button>
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

            // Mostrar el botón del carrito
            var cartBtn = document.getElementById("cartBtn");
            cartBtn.style.display = 'inline-block';
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

        // Ocultar el botón del carrito
        var cartBtn = document.getElementById("cartBtn");
        cartBtn.style.display = 'none';

        // Limpiar las opciones del menú
        var menuOptions = document.getElementById("menuOptions");
        menuOptions.innerHTML = '';

        alert("Sesión cerrada");
    }
</script>

</body>
</html>
