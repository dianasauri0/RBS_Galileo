<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #333;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<div class="login-container">
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

<script>
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

        // Redirigir a la página principal después del inicio de sesión
        window.location.href = 'index.html';
        return false;
    }
</script>

</body>
</html>
