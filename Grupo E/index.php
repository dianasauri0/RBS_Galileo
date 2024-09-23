<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escuelas</title>
    <link rel="stylesheet" type="text/css" href="./css/styles.css">
    <link rel="shortcut icon" href="./assets/images/logo.png" type="image/x-icon">
</head>
<body>
    <div class="floating-header">
        <button class="BTNagregarProfesor" id="openModal">Agregar Profesor</button>
        <button class="BTNeliminarProfesor" type="submit" id="deleteButton" name="eliminar">Eliminar Seleccionados</button>
        <button class="BTNmodificarProfesor" type="button" id="modifyButton">Modificar</button>
    </div>
    <h1>Lista de Profesores</h1>
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="addForm" method="post">
                <div class="input-row first-input-row">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="input-row">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required>
                </div>
                <div class="input-row">
                    <label for="dni">DNI:</label>
                    <input type="text" id="dni" name="dni" required>
                </div>
                <div class="input-row">
                    <label for="escuela">Escuela:</label>
                    <input type="text" id="escuela" name="escuela" required>
                </div>
                <button class="BTNagregarProfesor" type="submit" name="agregar" id="agregarProfesorBtn">Agregar</button>
                <div id="errorMessages" class="error-messages">
                    <?php
                    if (!empty($nombreError)) echo "<p>$nombreError</p>";
                    if (!empty($apellidoError)) echo "<p>$apellidoError</p>";
                    if (!empty($dniError)) echo "<p>$dniError</p>";
                    if (!empty($escuelaError)) echo "<p>$escuelaError</p>";
                    ?>
                </div>
            </form>
        </div>
    </div>

    <div id="modifyModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModifyModal()">&times;</span>
            <form id="modifyForm" method="post">
                <input type="hidden" name="idsToModify" id="idsToModify">
                <div class="input-row first-input-row">
                    <label for="modifyNombre">Nombre:</label>
                    <input type="text" id="modifyNombre" name="modifyNombre">
                </div>
                <div class="input-row">
                    <label for="modifyApellido">Apellido:</label>
                    <input type="text" id="modifyApellido" name="modifyApellido">
                </div>
                <div class="input-row">
                    <label for="modifyDni">DNI:</label>
                    <input type="text" id="modifyDni" name="modifyDni">
                </div>
                <div class="input-row">
                    <label for="modifyEscuela">Escuela:</label>
                    <input type="text" id="modifyEscuela" name="modifyEscuela">
                </div>
                <button class="BTNmodificarProfesor" type="submit" name="modificar" id="guardarCambiosBtn">Guardar</button>
                <div id="errorMessages" class="error-messages">
                    <?php
                    if (!empty($nombreError)) echo "<p>$nombreError</p>";
                    if (!empty($apellidoError)) echo "<p>$apellidoError</p>";
                    if (!empty($dniError)) echo "<p>$dniError</p>";
                    if (!empty($escuelaError)) echo "<p>$escuelaError</p>";
                    ?>
                </div>
            </form>
        </div>
    </div>

    <br>
    <form method="post">
        <table>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Escuela</th>
            </tr>
            <?php foreach ($profesores as $profesor) : ?>
                <tr data-id="<?= $profesor['id'] ?>"
                    data-nombre="<?= htmlspecialchars($profesor['nombre']) ?>"
                    data-apellido="<?= htmlspecialchars($profesor['apellido']) ?>"
                    data-dni="<?= htmlspecialchars($profesor['dni']) ?>"
                    data-escuela="<?= htmlspecialchars($profesor['escuela']) ?>">
                    <td class="checkbox"><input type="checkbox" name="ids[]" value="<?= $profesor['id'] ?>"></td>
                    <td><?= htmlspecialchars($profesor['id']) ?></td>
                    <td><?= htmlspecialchars($profesor['nombre']) ?></td>
                    <td><?= htmlspecialchars($profesor['apellido']) ?></td>
                    <td><?= htmlspecialchars($profesor['dni']) ?></td>
                    <td><?= htmlspecialchars($profesor['escuela']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button type="submit" id="deleteButton2" name="eliminar"></button>
    </form>
    <div id="messageBox" class="message-box">
        <div class="message-content"></div>
        <div class="progress-bar"></div>
    </div>
    <script src="./js/modal.js"></script>
    <script>
        document.getElementById('deleteButton').addEventListener('click', function() {
            document.getElementById('deleteButton2').click();
        });
    </script>
    <script src="./js/reglas.js"></script>
</body>
</html>

<!-- Realizado principalmente por Nehuen Perez Titz -->
<!-- Integrantes del grupo: Nehuen Perez Titz,Lisandro Tejeda, Monzon Kevin -->