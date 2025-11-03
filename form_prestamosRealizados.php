<?php
// ------------------ CONFIGURACIÓN DE CONEXIÓN ------------------
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca";

$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$resultado = null;
$mensaje = "";

if (isset($_POST['consultar'])) {
    $dni = trim($_POST["dni"]);

    if (!empty($dni)) {
        // Buscar lector por DNI
        $sql_lector = "SELECT id_lector, lector_nombre FROM lectores WHERE DNI = ?";
        $stmt = $conn->prepare($sql_lector);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $result_lector = $stmt->get_result();

        if ($result_lector->num_rows > 0) {
            $lector = $result_lector->fetch_assoc();
            $id_lector = $lector["id_lector"];
            $nombre_lector = $lector["lector_nombre"];

            // Buscar préstamos activos del lector
            $sql_prestamos = "
                SELECT libros.titulo, libros.autor, libros.anio_publicacion
                FROM prestamos
                INNER JOIN libros ON prestamos.id_libro = libros.id
                WHERE prestamos.id_lector = ?;
            ";
            $stmt2 = $conn->prepare($sql_prestamos);
            $stmt2->bind_param("i", $id_lector);
            $stmt2->execute();
            $resultado = $stmt2->get_result();

            if ($resultado->num_rows == 0) {
                $mensaje = "¡$nombre_lector, anímate a leer algo nuevo hoy!";
            }
        } else {
            $mensaje = "No se encontró ningún lector con el DNI ingresado.";
        }
    } else {
        $mensaje = "Por favor, introduce un DNI válido.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Préstamos</title>
</head>
<body>
    <h2>Consultar Préstamos de un Lector</h2>
    <form method="POST" action="">
        <label for="dni">Introduce el DNI del lector:</label><br>
        <input type="text" name="dni" id="dni" placeholder="Ej: 12345678A" required>
        <input type="submit" name="consultar" value="Consultar">
    </form>
    <hr>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <h2>Libros actualmente prestados:</h2>
        <table>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Año de Publicación</th>
            </tr>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row["titulo"]) ?></td>
                    <td><?= htmlspecialchars($row["autor"]) ?></td>
                    <td><?= htmlspecialchars($row["anio_publicacion"]) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php elseif (!empty($mensaje)): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <br>
    <a href="index.php">Volver al menú</a>
</body>
</html>
