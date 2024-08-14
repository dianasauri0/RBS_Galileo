<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz con Inicio de Sesión y Roles</title>
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
        /* Estilos del modal de inicio de sesión */
        #loginModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #loginModal .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
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
        <button id="loginBtn" onclick="toggleLogin()">
            <span class="icon">&#128100;</span> 
            Iniciar Sesión
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

<!-- Formulario de Inicio de Sesión (Modal) -->
<div id="loginModal">
    <div class="modal-content">
        <h2>Iniciar Sesión</h2>
        <form id="loginForm" onsubmit="return loginUser()">
            <div class="form-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Iniciar Sesión">
        </form>
    </div>
</div>

<script>
    function toggleMenu() {
        var menu = document.getElementById("menuDropdown");
        menu.style.display = menu.style.display === "block" ? "none" : "block";
    }

    function toggleLogin() {
        var loginModal = document.getElementById("loginModal");
        loginModal.style.display = "flex";
    }

    function closeLoginModal() {
        var loginModal = document.getElementById("loginModal");
        loginModal.style.display = "none";
    }

    function loginUser() {
        // Obtener los valores del formulario
        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;

        // Aquí se debería hacer la autenticación real (esto es un simulacro)
        // Supongamos que tenemos dos usuarios: admin y usuario
        var rol = "";
        if (username === "admin" && password === "admin123") {
            rol = "admin";
        } else if (username === "usuario" && password === "user123") {
            rol = "usuario";
        } else {
            alert("Credenciales incorrectas");
            return false;
        }

        // Guardar el rol del usuario
        sessionStorage.setItem("rolUsuario", rol);

        // Cambiar el botón de "Iniciar Sesión" a "Cerrar Sesión"
        var loginBtn = document.getElementById("loginBtn");
        loginBtn.innerHTML = '<span class="icon">&#128100;</span> Cerrar Sesión';
        loginBtn.onclick = function() {
            logoutUser();
        };

        // Cerrar el modal de inicio de sesión
        closeLoginModal();

        // Cargar las opciones del menú según el rol
        cargarOpcionesMenu(rol);

        return false; // Evitar que el formulario realice un submit real
    }

    function logoutUser() {
        // Limpiar la sesión
        sessionStorage.removeItem("rolUsuario");

        // Cambiar el botón de vuelta a "Iniciar Sesión"
        var loginBtn = document.getElementById("loginBtn");
        loginBtn.innerHTML = '<span class="icon">&#128100;</span> Iniciar Sesión';
        loginBtn.onclick = function() {
            toggleLogin();
        };

        // Limpiar las opciones del menú
        var menuOptions = document.getElementById("menuOptions");
        menuOptions.innerHTML = '';

        alert("Sesión cerrada");
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
        }
    }
</script>

</body>
</html>
