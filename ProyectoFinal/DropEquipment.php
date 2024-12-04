<!DOCTYPE html>
<html lang="es">
<head>
    <?php include "includes/header.php"; 
    $conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $search_equipment = isset($_POST['search_equipment']) ? $_POST['search_equipment'] : '';

    $sql = "SELECT id_equipment, name, equipment_location FROM equipment";
    $conditions = [];

    if (count($conditions) > 0) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $result = $conexion->query($sql);

    if (!$result) {
        die("Error en la consulta: " . $conexion->error);
    }

    $locations_query = "SELECT DISTINCT equipment_location FROM equipment";
    $locations_result = $conexion->query($locations_query);
    $locations = [];
    if ($locations_result) {
        while ($row = $locations_result->fetch_assoc()) {
            $locations[] = $row['equipment_location'];
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_equipment'])) {
        $id_equipment = $_POST['id_equipment'];
        $equipment_name = $_POST['equipment_name'];
        $equipment_location = $_POST['equipment_location'];

        if (!empty($id_equipment) && !empty($equipment_name) && !empty($equipment_location)) {
            $update_sql = "UPDATE equipment SET name = ?, equipment_location = ? WHERE id_equipment = ?";
            $stmt = mysqli_prepare($conexion, $update_sql);
            mysqli_stmt_bind_param($stmt, "ssi", $equipment_name, $equipment_location, $id_equipment);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: DropEquipment.php?update=success");
                exit();
            } else {
                echo "Error al actualizar el equipo: " . mysqli_error($conexion);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Por favor, complete todos los campos.";
        }
    }
    ?>
    <meta charset="UTF-8">
    <title>Lista de Equipos</title>
    <style>
        * {
            box-sizing: border-box;
        }

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

        .tabla-container {
            max-height: 75vh;
            overflow-y: auto;
            margin-top: 20px;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            width: 20%;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .equipment-list {
            margin-top: 20px;
        }

        .equipment-list input[type="text"] {
            margin-right: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
        }

        .equipment-list .btn-accion {
            margin-left: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            background-color: #fff;
            font-size: 16px;
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><polygon points="0,0 10,0 5,5" fill="%23333"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 10px;
        }

        select:hover {
            border-color: #ff7f50;
        }

        select:focus {
            outline: none;
            border-color: #ff7f50;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<main class="reporte-container">
    <?php
    if (isset($_POST['id'])) {
        $equipment_id = $_POST['id'];

        $delete_failures_query = "DELETE FROM failure_equipment WHERE equipment = ?";
        $stmt_failures = mysqli_prepare($conexion, $delete_failures_query);
        mysqli_stmt_bind_param($stmt_failures, "i", $equipment_id);
        mysqli_stmt_execute($stmt_failures);
        mysqli_stmt_close($stmt_failures);

        $delete_query = "DELETE FROM equipment WHERE id_equipment = ?";
        $stmt = mysqli_prepare($conexion, $delete_query);
        mysqli_stmt_bind_param($stmt, "i", $equipment_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    $query = "SELECT id_equipment, name, equipment_location FROM equipment";
    $result = mysqli_query($conexion, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table><tr><th>ID</th><th>Name</th><th>Ubication</th><th>Action</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row["id_equipment"] . "</td><td>" . $row["name"] . "</td><td>" . $row["equipment_location"] . "</td>
                  <td><button class='btn-accion' onclick='openModal(" . $row["id_equipment"] . ", \"" . addslashes($row["name"]) . "\", \"" . addslashes($row["equipment_location"]) . "\")'>Actualizar</button>
                      <form method='POST' action='DropEquipment.php' style='display:inline;'>
                          <input type='hidden' name='id' value='" . $row["id_equipment"] . "'>
                          <button type='submit' class='btn-accion eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este equipo?\");'>Eliminar</button>
                      </form>
                  </td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay equipos disponibles.</p>";
    }

    mysqli_close($conexion);
    ?>
        </div>
    </section>
</main>

<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Update Equipment</h2>
        <form id="updateForm" method="POST" action="DropEquipment.php">
            <input type="hidden" name="id_equipment" id="equipment_id">
            <label for="equipment_name">Name:</label>
            <input type="text" name="equipment_name" id="equipment_name" required>
            <label for="equipment_location">Location:</label>
            <select name="equipment_location" id="equipment_location" required>
                <option value="">Select Location</option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?php echo htmlspecialchars($location); ?>"><?php echo htmlspecialchars($location); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn-accion" style="margin-top: 15px;">Update</button>
        </form>
    </div>
</div>

<script>
function openModal(id, name, location) {
    document.getElementById('equipment_id').value = id;
    document.getElementById('equipment_name').value = name;
    document.getElementById('equipment_location').value = location;
    document.getElementById('updateModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('updateModal').style.display = 'none';
}

window.onclick = function(event) {
    var modal = document.getElementById('updateModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

</body>
</html>
