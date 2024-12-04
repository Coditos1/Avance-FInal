<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$id_usuario = $_SESSION['id_user']; 
$sql = "SELECT * FROM technician WHERE id_technician = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$datos_usuario = $resultado->fetch_assoc();

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician - Work Orders</title>
    <link rel="stylesheet" href="css/tecnico.css">
    <link rel="stylesheet" href="css/modal.css">
    
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <ul>
                <li><a href="Tecnico.php">Work Orders</a></li>
                <li><a href="TecReport.php">Create Maintenance Report</a></li>
                <li><a href="ViewTecReports.php">View Reports</a></li>
                <li><button id="openModal" class="open-modal">My Profile</button></li>
                <form method="POST" action="logout.php" style="display:inline;">
                    <button type="submit" class="open-modal">Log Out</button>
                </form>
            </ul>
        </nav>

        <div id="modal" class="modal-container">
            <div class="modal-content">
                <span id="closeModal" class="close-modal">&times;</span>
                <h2>My Profile</h2>

                <div id="profile-info">
                    <div class="info-card">
                        <strong>Name:</strong> <span><?php echo htmlspecialchars($datos_usuario['name'] ?? ''); ?></span>
                    </div>
                    <div class="info-card">
                        <strong>Last Name:</strong> <span><?php echo htmlspecialchars($datos_usuario['lastName'] ?? ''); ?></span>
                    </div>
                    <div class="info-card">
                        <strong>Second Last Name:</strong> <span><?php echo htmlspecialchars($datos_usuario['secLastName'] ?? ''); ?></span>
                    </div>
                    <div class="info-card">
                        <strong>Phone Number:</strong> <span><?php echo htmlspecialchars($datos_usuario['numTel'] ?? ''); ?></span>
                    </div>
                    <div class="info-card">
                        <strong>Email:</strong> <span><?php echo htmlspecialchars($datos_usuario['email'] ?? ''); ?></span>
                    </div>
                    <button id="editProfileButton">Editar Información</button>
                </div>

                <form id="editProfileForm" action="actualizar_perfil.php" method="POST">
                    <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($datos_usuario['name'] ?? ''); ?>" required>

                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($datos_usuario['lastName'] ?? ''); ?>" required>

                    <label for="secLastName">Second Last Name:</label>
                    <input type="text" id="secLastName" name="secLastName" value="<?php echo htmlspecialchars($datos_usuario['secLastName'] ?? ''); ?>">

                    <label for="numTel">Phone Number:</label>
                    <input type="tel" id="numTel" name="numTel" value="<?php echo htmlspecialchars($datos_usuario['numTel'] ?? ''); ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($datos_usuario['email'] ?? ''); ?>" required>

                    <button type="submit">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
    <script src="modal.js"></script>
</body>
</html>