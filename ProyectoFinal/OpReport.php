<?php
session_start(); // Asegúrate de que la sesión esté iniciada

// Conexión a la base de datos
$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Obtener el ID del operador que ha iniciado sesión
$operatorId = $_SESSION['id_user']; // Asegúrate de que el ID del operador esté almacenado en la sesión

if (empty($operatorId)) {
    die("No se ha encontrado el ID del operador en la sesión.");
}

// Obtener los reportes de fallas del operador
$query = "SELECT f.id_failure, f.date, f.description, e.name AS equipment_name
          FROM failure f
          JOIN equipment e ON f.equipment = e.id_equipment
          WHERE f.operator = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $operatorId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Fallas</title>
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate de tener un archivo CSS -->
</head>
<body>
    <?php include 'includes/opnav.php'; ?> <!-- Asegúrate de que este archivo no tenga etiquetas HTML adicionales -->
    
    <main class="report-container">
        <h2>Reportes de Fallas</h2>
        <form method="POST" action="">
            <label for="result_filter">Filter by Result:</label>
            <select name="result_filter" id="result_filter">
                <option value="">All</option>
                <option value="Successful" <?php if ($result_filter == 'Successful') echo 'selected'; ?>>Successful</option>
                <option value="Requires follow-up" <?php if ($result_filter == 'Requires follow-up') echo 'selected'; ?>>Requires follow-up</option>
                <option value="Could not complete" <?php if ($result_filter == 'Could not complete') echo 'selected'; ?>>Could not complete</option>
            </select>
            <button type="submit">Filter</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID of Failure</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Equipment</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['id_failure'] . "</td>
                                <td>" . $row['date'] . "</td>
                                <td>" . $row['description'] . "</td>
                                <td>" . $row['equipment_name'] . "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No se encontraron reportes de fallas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>

<?php
// Cerrar conexión
$conexion->close();
?>
