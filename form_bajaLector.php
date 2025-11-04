<?php
// Conexión a la base de datos
require_once('conecta.php');

// Si se envía el formulario de baja
if (isset($_POST['dar_baja'])) {
    $id_lector = $_POST['id_lector'];

    // Comprueba si el lector tiene préstamos pendientes
    $consulta = "SELECT n_prestados FROM lectores WHERE id_lector = $id_lector";
    $resultado = $conexion->query($consulta);

    if ($resultado && $resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        if ($fila['n_prestados'] == 0) {
            // Da de baja al lector si no tiene préstamos
            $baja = "UPDATE lectores SET estado = 'baja' WHERE id_lector = $id_lector";
            if ($conexion->query($baja)) {
                $mensaje = "Lector dado de baja con éxito";
            } else {
                $mensaje = "Error al dar de baja: " . $conexion->error;
            }
        } else {
            $mensaje = "Baja denegada. El lector tiene préstamos pendientes";
        }
    } else {
        // Por seguridad, si el lector no existe
        $mensaje = "Lector no encontrado";
    }
}

// Obtiene los lectores activos
$lectores = $conexion->query("SELECT id_lector, lector_nombre FROM lectores WHERE estado = 'activo'");

$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baja Lector</title>
</head>
<body>
    <h2>Baja de lector</h2>
    <?php 
        if (isset($mensaje)) {
            echo "<p><strong>$mensaje</strong></p>";
        }   
    ?>
    <!-- Formulario para dar de baja a un lector -->
    <form action="" method="post">
        <label for="id_lector">Selecciona un lector:</label>
        <select id="id_lector" name="id_lector" required>
            <option value="" disabled selected>--Selecciona un lector--</option>
            <?php while ($fila = $lectores->fetch_assoc()) { ?>
                <option value="<?php echo $fila['id_lector']; ?>">
                    <?php echo $fila['lector_nombre']; ?>
                </option>
            <?php } ?>
        </select>
        <br><br>
        <button type="submit" name="dar_baja">Dar de baja</button>
    </form>

    <br>
    <a href="index.php">Volver al menú</a>
</body>
</html>
