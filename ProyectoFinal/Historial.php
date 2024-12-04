<?php
    include 'includes/header.php';

    $conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $selected_date_order = isset($_POST['filtro_fecha']) ? $_POST['filtro_fecha'] : 'nueva';

    $sql_history = "SELECT mh.id_history, mh.completionDate AS maintenance_date, mh.results, mh.observations, e.name AS equipment
                    FROM maintenance_history mh
                    JOIN equipment e ON mh.equipment = e.id_equipment
                    WHERE 1=1";

    if ($selected_date_order == 'antigua') {
        $sql_history .= " ORDER BY mh.completionDate ASC";
    } else {
        $sql_history .= " ORDER BY mh.completionDate DESC";
    }

    $result_history = $conexion->query($sql_history);

    if (!$result_history) {
        die("Error en la consulta: " . $conexion->error);
    }
?>

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
        <div class="tabla-header">
            <h2>Maintenance History</h2>
        </div>

        <form method="POST" action="" class="filter-form">
            <label for="filtro_fecha">Order by Date:</label>
            <select id="filtro_fecha" name="filtro_fecha" onchange="this.form.submit()">
                <option value="nueva" <?php echo ($selected_date_order == 'nueva') ? 'selected' : ''; ?>>Newest to Oldest</option>
                <option value="antigua" <?php echo ($selected_date_order == 'antigua') ? 'selected' : ''; ?>>Oldest to Newest</option>
            </select>
        </form>

        <div class="tabla-container">
            <table>
                <thead>
                    <tr>
                        <th>Maintenance ID</th>
                        <th>Maintenance Date</th>
                        <th>Results</th>
                        <th>Observations</th>
                        <th>Equipment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_history->num_rows > 0) {
                        while($row = $result_history->fetch_assoc()) {
                            echo "<tr>
                                    <td>#MANT" . $row['id_history'] . "</td>
                                    <td>" . $row['maintenance_date'] . "</td>
                                    <td>" . $row['results'] . "</td>
                                    <td>" . $row['observations'] . "</td>
                                    <td>" . $row['equipment'] . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No maintenance records found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<?php
$conexion->close();
?>


