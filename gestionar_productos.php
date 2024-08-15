<?php
session_start();

// Configuración de la base de datos
$servername = "127.0.0.1";
$username = "root";
$password = "root";
$dbname = "b4_36189857_galileo";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el usuario está logueado y obtener el rol si es necesario
$usuario_rol = '';
if (isset($_SESSION['usuario_id'])) {
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
        echo "<script>alert('Usuario no encontrado.'); window.location.href='login.php';</script>";
        exit();
    }

    $stmt->close();
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

        $query = "INSERT INTO productos (descripcion, precio) VALUES ('$product_name', '$product_price')";
        if ($conn->query($query) === TRUE) {
            $message = "Producto creado exitosamente.";
        } else {
            $message = "Error al crear producto: " . $conn->error;
        }
    } elseif (isset($_POST['update'])) {
        // Actualizar producto existente
        $product_id = $_POST['id'];
        $product_name = $_POST['nombre'];
        $product_price = $_POST['precio'];

        $query = "UPDATE productos SET descripcion='$product_name', precio='$product_price' WHERE producto_id=$product_id";
        if ($conn->query($query) === TRUE) {
            $message = "Producto actualizado exitosamente.";
        } else {
            $message = "Error al actualizar producto: " . $conn->error;
        }
    }
}

// Manejo de la acción de eliminar
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $query = "DELETE FROM productos WHERE producto_id = $product_id";
    if ($conn->query($query) === TRUE) {
        $message = "Producto eliminado exitosamente.";
    } else {
        $message = "Error al eliminar producto: " . $conn->error;
    }
}

// Manejo de la acción de editar
if (isset($_GET['edit'])) {
    $product_id = $_GET['edit'];
    $query = "SELECT * FROM productos WHERE producto_id = $product_id";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $product_name = $product['descripcion'];
        $product_price = $product['precio'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
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
        .container {
            flex: 1; /* Esto hace que el contenedor crezca y ocupe el espacio restante */
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        form {
            margin-bottom: 20px;
            background-color: #fff;
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
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }
        a {
            text-decoration: none;
            color: #007bff;
            margin-right: 10px;
        }
        a:hover {
            color: #0056b3;
        }
        footer {
            background-color: #111;
            color: #ddd;
            padding: 20px;
            font-family: Arial, sans-serif;
            width: 100%;
            bottom: 0;
            position: relative;
            box-sizing: border-box; /* Asegura que el padding y el borde no aumenten el ancho total */
        }

        .footer-top {
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
        }

        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 100%; /* Asegura que el contenido no exceda el ancho del contenedor */
            box-sizing: border-box;
        }

        .footer-section {
            flex: 1;
            min-width: 150px;
            margin-right: 15px;
            box-sizing: border-box;
        }

        .footer-section h5 {
            border-bottom: 1px solid #888;
            font-size: 1.2em;
            padding-bottom: 5px;
            margin-bottom: 10px;
            color: #fff;
        }

        .footer-section p {
            margin: 5px 0;
            font-size: 14px;
        }

        .footer-section a {
            color: #ddd;
            text-decoration: none;
            font-size: 14px;
        }

        .footer-section a:hover {
            color: #fff;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 10px;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
            }

            .footer-section {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="header-left">
        <button onclick="toggleMenu()">
            <span class="icon-slider">&#9776;</span> Menu
        </button>
        <div id="menuDropdown">
            <ul>
                <li><a href="panel_control.php">Panel de Control</a></li>
                <li><a href="gestionar_productos.php">Gestionar Productos</a></li>
                <li><a href="gestionar_usuarios.php">Gestionar Usuarios</a></li>
            </ul>
        </div>
    </div>
    <div class="header-right">
        <button onclick="location.href='perfil.php'">
            <span class="icon">&#128100;</span>Perfil
        </button>
        <button onclick="logout()">
            <span class="icon">&#128682;</span>Logout
        </button>
    </div>
</header>

<div class="container">
    <h1>Gestión de Productos</h1>
    <?php if ($message): ?>
        <p class="message"><?= $message; ?></p>
    <?php endif; ?>
    <form method="POST" action="gestionar_productos.php">
        <input type="hidden" name="id" value="<?= $product_id; ?>">
        <input type="text" name="nombre" placeholder="Nombre del producto" value="<?= $product_name; ?>" required>
        <input type="number" name="precio" placeholder="Precio del producto" step="0.01" value="<?= $product_price; ?>" required>
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
                <th>Nombre</th>
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
                    <td><?= $row['producto_id']; ?></td>
                    <td><?= $row['descripcion']; ?></td>
                    <td><?= $row['precio']; ?></td>
                    <td>
                        <a href="gestionar_productos.php?edit=<?= $row['producto_id']; ?>">Editar</a>
                        <a href="gestionar_productos.php?delete=<?= $row['producto_id']; ?>" onclick="return confirm('¿Está seguro de que desea eliminar este producto?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<footer>
    <div class="footer-top">
        <div class="footer-content">
            <div class="footer-section">
                <h5>Contact Us</h5>
                <p>Email: support@example.com</p>
                <p>Phone: +1 234 567 890</p>
            </div>
            <div class="footer-section">
                <h5>Follow Us</h5>
                <a href="#">Facebook</a><br>
                <a href="#">Twitter</a><br>
                <a href="#">Instagram</a>
            </div>
            <div class="footer-section">
                <h5>Legal</h5>
                <a href="#">Privacy Policy</a><br>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2024 Your Company. All rights reserved.
    </div>
</footer>

<script>
    function logout() {
        window.location.href = 'gestionar_productos.php?logout=true';
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
