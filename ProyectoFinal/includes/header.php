<?php
session_start(); 

$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

if (isset($_SESSION['username'])) {
    $current_username = $_SESSION['username'];
} else {
    $current_username = "Invitado";
}

$id_usuario = $_SESSION['id_user']; 
$sql = "SELECT * FROM administrator WHERE id_administrator = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$datos_usuario = $resultado->fetch_assoc();
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Mantenimiento</title>
    <link rel="stylesheet" href="css/styles2.css">
</head>

<body>
    <header>
        <nav>
            <h1><a href="Supervisor.php" style="text-decoration: none; color: inherit;">Administrator Page</a></h1>
            <ul>
                <li><a href="Reporte.php"><i class="fas fa-exclamation-triangle"></i>View Failure Reports</a></li>
                <li><a href="Repuesto.php"><i class="fas fa-file-alt"></i>Spare Parts for Maintenance</a></li>
                <li><a href="WtchOdrWorks.php"><i class="fas fa-file-alt"></i>Watch Work Orders</a></li>
                <li><a href="ReportarFalla.php"><i class="fas fa-tools"></i>Maintenance Reports</a></li>
                <li><a href="Historial.php"><i class="fas fa-chart-line"></i>Maintenance History</a></li>
                <li><a href="Maquinas.php"><i class="fas fa-history"></i>Register Equipment</a></li>
                <li><a href="DropEquipment.php"><i class="fas fa-history"></i>Drop Equipment</a></li>
                <li><a href="DropUsers.php"><i class="fas fa-history"></i>Watch All Users</a></li>
                <li><a href="UserOperador.php"><i class="fas fa-history"></i>Register an Operator</a></li>
                <li><a href="UserTecnico.php"><i class="fas fa-history"></i>Register a Technician</a></li>
                <li><button id="openModal" class="open-modal">Profile</button></li>
                <li>
                    <form method="POST" action="logout.php" style="display:inline;">
                        <button type="submit" class="open-modal">Log Out</button>
                    </form>
                </li>
            </ul>
        </nav>

    </header>

    <div id="modal" class="modal-container">
    <div class="modal-content">
        <span id="closeModal" class="close-modal">&times;</span>
        <h2>My Profile</h2>

    
    <div id="profile-info">
    <div class="info-card">
        <strong>Name:</strong> <span><?php echo htmlspecialchars($datos_usuario['name'] ?? ''); ?></span>
    </div>
    <div class="info-card">
        <strong>LastName:</strong> <span><?php echo htmlspecialchars($datos_usuario['lastName'] ?? ''); ?></span>
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

    <button id="editProfileButton">Edit Information</button>
</div>


       
<form id="editProfileForm" action="" method="POST" style="display: none;">
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
<script src="modal.js"></script>
<?php
if (isset($_GET['message'])) {
    $message = $_GET['message'];

    if ($message == 'success') {
        echo '<div class="alert success">Cambio Exitoso</div>';
    } elseif ($message == 'error') {
        echo '<div class="alert error">Se produjo un error</div>';
    }
}
?>