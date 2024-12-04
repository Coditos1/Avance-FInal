<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['user_type'] !== 'technician') {
    die("Acceso no autorizado");
}

$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_technician = $_SESSION['id_user'];
    $name = mysqli_real_escape_string($conexion, $_POST['name']);
    $lastName = mysqli_real_escape_string($conexion, $_POST['lastName']);
    $secLastName = mysqli_real_escape_string($conexion, $_POST['secLastName']);
    $numTel = mysqli_real_escape_string($conexion, $_POST['numTel']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);


    $check_sql = "SELECT id_technician FROM technician 
                  WHERE (email = ? OR numTel = ?) 
                  AND id_technician != ?";
                  
    $check_stmt = $conexion->prepare($check_sql);
    $check_stmt->bind_param("ssi", $email, $numTel, $id_technician);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        die("El email o número de teléfono ya está en uso por otro usuario.");
    }
    $check_stmt->close();

 
    $sql = "UPDATE technician 
            SET name = ?, 
                lastName = ?, 
                secLastName = ?, 
                numTel = ?, 
                email = ? 
            WHERE id_technician = ?";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("sssssi", $name, $lastName, $secLastName, $numTel, $email, $id_technician);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Actualización Exitosa</title>
                <style>
                        
                    .success-container {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        min-height: 100vh;
                        font-family: Arial, sans-serif;
                        background-color: #f5f5f5;
                    }
                    .success-message {
                        background-color: #4CAF50;
                        color: white;
                        padding: 20px 40px;
                        border-radius: 8px;
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    .success-icon {
                        font-size: 48px;
                        margin-bottom: 10px;
                    }
                    .redirect-message {
                        color: #666;
                        margin-bottom: 20px;
                    }
                    .back-button {
                        background-color: #007bff;
                        color: white;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 4px;
                        text-decoration: none;
                        transition: background-color 0.3s;
                    }
                    .back-button:hover {
                        background-color: #0056b3;
                    }
                </style>
            </head>
            <body>
                <div class="success-container">
                    <div class="success-message">
                        <div class="success-icon">✓</div>
                        <h2>¡Cambios Guardados Exitosamente!</h2>
                        <p>Tu información ha sido actualizada correctamente.</p>
                    </div>
                    <p class="redirect-message">Serás redirigido en 3 segundos...</p>
                    <a href="Tecnico.php" class="back-button">Volver al Inicio</a>
                </div>

                <script>
                    setTimeout(function() {
                        window.location.href = 'Tecnico.php';
                    }, 3000);
                </script>
            </body>
            </html>
            <?php
            exit();
        }
        $stmt->close();
    }
}

$conexion->close();
?>

<br><br>
<a href="Tecnico.php">Volver a la página del técnico</a> 