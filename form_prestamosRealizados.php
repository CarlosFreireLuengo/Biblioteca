<?php
require_once('conecta.php');

$resultado = null;
$mensaje = "";
$id_lector = $_POST['id_lector'] ?? '';
//Obtenemos lista de lectores activos para el select
$sql_lectores = "SELECT id_lector, lector_nombre FROM lectores WHERE estado = 'activo'";
$lectores = $conexion->query($sql_lectores);

if (isset($_POST['consultar'])) {
    $id_lector = $_POST['id_lector'];

    if (!empty($id_lector)) {
        //Obtenemos el nombre del lector
        $sql_nombre = "SELECT lector_nombre FROM lectores WHERE id_lector = $id_lector";
        $res_nombre = $conexion->query($sql_nombre);
        $lector = $res_nombre->fetch_assoc();
        $nombre_lector = $lector['lector_nombre'];

        // Buscar préstamos activos del lector
        $sql_prestamos = "
            SELECT libros.titulo, libros.autor, libros.anio_publicacion
            FROM prestamos
            INNER JOIN libros ON prestamos.id_libro = libros.id
            WHERE prestamos.id_lector = $id_lector;
        ";
        $resultado = $conexion->query($sql_prestamos);
        
        if ($resultado->num_rows == 0) {
            $mensaje = "¡$nombre_lector, anímate a leer algo nuevo hoy!";
        }
    } else {
        $mensaje = "Selecciona un lector válido";
    }
    
        
}

$conexion->close();
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
        <label for="id_lector">Selecciona un lector:</label><br>
        <select name="id_lector" id="id_lector" required>
            <option value="">-- Selecciona un lector --</option>
            <?php while ($fila = $lectores->fetch_assoc()) { ?>
                    <option value="<?php echo $fila['id_lector'] ?>"
                     <?php echo ($id_lector == $fila['id_lector']) ? 'selected' : '' ?>>
                     <?php echo htmlspecialchars($fila['lector_nombre']) ?>
                    </option>
                <?php } ?>
        </select>
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
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($fila["titulo"]) ?></td>
                    <td><?= htmlspecialchars($fila["autor"]) ?></td>
                    <td><?= htmlspecialchars($fila["anio_publicacion"]) ?></td>
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
