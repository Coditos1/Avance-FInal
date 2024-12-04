<?php
include 'includes/header.php';

$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

$result_filter = isset($_POST['result_filter']) ? $_POST['result_filter'] : '';

$sql = "SELECT 
    e.name AS equipment_name,
    t.name AS technician_name,
    mh.completionDate,
    mh.results,
    mh.observations
FROM maintenance_history mh
JOIN equipment e ON mh.equipment = e.id_equipment
JOIN technician t ON mh.id_user = t.id_technician";

if ($result_filter) {
    $sql .= " WHERE mh.results = ?";
}

$sql .= " ORDER BY mh.completionDate DESC;";

$stmt = $conexion->prepare($sql);
if ($result_filter) {
    $stmt->bind_param("s", $result_filter);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<body>
    <style>
        .tabla-container {
            max-height: 75vh; 
            overflow-y: auto; 
            margin-top: 20px; 
        }

        table {
            width: 100%; 
            border-collapse: collapse; 
        }

        th, td {
            padding: 10px; 
            padding-left: 70px;
            text-align: center; 
            border-bottom: 1px solid #ddd; 
            width: 20%; 
        }

        th {
            background-color: #f2f2f2; 
        }

        .filter-form {
            margin: 20px 0; 
            padding: 15px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            background-color: #f9f9f9; 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
        }

        .filter-form label {
            font-weight: bold; 
            margin-right: 10px; 
        }

        .filter-form select {
            padding: 5px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            margin-right: 10px; 
        }

        .filter-form button {
            padding: 6px 12px; 
            background-color: orange; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            transition: background-color 0.3s; 
            width: 150px;
        }

        .filter-form button:hover {
            background-color: darkorange; 
        }
    </style>
    <main class="reporte-container">
    <section class="reportes-tabla">
    <h1>Maintenance Reports</h1>
    
    <form method="POST" action="" class="filter-form">
        <label for="result_filter">Filter by Result:</label>
        <select name="result_filter" id="result_filter">
            <option value="">All</option>
            <option value="Exitoso" <?php if ($result_filter == 'Exitoso') echo 'selected'; ?>>Exitoso</option>
            <option value="Requiere seguimiento" <?php if ($result_filter == 'Requiere seguimiento') echo 'selected'; ?>>Requiere seguimiento</option>
            <option value="No se pudo completar" <?php if ($result_filter == 'No se pudo completar') echo 'selected'; ?>>No se pudo completar</option>
        </select>
        <button type="submit">Filter</button>
    </form>

    <div class="tabla-container">
        <table>
            <thead>
                <tr>
                <th>Equipment Name</th>
                <th>Technician</th>
                <th>Completion Date</th>
                <th>Results</th>
                <th>Observations</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $estado_class = '';
                    switch ($row['results']) {
                        case 'Exitoso':
                            $estado_class = 'estado exitoso';
                            break;
                        case 'Pendiente':
                            $estado_class = 'estado seguimiento';
                            break;
                        case 'No se pudo completar':
                            $estado_class = 'estado no-completo';
                            break;
                    }
                    echo "<tr>
                            <td>{$row['equipment_name']}</td>
                            <td>{$row['technician_name']}</td>
                            <td>{$row['completionDate']}</td>
                            <td class='{$estado_class}'>{$row['results']}</td>
                            <td>{$row['observations']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No maintenance reports found.</td></tr>";
            }
            ?>
        </tbody>
        </div>
        </section>
    </main>
</body>
</html>