<?php
$conexion = new mysqli("localhost", "root", "", "biblioteca");

if($conexion->connect_error){
    die("Error de conexion: " .$conexion->connect_error);
}

//Seleccionamos los libros que tengan ejemplares disponibles
$sql= "SELECT * FROM libros WHERE n_disponibles > 0";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálgo de libros disponibles</title>
</head>
<body>
    <h2>Catálogo de libros disponibles</h2>

    <?php if($resultado->num_rows>0):?>
        <table>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Disponibles</th>
            </tr>
            <?php while($libro = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($libro['titulo'])?></td>
                    <td><?php echo htmlspecialchars($libro['autor'])?></td>
                    <td><?php echo ($libro['n_disponibles'])?></td>
                </tr>
            <?php endwhile;?>
        </table>
    <?php else: ?>
        <p>No hay libros disponibles para préstamo en este momento.</p>
    <?php endif; ?>
    <br>
    <a href="index.php">Volver al menú</a>
</body>
</html>