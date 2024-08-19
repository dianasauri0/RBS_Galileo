<?php
session_start();

// Datos de conexión a la base de datos
$servername = "sql110.infinityfree.com";
$db_username = "if0_37108824";
$db_password = "BjpIYhEjhN";
$dbname = "if0_37108824_fdex";

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
    <title>FDEX - Carrito de Compras</title>
    <link rel="icon" href="F.png" type="image/x-icon"> <!-- Favicon -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <style>
        /* Estilos generales */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            box-sizing: border-box;
            background-color: #f8f9fa; /* Fondo de Página */
        }

        /* Estilos del encabezado */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #212529; /* Fondo oscuro */
            color: #dee2e6; /* Texto claro */
        }

        .header-left, .header-right {
            display: flex;
            align-items: center;
        }

        .header-left button,
        .header-right button {
            background-color: #e85d04; /* Naranja vibrante */
            border: none;
            color: #dee2e6; /* Texto claro */
            cursor: pointer;
            font-size: 16px;
            margin-right: 15px;
            display: flex;
            align-items: center;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .header-left button:hover,
        .header-right button:hover {
            background-color: #c84e01; /* Naranja más oscuro para el hover */
        }

        .header-center {
            flex: 1;
            text-align: center;
        }

        .logo {
            height: 50px; /* Ajusta la altura del logo según sea necesario */
            max-width: 100%;
        }

        .header-right button:last-child {
            margin-right: 0;
        }

        .header-right button .icon {
            margin-right: 5px;
        }

        #menuDropdown {
            position: absolute;
            top: 60px;
            left: 20px;
            background-color: #212529; /* Fondo oscuro */
            color: #dee2e6; /* Texto claro */
            border-radius: 4px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
            color: #dee2e6; /* Texto claro */
            transition: color 0.3s ease;
        }

        #menuDropdown ul li a:hover {
            color: #e85d04; /* Naranja vibrante */
        }

        /* Estilos generales del contenedor del carrito */
.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #ffffff; /* Fondo blanco para el contenedor del carrito */
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.container h1 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #212529; /* Gris oscuro */
    text-align: center;
}

#cartContent {
    margin-top: 20px;
}

.product {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #dee2e6; /* Línea separadora entre productos */
}

.product:last-child {
    border-bottom: none; /* Eliminar la línea en el último producto */
}

.product span {
    font-size: 16px;
    color: #212529; /* Gris oscuro para el texto */
}

.product button {
    background-color: #e85d04; /* Naranja vibrante */
    color: #ffffff; /* Blanco para el texto */
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.product button:hover {
    background-color: #c84e01; /* Naranja más oscuro para el hover */
}

.total {
    font-size: 20px;
    font-weight: bold;
    margin-top: 20px;
    text-align: right;
    color: #007A33; /* Verde esmeralda para el total */
}

.buy-now {
    display: block;
    width: 100%;
    text-align: center;
    padding: 10px;
    background-color: #007A33; /* Verde esmeralda */
    color: #ffffff; /* Blanco para el texto */
    text-decoration: none;
    border-radius: 5px;
    font-size: 18px;
    margin-top: 20px;
    transition: background-color 0.3s ease;
}

.buy-now:hover {
    background-color: #005f24; /* Verde más oscuro para el hover */
}

        /* Estilos del pie de página */
footer {
    background-color: #212529; /* Fondo oscuro */
    color: #adb5bd; /* Texto gris claro */
    padding: 40px 0;
    font-size: 14px;
    text-align: center;
    width: 100vw;
    margin-top: auto;
}

.footer-top {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 20px;
    border-bottom: 1px solid #343a40; /* Gris oscuro */
}

.footer-content {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    text-align: left;
}

.footer-section {
    flex: 1;
    margin: 20px;
    min-width: 200px;
}

.footer-section h5 {
    font-size: 16px;
    margin-bottom: 15px;
    color: #dee2e6; /* Texto claro */
}

.footer-section p,
.footer-section a {
    color: #adb5bd; /* Texto gris claro */
    line-height: 1.8;
    text-decoration: none;
}

.footer-section a:hover {
    color: #e85d04; /* Naranja vibrante */
}

.footer-bottom {
    padding: 10px 0;
    background-color: #212529; /* Fondo oscuro */
    font-size: 12px;
    border-top: 1px solid #343a40; /* Gris oscuro */
}

.footer-bottom p {
    margin: 5px 0;
}

.footer-bottom a {
    color: #e85d04; /* Naranja vibrante */
    text-decoration: none;
}

.footer-bottom a:hover {
    text-decoration: underline;
}
    </style>
</head>
<body>

<header>
        <div class="header-left">                                                      <!--Header parte izquierda-->
            <button id="sliderBtn" onclick="toggleMenu()">
                <span class="icon-slider">&#9776;</span> 
                Menú
            </button>
            <div id="menuDropdown">
            <ul id="menuOptions">
                <?php if ($usuario_logueado && $usuario_rol === 'administrador'): ?>
                    <li><a href="pedidos.php">Mis pedidos</a></li>
                    <li><a href="clientes.php">Gestionar Clientes</a></li>
                    <li><a href="gestionar_productos.php">Gestionar Productos</a></li>
                <?php elseif ($usuario_logueado && $usuario_rol === 'cliente'): ?>
                    <li><a href="pedidos.php">Mis pedidos</a></li>
                <?php else: ?>
                    <!-- Opciones disponibles para usuarios no logueados -->
                    <li><a href="login.html">Iniciar Sesión</a></li>
                    <li><a href="registro.html">Registrarse</a></li>
                <?php endif; ?>
            </ul>
            </div>
        </div>

        <div class="header-center">                                                     <!-- Logo en el centro -->
            <a href="index.php">
                <img src="FDEX.png" alt="Logo" class="logo">
            </a>
        </div>

        <div class="header-right">                                                     <!--Header parte derecha-->
            <button id="loginBtn" onclick="handleLoginLogout()">
                <span class="icon">&#128100;</span> 
                <?php echo $usuario_logueado ? 'Cerrar Sesión' : 'Iniciar Sesión'; ?>
            </button>
            <?php if (!$usuario_logueado): ?>
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
            <div class="total">Total: $<?php echo $total; ?></div>
            <a href="#" class="buy-now">Comprar Ahora</a>
        <?php else: ?>
            <p>Tu carrito está vacío.</p>
        <?php endif; ?>
    </div>
</div>

<footer>                                                                           <!--Footer de la página -->
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
                    <p><a href="privacidad.html">Política de Privacidad</a></p>
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
            <p><a href="#">Términos y Condiciones</a> | <a href="politica.html">Política de Privacidad</a></p>
        </div>
    <!-- Fin del Footer -->
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