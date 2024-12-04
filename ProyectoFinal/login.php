<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); 
session_start(); 

$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario']) && isset($_POST['contraseña'])) {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    try {
        $stmt = mysqli_prepare($conexion, "SELECT id_administrator, user FROM administrator WHERE user = ? AND password = ?");
        mysqli_stmt_bind_param($stmt, 'ss', $usuario, $contraseña);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) > 0) {
            $row = mysqli_fetch_assoc($resultado);
            $_SESSION['usuario'] = $usuario; 
            $_SESSION['id_user'] = $row['id_administrator']; 
            $_SESSION['user_type'] = 'administrator'; 
            header("Location: Supervisor.php");
            exit();
        }

        $stmt = mysqli_prepare($conexion, "SELECT id_operator, user FROM operator WHERE user = ? AND password = ?");
        mysqli_stmt_bind_param($stmt, 'ss', $usuario, $contraseña);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) > 0) {
            $row = mysqli_fetch_assoc($resultado);
            $_SESSION['usuario'] = $usuario; 
            $_SESSION['id_user'] = $row['id_operator']; 
            $_SESSION['user_type'] = 'operator'; 
            header("Location: Operador.php");
            exit();
        }

        $stmt = mysqli_prepare($conexion, "SELECT id_technician, user FROM technician WHERE user = ? AND password = ?");
        mysqli_stmt_bind_param($stmt, 'ss', $usuario, $contraseña);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) > 0) {
            $row = mysqli_fetch_assoc($resultado);
            $_SESSION['usuario'] = $usuario; 
            $_SESSION['id_user'] = $row['id_technician']; 
            $_SESSION['user_type'] = 'technician'; 
            header("Location: Tecnico.php");
            exit();
        }

        echo "<h2>Tus credenciales son incorrectas. Por favor ingresalas correctamente</h2>";
    } catch (mysqli_sql_exception $e) {
        echo "<h2>Ocurrió un error al procesar su solicitud. Por favor, inténtelo más tarde.</h2>";
        error_log($e->getMessage());
    }
} else {
    echo "<h1>Por favor, ingresa tus credenciales.</h1>";
}
?>

