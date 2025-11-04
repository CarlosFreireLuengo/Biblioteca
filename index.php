<?php 
    //Antonio Alonso Alonso y Carlos Freire Luengo
    require_once('conecta.php');

    //Cierra la conexión tras crear o comprobar la BD
    $conexion->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de la biblioteca</title>
</head>
<body>
    <h1>Gestión de la Biblioteca</h1>
    <h2>Antonio Alonso Alonso y Carlos Freire Luengo</h2>
    <div id="container">
        
        <form action="form_registrarLector.php">
            <button type="submit">Registrar lector</button>
        </form>

        <form action="form_realizarPrestamo.php">
            <button type="submit">Realizar préstamo</button>
        </form>

         <form action="form_devolverPrestamo.php">
            <button type="submit">Devolver préstamo</button>
        </form>

         <form action="form_addLibro.php">
            <button type="submit">Añadir libro</button>
        </form>

         <form action="form_bajaLector.php">
            <button type="submit">Dar de baja lector</button>
        </form>

         <form action="form_prestamosRealizados.php">
            <button type="submit">Consultar préstamos</button>
        </form>

        <form action="catalogo_libros.php">
            <button type="submit">Consultar libros disponibles</button>
        </form>
    </div>
</body>
</html>