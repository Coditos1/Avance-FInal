<?php
$servername = "127.0.0.1";
$user = "root";
$password = "";
$database = "industrial_maintenance";


$conexion = mysqli_connect($servername, $user, $password, $database);

if (!$conexion) {
    die("Error al conectar: " . mysqli_connect_error());
}
echo "Conexion exitosa";
mysqli_close($conexion);
?>