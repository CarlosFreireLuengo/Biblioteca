<?php
require_once('conecta.php');

if(isset($_POST["registrar"])){
    $nombre = $conexion->real_escape_string($_POST["nombre"]);
    $dni = $conexion->real_escape_string($_POST["dni"]);
    $estado = "activo";
    $n_prestados =0;

    //Comprobar si el lector ya está registrado
    $compdni = "SELECT * FROM lectores WHERE DNI = '$dni'";
    $resultado = $conexion->query($compdni);

    if($resultado->num_rows>0){
        $mensaje = "Ya existe un lector registrado con ese DNI";
    }else{
        $sql_addLector = "INSERT into lectores (lector_nombre, DNI, estado, n_prestados)
                        VALUES ('$nombre', '$dni', '$estado','$n_prestados')";

        if($conexion->query($sql_addLector)){
            $mensaje="Lector registrado con éxito";
        }else{
            $mensaje="Error al resgistrar el lector: ". $conexion->error;
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
        <title>Registar lector</title>
    </head>
    <body>
        <!--Formulario registro lectores-->
        <form action="" method="post">
            <h2>Registrar lector</h2>
            <?php 
            if (isset($mensaje)) {
                echo "<p><strong>$mensaje</strong></p>";
            }
            ?>
            <label for="nombre">Nombre y apellidos:</label>
            <input type="text" id="nombre" name="nombre" required><br><br>
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" required><br><br>

            <button type="submit" name="registrar">Registrar lector</button>

        </form>
    <br>
    <a href="index.php">Volver al menú</a>
    </body>
</html>