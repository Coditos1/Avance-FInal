<?php
    include "includes/header.php";
 
    $conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        echo "";
    }

   
    $selected_date_order = isset($_POST['filtro_fecha']) ? $_POST['filtro_fecha'] : '';
    $search_operator = isset($_POST['search_operator']) ? $_POST['search_operator'] : '';

   
    $sql = "SELECT f.id_failure, f.date, f.description, o.name AS reported_by
            FROM failure f
            JOIN operator o ON f.operator = o.id_operator";

   
    $conditions = [];
    if ($search_operator) {
        $conditions[] = "o.name LIKE '%$search_operator%'";
    }

    if (count($conditions) > 0) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    if ($selected_date_order) {
        if ($selected_date_order == 'antigua') {
            $sql .= " ORDER BY f.date ASC";
        } else {
            $sql .= " ORDER BY f.date DESC";
        }
    }

    $result = $conexion->query($sql);

    
    if (!$result) {
        die("Error en la consulta: " . $conn->error);
    }

    if (isset($_POST['id_failure'])) {
        $id_failure = $_POST['id_failure'];

        $delete_failures_query = "DELETE FROM failure_equipment WHERE failure = ?";
        $stmt_failures = $conexion->prepare($delete_failures_query);
        $stmt_failures->bind_param("i", $id_failure);
        $stmt_failures->execute();
        $stmt_failures->close();

        $sql = "DELETE FROM failure WHERE id_failure = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_failure);
        
        if ($stmt->execute()) {
            header("Location: Reporte.php");
            exit();
        } else {
            echo "<script>alert('Error al eliminar el reporte.');</script>";
        }
        $stmt->close();
    }
?>

    <style>
        .btn-accion {
            padding: 10px 15px;
            font-size: 12px;
            margin-left: 10px;
            cursor: pointer;
            width: 100px;
            height: auto;
            vertical-align: middle;
            background-color: #ff7f50; 
            border: none;
            border-radius: 5px;
            color: white;
            transition: background-color 0.3s;
        }

        .btn-accion:hover {
            background-color: #e76a3c; 
        }

        .filtros {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .filtros input[type="text"] {
            margin-right: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px; 
        }

        .filtro-fecha {
            margin-left: 10px;
        }

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
    </style>
    <main class="reporte-container">
        <section class="reportes-tabla">
            <div class="tabla-header">
                <h2>Report History</h2>
                <div class="filtros">
                    <form method="POST">
                        <input type="text" name="search_operator" placeholder="Search by operator" value="<?php echo htmlspecialchars($search_operator); ?>" autocomplete="off">
                        <button type="submit" class="btn-accion">Search</button>
                        <select name="filtro_fecha" class="filtro-fecha" onchange="this.form.submit()">
                            <option value="">Sort by Date</option>
                            <option value="antigua" <?php echo ($selected_date_order == 'antigua') ? 'selected' : ''; ?>>Oldest</option>
                            <option value="nueva" <?php echo ($selected_date_order == 'nueva') ? 'selected' : ''; ?>>Newest</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="tabla-container">
                <table>
                    <thead>
                        <tr>
                            <th>Report ID</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Reported By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>#REP" . $row['id_failure'] . "</td>
                                        <td>" . $row['date'] . "</td>
                                        <td>" . $row['description'] . "</td>
                                        <td>" . $row['reported_by'] . "</td>
                                        <td>
                                            <form method='POST' action='Supervisor.php' style='display:inline;'>
                                                <input type='hidden' name='id_failure' value='" . $row['id_failure'] . "'>
                                                <button type='submit' class='btn-accion generar'>Generate Order</button>
                                            </form>
                                            <form method='POST' action='Reporte.php' style='display:inline;'>
                                                <input type='hidden' name='id_failure' value='" . $row['id_failure'] . "'>
                                                <button type='submit' class='btn-accion eliminar' onclick='return confirm(\"¿Are you sure you want to delete this report?\");'>Delete</button>
                                            </form>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No reports found.</td></tr>";
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