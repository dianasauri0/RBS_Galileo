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

// Variables para los mensajes y los datos del producto
$message = "";
$product_id = 0;
$product_name = "";
$product_price = "";

// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        // Crear nuevo producto
        $product_name = $_POST['nombre'];
        $product_price = $_POST['precio'];

        $query = "INSERT INTO productos (descripcion, precio) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sd", $product_name, $product_price);
        if ($stmt->execute()) {
            $message = "Producto creado exitosamente.";
        } else {
            $message = "Error al crear producto: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['update'])) {
        // Actualizar producto existente
        $product_id = $_POST['id'];
        $product_name = $_POST['nombre'];
        $product_price = $_POST['precio'];

        $query = "UPDATE productos SET descripcion=?, precio=? WHERE producto_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdi", $product_name, $product_price, $product_id);
        if ($stmt->execute()) {
            $message = "Producto actualizado exitosamente.";
        } else {
            $message = "Error al actualizar producto: " . $stmt->error;
        }
        $stmt->close();

        // Redirigir a la misma página para evitar reenvío del formulario al refrescar
        header("Location: gestionar_productos.php");
        exit();
    }
}

// Manejo de la acción de eliminar
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $query = "DELETE FROM productos WHERE producto_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        $message = "Producto eliminado exitosamente.";
    } else {
        $message = "Error al eliminar producto: " . $stmt->error;
    }
    $stmt->close();
    // Redirigir para evitar reenvío del formulario al refrescar
    header("Location: gestionar_productos.php");
    exit();
}

// Manejo de la acción de editar
if (isset($_GET['edit'])) {
    $product_id = $_GET['edit'];
    $query = "SELECT * FROM productos WHERE producto_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $product_name = $product['descripcion'];
        $product_price = $product['precio'];
    } else {
        $message = "Producto no encontrado.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FDEX - Gestión de Productos</title>
    <link rel="icon" href="F.png" type="image/x-icon"> <!-- Favicon -->
   <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
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

        .container {
            flex: 1; /* Esto hace que el contenedor crezca y ocupe el espacio restante */
            padding: 20px;
            background-color: #fff; /* Fondo blanco */
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Centrar el título */
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th {
            background-color: #212529; /* Fondo oscuro */
            color: #dee2e6; /* Texto claro */
            padding: 10px;
        }

        td {
            padding: 10px;
            border-top: 1px solid #ddd;
            text-align: center; /* Centra el contenido de la celda */
        }

        form {
            margin-bottom: 20px;
            background-color: #f8f9fa; /* Fondo gris claro */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        input[type="text"], input[type="number"] {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button[type="submit"] {
            background-color: #007bff; /* Azul primario */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #0056b3; /* Azul más oscuro */
        }

        .message {
            color: #28a745; /* Verde para mensajes de éxito */
            font-weight: bold;
            margin-bottom: 20px;
        }

        a {
            text-decoration: none;
            color: #007bff; /* Azul primario */
            margin-right: 10px;
        }

        a:hover {
            color: #0056b3; /* Azul más oscuro */
        }

        button.action-button {
    background-color: #e85d04; /* Naranja vibrante */
    color: #fff; /* Texto blanco */
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    margin: 0 5px;
    transition: background-color 0.3s ease;
    text-align: center;
}

button.action-button:hover {
    background-color: #c84e01; /* Naranja más oscuro para el hover */
}

/* Estilos para los botones de acción en el formulario */
form button[type="submit"] {
    background-color: #e85d04; /* Naranja vibrante */
    color: #fff; /* Texto blanco */
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

form button[type="submit"]:hover {
    background-color: #c84e01; /* Naranja más oscuro */
}

/* Asegura que los enlaces en las celdas estén centrados */
td a {
    display: inline-block;
    margin: 0 5px;
    text-decoration: none;
    color: #fff;
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
    <h1>Gestión de Productos</h1>
    <?php if ($message): ?>
        <p class="message"><?= $message; ?></p>
    <?php endif; ?>
    <form method="POST" action="gestionar_productos.php">
        <input type="hidden" name="id" value="<?= htmlspecialchars($product_id); ?>">
        <input type="text" name="nombre" placeholder="Nombre del producto" value="<?= htmlspecialchars($product_name); ?>" required>
        <input type="number" name="precio" placeholder="Precio del producto" step="0.01" value="<?= htmlspecialchars($product_price); ?>" required>
        <?php if ($product_id): ?>
            <button type="submit" name="update">Actualizar Producto</button>
        <?php else: ?>
            <button type="submit" name="create">Crear Producto</button>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Producto</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM productos");
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['producto_id']); ?></td>
                    <td><?= htmlspecialchars($row['descripcion']); ?></td>
                    <td><?= htmlspecialchars($row['precio']); ?></td>
                    <td>
                        <button class="action-button" onclick="window.location.href='gestionar_productos.php?edit=<?= $row['producto_id']; ?>'">Editar</button>
                        <button class="action-button" onclick="if(confirm('¿Está seguro de que desea eliminar este producto?')) window.location.href='gestionar_productos.php?delete=<?= $row['producto_id']; ?>'">Eliminar</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    function handleLoginLogout() {
        <?php if ($usuario_logueado): ?>
            window.location.href = 'index.php?logout=true';
        <?php else: ?>
            window.location.href = 'login.html';
        <?php endif; ?>
    }

    function toggleMenu() {
        var menu = document.getElementById('menuDropdown');
        if (menu.style.display === 'block') {
            menu.style.display = 'none';
        } else {
            menu.style.display = 'block';
        }
    }
</script>
</body>
</html>
