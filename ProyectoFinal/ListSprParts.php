<?php
    include 'includes/header.php';

    $conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        echo "";
    }

$sql = "SELECT id_spareParts, name, stock FROM spare_parts";
$sql = "SELECT id_spareParts, name, stock FROM spare_parts";
$result = $conexion->query($sql);
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
</style>
<main class="reporte-container">
    <section class="reportes-tabla">
    <h1>Spare Parts List</h1>

<div class="tabla-container">
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id_spareParts']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No spare parts available</td></tr>";
        }
        ?>
    </tbody>
</table>
</div>
</section>
</main>