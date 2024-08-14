<?php
// Iniciar sesión (session) para manejar mensajes de error
session_start();

// Si el formulario se envió (es decir, se hizo clic en "Registrar")
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validar los datos
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
    } elseif ($password !== $confirmPassword) {
        $_SESSION['error'] = "Las contraseñas no coinciden.";
    } else {
        // Aquí iría el código para guardar el usuario en una base de datos

        // Ejemplo: Conectar a la base de datos y guardar el usuario
        // (El siguiente código es solo un ejemplo y debe ajustarse según tu configuración de base de datos)
        /*
        $conn = new mysqli('localhost', 'usuario', 'contraseña', 'nombre_base_datos');
        
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (username, password) VALUES ('$username', '$hashedPassword')";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Registro exitoso. Ahora puede iniciar sesión.";
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['error'] = "Error al registrar: " . $conn->error;
        }
        
        $conn->close();
        */

        // Para propósitos de demostración, asumiremos que el registro fue exitoso
        $_SESSION['success'] = "Registro exitoso. Ahora puede iniciar sesión.";
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #555;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Registro de Usuario</h2>

    <!-- Mostrar mensaje de error o éxito -->
    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="error">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<div class="success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    ?>

    <!-- Formulario de registro -->
    <form action="registro.php" method="POST">
        <input type="text" name="username" placeholder="Nombre de Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" required>
        <input type="submit" value="Registrar">
    </form>
</div>

</body>
</html>
