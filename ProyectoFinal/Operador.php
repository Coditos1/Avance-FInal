<?php
session_start(); 

include 'includes/opnav.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

if (!$conexion) {
    die("Connection error: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['fecha'];
    $description = $_POST['descripcion'];
    $equipment_id = $_POST['equipo'];

    $operator_id = $_SESSION['id_user']; 

    $sql_failure = "INSERT INTO failure (date, description, operator) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql_failure);
    $stmt->bind_param("ssi", $date, $description, $operator_id);

    if ($stmt->execute()) {
        $failure_id = $conexion->insert_id;

        $sql_equipment = "INSERT INTO failure_equipment (failure, equipment) VALUES (?, ?)";
        $stmt_equipment = $conexion->prepare($sql_equipment);
        $stmt_equipment->bind_param("ii", $failure_id, $equipment_id);

        if ($stmt_equipment->execute()) {
            echo "<div class='alert alert-success mt-3'>Report submitted successfully.</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>Error registering the equipment: " . $stmt_equipment->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger mt-3'>Error registering the failure: " . $stmt->error . "</div>";
    }

    $stmt->close();
    if (isset($stmt_equipment)) {
        $stmt_equipment->close();
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator</title>
    <link rel="stylesheet" href="css/tecnico.css">
</head>
<body>
    <section class="reporte-operador">
        <h2>Failure Report</h2>
        <form action="Operador.php" method="POST">
            <div class="form-group">
                <label for="fecha">Report Date:</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required onchange="validateDateVolII(this)">
            </div>
            <div class="form-group">
                <label for="descripcion">Failure Description:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="equipo">Affected Equipment:</label>
                <select class="form-control" id="equipo" name="equipo" required>
                    <?php
                    $conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

                    if (!$conexion) {
                        die("Connection error: " . mysqli_connect_error());
                    }

                    $sql_equipo = "SELECT id_equipment, name FROM equipment WHERE status = 'Operativo'";
                    $result = $conexion->query($sql_equipo);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id_equipment'] . "'>" . $row['name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No equipment available</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit Report</button>
        </form>
    </section>
    <script src="functions.js"></script>
</body>
</html>