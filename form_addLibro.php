<?php
require_once('conecta.php');

// Si se envía el formulario
if (isset($_POST['addLibro'])) {
    // Escapa los valores para evitar inyección SQL
    $titulo = $conexion->real_escape_string($_POST["nombre"]);
    $autor = $conexion->real_escape_string($_POST["autor"]);
    $publicacion = $conexion->real_escape_string($_POST["publi"]);
    $isbn = $conexion->real_escape_string($_POST["isbn"]);
    $sinopsis = $conexion->real_escape_string($_POST["sinopsis"]);
    $nejemplares = $conexion->real_escape_string($_POST["ntotales"]);
    $ndisponibles = $nejemplares;

    // Comprueba si el libro ya existe por ISBN
    $compLibro = "SELECT titulo FROM libros WHERE ISBN=$isbn";
    $resultado = $conexion->query($compLibro);
    $fila = $resultado->fetch_assoc();

    if ($resultado->num_rows > 0) {
        $mensaje = "El libro " . $fila['titulo'] . " ya está registrado en la biblioteca";
    } else {
        // Inserta el nuevo libro
        $sql_addLibro = "INSERT INTO libros (titulo, autor, anio_publicacion, ISBN, sinopsis, n_disponibles, n_totales)
                         VALUES ('$titulo', '$autor', '$publicacion', '$isbn', '$sinopsis', '$ndisponibles', '$nejemplares')";
        if ($conexion->query($sql_addLibro)) {
            $mensaje = "Libro añadido con éxito";
        } else {
            $mensaje = "Error al añadir el libro: " . $conexion->error;
        }
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir libro</title>
</head>
<body>
    <!-- Formulario para añadir libros -->
    <form action="" method="post">
        <h2>Añadir libro</h2>
        <?php 
        if (isset($mensaje)) {
            echo "<p><strong>$mensaje</strong></p>";
        }
        ?>
        
        <label for="nombre">Título:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="autor">Autor:</label>
        <input type="text" id="autor" name="autor" required><br><br>

        <label for="publi">Año de publicación:</label>
        <input type="number" id="publi" name="publi" required><br><br>

        <label for="isbn">ISBN:</label>
        <input type="number" id="isbn" name="isbn" required><br><br>

        <label for="sinopsis">Sinopsis:</label>
        <textarea id="sinopsis" name="sinopsis" rows="4" required></textarea><br><br>

        <label for="ntotales">Número de ejemplares:</label>
        <input type="number" id="ntotales" name="ntotales" required min="1"><br><br>

        <button type="submit" name="addLibro">Añadir libro</button>
    </form>
    <br>
    <a href="index.php">Volver al menú</a>
</body>
</html>
