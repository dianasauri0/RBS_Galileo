<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header con Slider, Inicio de Sesión y Carrito</title>
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
        <button id="cartBtn" onclick="window.location.href='carrito.php'">
            <span class="icon">&#128722;</span> 
            Carrito
        </button>
    </div>
</header>

</body>
</html>
