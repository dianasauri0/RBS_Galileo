<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte Técnico - FDEX</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        header {
            background-color: #333;
            padding: 10px;
            display: flex;
            align-items: center;
            position: relative;
        }

        header .btn-back {
            background-color: #e07a00; /* Naranja más oscuro */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 20px;
        }

        header .btn-back:hover {
            background-color: #d06500; /* Naranja aún más oscuro al pasar el ratón */
        }

        header img {
            height: 50px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        header h1 {
            color: #e07a00; /* Naranja más oscuro */
            margin: 0;
            font-size: 24px;
            text-align: right;
            flex: 1;
        }

        h1 {
            color: #e07a00; /* Naranja más oscuro */
            text-align: center;
            margin: 20px 0;
        }

        h2 {
            color: #e07a00; /* Naranja más oscuro */
        }

        .content {
            padding: 20px;
        }

        .section {
            margin-bottom: 40px;
        }

        .section p {
            color: #333;
        }

        .contact-form {
            width: 60%;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(to bottom, #e0e0e0, #ffffff);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .contact-form h2 {
            margin-bottom: 20px;
        }

        .contact-form label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .contact-form button {
            background-color: #e07a00; /* Naranja más oscuro */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .contact-form button:hover {
            background-color: #d06500; /* Naranja aún más oscuro al pasar el ratón */
        }

        footer {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<header>
    <button class="btn-back" onclick="window.location.href='index.php'">Volver al Inicio</button>
    <img src="FDEX.png" alt="FDEX">
    <h1>Soporte Técnico</h1>
</header>

<main class="content">
    <div class="section">
        <h2>Recomendaciones Generales</h2>
        <p>Para asegurar el funcionamiento óptimo de tus equipos y evitar problemas recurrentes, es fundamental realizar un mantenimiento preventivo periódico. Esto incluye limpieza de componentes internos, verificación de conexiones, y la actualización de todos los programas y sistemas operativos a sus versiones más recientes. Mantener tus equipos en un ambiente libre de polvo y humedad también contribuirá a su longevidad.</p>
    </div>

    <div class="section">
        <h2>Problemas de Conectividad</h2>
        <p>Si enfrentas problemas de conectividad, el primer paso es asegurarte de que todos los cables estén correctamente conectados y no presenten daños visibles. También es útil reiniciar tanto el router como el dispositivo que estás utilizando. Si los problemas persisten, verifica si hay interrupciones del servicio con tu proveedor de internet o realiza un diagnóstico más detallado para identificar posibles fallos en el hardware.</p>
    </div>

    <div class="section">
        <h2>Actualización de Software</h2>
        <p>Mantener el software de tus dispositivos actualizado es crucial para protegerte contra vulnerabilidades de seguridad y mejorar el rendimiento del sistema. Asegúrate de habilitar las actualizaciones automáticas siempre que sea posible. Revisa periódicamente la página del fabricante para obtener las últimas actualizaciones y parches. La actualización regular también puede ayudar a resolver problemas de compatibilidad y errores conocidos.</p>
    </div>

    <div class="section">
        <h2>Solución de Problemas Comunes</h2>
        <p>En caso de que tu equipo muestre comportamientos inusuales, como lentitud o errores recurrentes, un primer paso recomendado es reiniciar el sistema para descartar problemas temporales. Si los problemas persisten, puedes intentar restaurar el sistema a un punto anterior donde funcionaba correctamente. Si el equipo sigue sin funcionar correctamente, considera buscar asistencia técnica profesional para un diagnóstico exhaustivo.</p>
    </div>

    <div class="contact-form">
        <h2>Contacto</h2>
        <form action="#" method="post">
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Mensaje:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit">Enviar</button>
        </form>
    </div>
</main>

<footer>
    <p>&copy; 2024 FDEX. Todos los derechos reservados.</p>
</footer>

</body>
</html>
