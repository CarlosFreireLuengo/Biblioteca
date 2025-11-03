<?php
// Datos de conexión
$servidor = "localhost";
$usuario = "root";
$password = "";
$db = "biblioteca";

// Conexión al servidor
$conexion = new mysqli($servidor, $usuario, $password);

// Verifica conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
} else {
    // Comprueba si existe la base de datos
    $sql = "SHOW DATABASES LIKE '$db'";
    if ($conexion->query($sql)->num_rows > 0) {
        $conexion->select_db($db); // La selecciona
    } else {
        include 'crea_tablas.php'; // La crea si no existe
    }
}

// Cierra la conexión
$conexion->close();
?>
