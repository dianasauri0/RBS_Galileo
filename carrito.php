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
    </style>
</head>
<body>

<header>
    <div class="header-left">
        <button id="sliderBtn">
            <span class="icon-slider">&#9776;</span> 
            Menú
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

            // Mostrar el contenido del carrito
            document.getElementById('cartContent').style.display = 'block';
            document.getElementById('loginPrompt').style.display = 'none';

            // Aquí cargarías los productos del carrito desde el backend o sessionStorage
            // Ejemplo:
            document.getElementById('cartContent').innerHTML = `
                <div class="product">
                    <span>Producto 1</span>
                    <span>$10.00</span>
                </div>
                <div class="product">
                    <span>Producto 2</span>
                    <span>$20.00</span>
                </div>`;
        } else {
            // Si no hay sesión iniciada, ocultar el botón de "Carrito"
            document.getElementById('cartBtn').style.display = 'none';
            document.getElementById('cartContent').style.display = 'none';
            document.getElementById('loginPrompt').style.display = 'block';
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

        // Mostrar el botón de registro y ocultar el botón de "Carrito"
        var registerBtn = document.getElementById("registerBtn");
        registerBtn.style.display = 'inline-block';

        document.getElementById('cartBtn').style.display = 'none';
        document.getElementById('cartContent').style.display = 'none';
        document.getElementById('loginPrompt').style.display = 'block';

        alert("Sesión cerrada");
    }
</script>

</body>
</html>
