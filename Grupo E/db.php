<?php
$host = 'localhost';
$dbname = 'escuela';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("No se pudo conectar a la base de datos $dbname :" . $e->getMessage());
}

$nombreError = $apellidoError = $dniError = $escuelaError = "";

// Agregar profesor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $escuela = $_POST['escuela'];

    $valid = true;

    // Validar que el nombre contenga solo letras
    if (!preg_match("/^[a-zA-Z ]*$/", $nombre)) {
        $valid = false;
    }

    // Validar que el apellido contenga solo letras
    if (!preg_match("/^[a-zA-Z ]*$/", $apellido)) {
        $valid = false;
    }

    // Validar que el DNI sea numérico y exactamente 8 dígitos
    if (!is_numeric($dni) || strlen($dni) != 8) {
        $valid = false;
    }

    if ($valid) {
        $sql = "INSERT INTO profesores (nombre, apellido, dni, escuela) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $apellido, $dni, $escuela]);
    
        header("Location: index.php");
        exit;
    }
    
}

// Modificar profesores seleccionados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modificar'])) {
    $idsToModify = explode(',', $_POST['idsToModify']);
    $nombre = $_POST['modifyNombre'];
    $apellido = $_POST['modifyApellido'];
    $dni = $_POST['modifyDni'];
    $escuela = $_POST['modifyEscuela'];

    $valid = true;

    // Validar que el nombre contenga solo letras
    if (empty($nombre) || !preg_match("/^[a-zA-Z ]*$/", $nombre)) {
        $valid = false;
    }

    // Validar que el apellido contenga solo letras
    if (empty($apellido) || !preg_match("/^[a-zA-Z ]*$/", $apellido)) {
        $valid = false;
    }

    // Validar que el DNI sea numérico y exactamente 8 dígitos
    if (empty($dni) || !is_numeric($dni) || strlen($dni) != 8) {
        $valid = false;
    }

    // Validar que la escuela no esté vacía
    if (empty($escuela)) {
        $valid = false;
    }

    if ($valid) {
        foreach ($idsToModify as $id) {
            $sql = "UPDATE profesores SET nombre = ?, apellido = ?, dni = ?, escuela = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $apellido, $dni, $escuela, $id]);
        }

        header("Location: index.php");
        exit;
    }
}

// Eliminar profesores seleccionados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar'])) {
    if (!empty($_POST['ids'])) {
        $ids = $_POST['ids'];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM profesores WHERE id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);

        header("Location: index.php");
        exit;
    } else {
        $mensajeError = "No se seleccionaron profesores para eliminar.";
    }
}

// Mostrar profesores
$sql = "SELECT * FROM profesores";
$stmt = $pdo->query($sql);
$profesores = $stmt->fetchAll();
?>

<!-- Realizado principalmente por Nehuen Perez Titz -->
<!-- Integrantes del grupo: Nehuen Perez Titz,Lisandro Tejeda, Monzon Kevin -->