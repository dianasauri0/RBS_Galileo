<?php
// Iniciar la sesión
session_start();

// Datos de conexión a la base de datos
$servername = "sql110.infinityfree.com";
$username = "if0_37108824";
$password = "BjpIYhEjhN";
$dbname = "if0_37108824_fdex";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el usuario está logueado y obtener el rol si es necesario
$usuario_logueado = isset($_SESSION['usuario_id']);
$usuario_rol = '';
if ($usuario_logueado) {
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
        $usuario_logueado = false; // Usuario no encontrado, considerarlo como no logueado
    }

    $stmt->close();
}

// Verificar si el usuario tiene permisos para acceder a esta página
if (!$usuario_logueado || $usuario_rol !== 'administrador') {
    header('Location: index.php');
    exit();
}

// Manejar el logout
if (isset($_GET['logout'])) {
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    echo "<script>window.location.href='index.php';</script>"; // Refresca la página
    exit();
}

// Manejar la actualización del estado o eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pendiente_id = $_POST['pendiente_id'];
    $action = $_POST['action'];

    if ($action === 'entregar') {
        $stmt = $conn->prepare("UPDATE pendientes SET estado = 'entregado' WHERE pendiente_id = ?");
        $stmt->bind_param("i", $pendiente_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'anular') {
        $stmt = $conn->prepare("DELETE FROM pendientes WHERE pendiente_id = ?");
        $stmt->bind_param("i", $pendiente_id);
        $stmt->execute();
        $stmt->close();
    }

    // Redirigir después de procesar la acción
    header("Location: clientes.php");
    exit();
}

// Consultar los datos para mostrar en la tabla
$sql = "SELECT * FROM pendientes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FDEX - Clientes</title>
    <link rel="icon" href="F.png" type="image/x-icon">
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

        /* Estilos para tablas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #212529; /* Fondo oscuro */
            color: #dee2e6; /* Texto claro */
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

         /* Estilos para los botones de acción */
        .btn-action {
            background-color: #007bff; /* Azul brillante */
            border: none;
            color: #fff;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-action:hover {
            background-color: #0056b3; /* Azul más oscuro para el hover */
        }

        .btn-entregar {
            background-color: #28a745; /* Verde */
        }

        .btn-entregar:hover {
            background-color: #218838; /* Verde más oscuro para el hover */
        }

        .btn-anular {
            background-color: #dc3545; /* Rojo */
        }

        .btn-anular:hover {
            background-color: #c82333; /* Rojo más oscuro para el hover */
        }

        /* Estilo para centrar el encabezado */
        .centered-heading {
            text-align: center;
            margin: 20px 0;
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

<!-- Contenido de Clientes.php -->
<h1 class="centered-heading">Administración de Clientes</h1>
<!-- Ejemplo de tabla con estilos aplicados -->
<table border="1">
    <tr>
        <th>ID</th>
        <th>Estado</th>
        <th>Producto ID</th>
        <th>Order ID</th>
        <th>Usuario ID</th>
        <th>Acción</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['pendiente_id']; ?></td>
        <td><?php echo $row['estado']; ?></td>
        <td><?php echo $row['producto_id']; ?></td>
        <td><?php echo $row['order_id']; ?></td>
        <td><?php echo $row['usuario_id']; ?></td>
        <td>
            <?php if ($row['estado'] === 'pendiente'): ?>
                <form method="POST" style="display:inline-block;">
                    <input type="hidden" name="pendiente_id" value="<?php echo $row['pendiente_id']; ?>">
                    <input type="hidden" name="action" value="entregar">
                    <input type="submit" value="Entregar" class="btn-action btn-entregar">
                </form>
            <?php endif; ?>

            <?php if ($row['estado'] === 'pendiente' || $row['estado'] === 'entregado'): ?>
                <form method="POST" style="display:inline-block;">
                    <input type="hidden" name="pendiente_id" value="<?php echo $row['pendiente_id']; ?>">
                    <input type="hidden" name="action" value="anular">
                    <input type="submit" value="Anular" class="btn-action btn-anular">
                </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<script>
    function toggleMenu() {
        var menuDropdown = document.getElementById('menuDropdown');
        menuDropdown.style.display = menuDropdown.style.display === 'block' ? 'none' : 'block';
    }

    function handleLoginLogout() {
        var loginBtn = document.getElementById('loginBtn');
        if (loginBtn.innerHTML.includes('Cerrar Sesión')) {
            window.location.href = 'clientes.php?logout=true';
        } else {
            window.location.href = 'login.html';
        }
    }

</script>

</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
