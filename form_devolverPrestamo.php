<?php
$conexion = new mysqli("localhost", "root", "", "biblioteca");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (isset($_POST['devolver'])) {
    $id_libro = ($_POST['id_libro']);
    $id_lector =($_POST['id_lector']);

    // Comprobar si el lector tiene préstamos activos
    $sql_prestamos = $conexion->query("SELECT * FROM prestamos WHERE id_lector = $id_lector");
    if ($sql_prestamos->num_rows == 0) {
        $mensaje = "Error: El lector no tiene préstamos activos.";
    } else {
        // Comprobar si existe préstamo de ese libro para ese lector
        $sql_prestamosActivos = "SELECT * FROM prestamos WHERE id_lector = $id_lector AND id_libro = $id_libro";
        $resultado = $conexion->query($sql_prestamosActivos);

        if ($resultado->num_rows > 0) {
            // Eliminar el préstamo
            $conexion->query("DELETE FROM prestamos WHERE id_lector = $id_lector AND id_libro = $id_libro");

            // Actualizar lector (disminuir número de préstamos)
            $conexion->query("UPDATE lectores SET n_prestados = n_prestados - 1 WHERE id_lector = $id_lector");

            // Actualizar libro (aumentar número de disponibles)
            $conexion->query("UPDATE libros SET n_disponibles = n_disponibles + 1 WHERE id = $id_libro");

            $mensaje = "Préstamo devuelto correctamente.";
        } else {
            $mensaje = "Error: No existe un préstamo activo para este libro y lector.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Devolver préstamo</title>
</head>

<body>

    <h2>Devolver un préstamo</h2>

    <form method="POST" action="">
        <label for="id_lector">ID del lector:</label><br>
        <input type="number" name="id_lector" id="id_lector" required><br><br>

        <label for="id_libro">ID del libro:</label><br>
        <input type="number" name="id_libro" id="id_libro" required><br><br>

        <button type="submit" name="devolver">Devolver préstamo</button>
    </form>

    <?php
    if (isset($mensaje)) {
        echo "<p>$mensaje</p>";
    }
    ?>

    <br>
    <a href="index.php">Volver al menú</a>
</body>

</html>