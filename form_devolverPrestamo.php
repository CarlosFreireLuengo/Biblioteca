<?php
$conexion = new mysqli("localhost", "root", "", "biblioteca");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

//Obtener lectores activos
$lectores = $conexion->query("SELECT id_lector, lector_nombre FROM lectores WHERE estado='activo'");

//Ver si se ha seleccionado un lector
$id_lector_seleccionado = $_POST['id_lector']?? null;
//Array para almacenar los libros prestados del lector
$libros_prestados = [];

if($id_lector_seleccionado){
    //Obtener los libros prestados del lector seleccionado
    $sql ="SELECT libros.id, libros.titulo
            FROM prestamos
            JOIN libros ON prestamos.id_libro = libros.id
            WHERE prestamos.id_lector = $id_lector_seleccionado";
    
    $resultado = $conexion->query($sql);

    while($libro = $resultado->fetch_assoc()){
        $libros_prestados[]= $libro;
    }
}

if (isset($_POST['devolver'])&& isset($_POST["id_libro"])) {
    $id_libro = ($_POST['id_libro']);
    $id_lector =($_POST['id_lector']);

    // Comprobar si el lector tiene préstamos activos
    $sql_prestamos = $conexion->query("SELECT * FROM prestamos WHERE id_lector = $id_lector AND id_libro= $id_libro");

    if ($sql_prestamos->num_rows > 0) {
        //Eliminar el prestamo
        $conexion->query("DELETE FROM prestamos WHERE id_lector = $id_lector AND id_libro = $id_libro");
        
        //Actualizar lector (disminuir número de préstamos)
        $conexion->query("UPDATE lectores SET n_prestados = n_prestados - 1 WHERE id_lector = $id_lector");

        // Actualizar libro (aumentar número de disponibles)
        $conexion->query("UPDATE libros SET n_disponibles = n_disponibles + 1 WHERE id = $id_libro");
        
        $mensaje = "Préstamo devuelto correctamente.";

    } else {
         $mensaje = "Error: No existe un préstamo activo para este libro y lector.";
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Devolver préstamo</title>
</head>

<body>

    <h2>Devolver un préstamo</h2>

    <!-- Seleccionar lector -->
        <form method="post" action="">
            <label for="id_lector">Selecciona lector:</label>
            <select name="id_lector" id="id_lector" required>
                <option value="">-- Selecciona --</option>
                <?php while ($fila = $lectores->fetch_assoc()) { ?>
                    <option value="<?php echo $fila['id_lector'] ?>" <?php echo ($id_lector_seleccionado == $fila['id_lector']) ? 'selected' : '' ?>>
                        <?php echo htmlspecialchars($fila['lector_nombre']) ?>
                    </option>
                <?php } ?>
            </select>
            <br><br>
            <button type="submit" name="cargar">Cargar préstamos</button>
        </form>

    <!--Cuando se selecciona lector y se pulsa en cargar préstamos, se despliega el formulario siguiente: -->
    <!-- Mostrar libros prestados del lector si hay lector seleccionado -->
    <?php if ($id_lector_seleccionado && count($libros_prestados) > 0): ?>
        <form method="post" action="">
            <input type="hidden" name="id_lector" value="<?php echo $id_lector_seleccionado ?>">
            <label for="id_libro">Selecciona libro a devolver:</label>
            <select name="id_libro" id="id_libro" required>
                <option value="">-- Selecciona --</option>
                <?php foreach ($libros_prestados as $libro): ?>
                    <option value="<?php echo $libro['id'] ?>"><?php echo htmlspecialchars($libro['titulo']) ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <button type="submit" name="devolver">Devolver préstamo</button>
        </form>
    <?php elseif ($id_lector_seleccionado): ?>
            <p>Este lector no tiene préstamos activos.</p>
    <?php endif; ?>

    <?php
    if (isset($mensaje)) {
        echo "<p>$mensaje</p>";
    }
    ?>

    <br>
    <a href="index.php">Volver al menú</a>
</body>

</html>