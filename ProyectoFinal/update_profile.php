<?php
session_start(); 

if (!isset($_SESSION['id_user']) || !isset($_SESSION['usuario'])) {
    die("No estás autenticado. Por favor inicia sesión.");
}

$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");
if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

$id_user = $_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $lastName = $_POST['lastName'];
    $secLastName = $_POST['secLastName'];
    $numTel = $_POST['numTel'];
    $email = $_POST['email'];

    $stmt = mysqli_prepare($conexion, "
        UPDATE administrator SET name = ?, lastName = ?, secLastName = ?, numTel = ?, email = ? 
        WHERE id_administrator = ?
    ");
    mysqli_stmt_bind_param($stmt, 'sssssi', $name, $lastName, $secLastName, $numTel, $email, $id_user);

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        header("Location: update_profile.php?message=success");
    } else {
        header("Location: update_profile.php?message=error");
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conexion);
?>






