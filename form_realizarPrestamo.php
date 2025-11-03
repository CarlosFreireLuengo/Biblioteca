<?php
$conexion = new mysqli("localhost", "root", "", "biblioteca");
 if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (isset($_POST['prestamo'])) {

    $id_libro = $_POST['id_libro'];
    $id_lector = $_POST['id_lector'];

    // Comprobar lector activo
    $sql_lector = $conexion->query("SELECT estado, n_prestados FROM lectores WHERE id_lector = $id_lector");
    $lector = $sql_lector->fetch_assoc();

    if (!$lector) {
        $mensaje = "Error: El lector no existe.";
    } elseif ($lector['estado'] != 'activo') {
            $mensaje = "Error: El lector está dado de baja y no puede realizar préstamos.";
    } else {
        //Comprobación de que haya ejemplares de ese libro disponibles para prestar
         $sql_libro = $conexion->query("SELECT n_disponibles FROM libros WHERE id = $id_libro");
         $libro = $sql_libro->fetch_assoc();
         if(!$libro){
            $mensaje = "El libro no existe";
         }elseif($libro["n_disponibles"]<=0){
            $mensaje = "Error. No hay ejemplares disponibles para prestar";
         }else{
            // Registrar el préstamo (solo lo que indica el enunciado)
            $conexion->query("INSERT INTO prestamos (id_lector, id_libro)
                            VALUES ($id_lector, $id_libro)");

            // Incrementar número de libros prestados del lector
            $conexion->query("UPDATE lectores SET n_prestados = n_prestados + 1
                            WHERE id_lector = $id_lector");

            //Reducir número de ejemplares disponibles
            $conexion->query("UPDATE libros SET n_disponibles = n_disponibles -1
                            WHERE id = $id_libro");

            $mensaje = "Préstamo realizado correctamente.";

         } 
        // Registrar el préstamo (solo lo que indica el enunciado)
        $conexion->query("INSERT INTO prestamos (id_lector, id_libro)
                          VALUES ($id_lector, $id_libro)");

        // Incrementar número de libros prestados del lector
        $conexion->query("UPDATE lectores SET n_prestados = n_prestados + 1
                          WHERE id_lector = $id_lector");

        //Faltante: reducir en 1 el numero de ejemplares disponibles para prestar del libro prestado

        $mensaje = "Préstamo realizado correctamente.";
    }
}
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Realizar Préstamo</title>
</head>
<body>
    <h2>Realizar Préstamo</h2>

    <?php 
    if (isset($mensaje)) {
        echo "<p><strong>$mensaje</strong></p>";
    }
    ?>

    <form action="" method="POST">
        <label>ID del Libro:</label>
        <input type="number" name="id_libro" required><br><br>

        <label>ID del Lector:</label>
        <input type="number" name="id_lector" required><br><br>

        <button type="submit" name="prestamo">Prestar Libro</button>
    </form>
    <br>
    <a href="index.php">Volver al menú</a>
</body>
</html>
