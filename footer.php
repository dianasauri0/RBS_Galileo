<!DOCTYPE html>
<html lang="es">
<?php                               //En esta parte está la configuración y conexión a la base de datos, verificación de logueo y rol, y maneja el logout y sesion//

session_start();

// Configuración de la base de datos
$servername = "sql110.infinityfree.com";
$username = "if0_37108824";
$password = "BjpIYhEjhN";
$dbname = "if0_37108824_fdex";

// Crea la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    echo "Conexión fallida: " . $conn->connect_error;
    exit();
}

// Verifica si el usuario está logueado y obtener el rol si es necesario
$usuario_rol = 'cliente'; // Valor por defecto para usuarios no logueados
$usuario_logueado = false;

if (isset($_SESSION['usuario_id'])) {
    $usuario_logueado = true;
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
        // Si el usuario no se encuentra, se maneja con una alerta y se limpia la sesión
        session_unset();
        session_destroy();
        $usuario_logueado = false; // Actualizar el estado de logueo
    }

    $stmt->close();
}

//Maneja el resultado de compra
if (isset($_GET['status'])) {
    $status = $_GET['status'];

    if ($status == 'success') {
        echo "<script>alert('¡Compra exitosa!');</script>";
    } elseif ($status == 'failure') {
        echo "<script>alert('La compra ha fallado. Por favor, intenta nuevamente.');</script>";
    } elseif ($status == 'pending') {
        echo "<script>alert('Tu compra está pendiente. Te notificaremos cuando se complete.');</script>";
    }
}

//Maneja el logout
    if (isset($_GET['logout'])) {
        session_unset(); // Elimina todas las variables de la sesión
        session_destroy(); // Destruye la sesión
        echo "<script>window.location.href='index.php';</script>"; // Refresca la página
        exit();
    }
?>

<head>  <!--Head de la página-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FDEX - Tienda Deportiva</title>
    <link rel="icon" href="F.png" type="image/x-icon"> <!-- Favicon -->
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

/* Estilos del contenedor principal */
.container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    padding: 20px;
    max-width: 80vw;
    margin: 0 auto;
    box-sizing: border-box;
}

.courses {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 20px;
}

.course {
    background-color: #ffffff; /* Blanco para el fondo de las tarjetas */
    margin: 10px;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Sombra más pronunciada */
    width: 250px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.course:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3); /* Sombra más pronunciada en hover */
}

.course-title {
    font-size: 18px;
    margin-bottom: 10px;
    color: #212529; /* Gris oscuro */
}

.course-price {
    font-size: 20px;
    margin-bottom: 15px;
    color: #007A33; /* Verde Esmeralda */
}

.course button {
    background-color: #e85d04; /* Naranja vibrante */
    color: #ffffff; /* Blanco para el texto */
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.course button:hover {
    background-color: #c84e01; /* Naranja más oscuro */
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

.social-icons {
    display: flex;
    justify-content: flex-start;
}

.social-icon {
    margin-right: 15px;
}

.social-icon a {
    color: #adb5bd; /* Gris claro */
    text-decoration: none;
    font-size: 20px;
}

.social-icon a:hover {
    color: #e85d04; /* Naranja vibrante */
}

/* Estilos del contenedor de mensajes emergentes */
    #message-container {
        position: fixed;
        bottom: 20px;
        left: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px; /* Espacio entre mensajes */
    }

    /* Estilos del mensaje emergente */
    .message {
        background-color: #007A33; /* Verde Esmeralda */
        color: #F5F5F5; /* Blanco Nieve */
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.5s ease, transform 0.5s ease;
        transform: translateY(20px);
    }

    .message.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .message.success {
        background-color: #007A33; /* Verde para mensajes de éxito */
    }

    .message.error {
        background-color: #dc3545; /* Rojo para mensajes de error */
        color: #ffffff; /* Blanco para el texto */
    }
    </style>
</head>
<body>  <!--Body de la página-->

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

    <div class="container">                                                            <!-- Lista de Productos -->
        <?php
        // Obtener los productos desde la base de datos
        $sql = "SELECT producto_id, descripcion, precio FROM productos";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='course'>";
                echo "<h3 class='course-title'>" . htmlspecialchars($row['descripcion']) . "</h3>";
                echo "<p class='course-price'>$" . htmlspecialchars($row['precio']) . "</p>";

                // Mostrar el botón según el estado de la sesión
                if ($usuario_logueado) {
                    echo "<form method='POST' action='agregar_al_carrito.php' style='display:inline;'>";
                    echo "<input type='hidden' name='producto_id' value='" . htmlspecialchars($row['producto_id']) . "'>";
                    echo "<button type='submit'>Añadir al Carrito</button>";
                    echo "</form>";
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

    <footer> <!-- Footer de la página -->
    <div class="footer-top">
        <div class="footer-content">
            <div class="footer-section about">
                <h5>Sobre Nosotros</h5>
                <p>En FDEX, te equipamos con ropa deportiva que rinde al máximo. Calidad y estilo para que siempre estés listo para ganar.</p>
            </div>
            <div class="footer-section help">
                <h5>Ayuda</h5>
                <p><a href="preguntas.php">Preguntas Frecuentes</a></p>
                <p><a href="stecnico.php">Soporte Técnico</a></p>
                <p><a href="politicas.php">Política de Privacidad</a></p>
            </div>
            <div class="footer-section contact">
                <h5>Contacto</h5>
                <p>Teléfono: +11 2163-9809</p>
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
        <p><a href="terminos.php">Términos y Condiciones</a></p>
    </div>
    <!-- Fin del Footer -->
</footer>


    <div id="message-container"></div>

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

    let lastAlertTime = 0; // Para almacenar el tiempo del último clic

    function showMessage(message, isSuccess) {
        const messageContainer = document.getElementById('message-container');
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message ' + (isSuccess ? 'success' : 'error');
        messageDiv.textContent = message;
        messageContainer.appendChild(messageDiv);

        // Mostrar el mensaje con animación
        requestAnimationFrame(() => {
            messageDiv.classList.add('show');
        });

        // Ocultar el mensaje después de 3 segundos
        setTimeout(() => {
            messageDiv.classList.remove('show');
            // Remover el mensaje después de la animación
            setTimeout(() => {
                messageContainer.removeChild(messageDiv);
            }, 500); // Tiempo igual al de la transición de ocultamiento
        }, 3000); // Mostrar mensaje durante 3 segundos
    }

    function addToCart(productId) {
        const now = Date.now();
        if (now - lastAlertTime < 1000) {
            // Si el tiempo entre clics es menor a 1 segundo, no hacer nada
            return;
        }
        lastAlertTime = now;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'agregar_al_carrito.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                showMessage(response.message, response.status === 'success');
            }
        };
        xhr.send('producto_id=' + encodeURIComponent(productId));
    }

    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Evitar el envío del formulario
            var productoId = this.querySelector('input[name="producto_id"]').value;
            addToCart(productoId);
        });
    });

    function showLoginAlert() {
        showMessage('Debes iniciar sesión para añadir productos al carrito.', false); // false indica que es un mensaje de error
    }
</script>
</body>
</html>