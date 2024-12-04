<?php
include 'includes/header.php';

$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

if (!$conexion) {
    die("Error in connection: " . mysqli_connect_error());
}

if (isset($_POST['delete_order'])) {
    $orderId = $_POST['delete_order'];

    $query = "DELETE FROM work_orders WHERE id_workOrders = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $orderId);
    
    if ($stmt->execute()) {
        echo "<script>alert('Work order deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting the work order.');</script>";
    }
    $stmt->close();
}

if (isset($_POST['order_id']) && isset($_POST['new_description'])) {
    $orderId = $_POST['order_id'];
    $newDescription = $_POST['new_description'];

    $query = "UPDATE work_orders SET description = ? WHERE id_workOrders = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("si", $newDescription, $orderId);
    
    if ($stmt->execute()) {
        echo "<script>alert('Description updated successfully.');</script>";
    } else {
        echo "<script>alert('Error updating the description.');</script>";
    }
    $stmt->close();
}

$administratorId = $_SESSION['id_user'];

if (empty($administratorId)) {
    die("No se ha encontrado el ID del supervisor en la sesión.");
}

$query = "SELECT id_workOrders, description, creationDate, status
          FROM work_orders
          WHERE administrator = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $administratorId);
$stmt->execute();
$result = $stmt->get_result();
?>

<body>
    <main class="reporte-container">
    <section class="reportes-tabla">
        <h2>Work Orders</h2>

        <div class="tabla-container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Description</th>
                    <th>Creation Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['id_workOrders'] . "</td>
                                <td>" . $row['description'] . "</td>
                                <td>" . $row['creationDate'] . "</td>
                                <td>" . $row['status'] . "</td>
                                <td>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='delete_order' value='" . $row['id_workOrders'] . "'>
                                        <button type='submit' class='btn-accion' onclick='return confirm(\"Are you sure you want to delete this work order?\");' style='margin-right: 10px;'>Delete</button>
                                    </form>
                                    <button class='btn-accion' onclick='openModal(" . $row['id_workOrders'] . ", \"" . addslashes($row['description']) . "\")'>Update</button>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No work orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        </div>
    </section>
    </main>

    <div id="updateModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Update Description</h2>
            <form method="POST" id="updateForm">
                <input type="hidden" name="order_id" id="order_id">
                <label for="new_description">New Description:</label>
                <input type="text" name="new_description" id="new_description" required>
                <button type="submit">Update</button>
            </form>
        </div>
    </div>

    <style>
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

    <script>
    function openModal(orderId, currentDescription) {
        document.getElementById('order_id').value = orderId;
        document.getElementById('new_description').value = currentDescription;
        document.getElementById('updateModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('updateModal').style.display = 'none';
    }
    </script>
</body>
</html>
