<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$db = "biblioteca";

$conexion = new mysqli($servidor, $usuario, $password);
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
} else {
    $sql = "SHOW DATABASES LIKE '$db'";
    if ($conexion->query($sql)->num_rows > 0) {
        $conexion->select_db($db);
    } else {
        include 'crea_tablas.php';
    }
}

$conexion->close();
?>