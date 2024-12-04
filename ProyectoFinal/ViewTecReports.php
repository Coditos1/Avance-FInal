<?php
include 'includes/tecnav.php';
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id_user'];

$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

$sql_reports = "SELECT mh.id_history, mh.completionDate, mh.results, mh.observations, mh.equipment, mh.maintenance, sp.name AS spare_part_name, sh.usedQuantity, sh.usageDate 
                FROM maintenance_history mh
                LEFT JOIN spare_history sh ON mh.id_history = sh.maintenance_history
                LEFT JOIN spare_parts sp ON sh.spare_parts = sp.id_spareParts
                WHERE mh.id_user = ?";
$stmt_reports = $conexion->prepare($sql_reports);
$stmt_reports->bind_param("i", $user_id);
$stmt_reports->execute();
$result_reports = $stmt_reports->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Mantenimiento</title>
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

        textarea {
        width: 100%; 
        padding: 10px; 
        border: 1px solid #ccc; 
        border-radius: 4px; 
        resize: none; 
        margin-top: 5px; 
    }

    button[type="submit"] {
        background-color: orange; 
        color: white; 
        border: none; 
        padding: 10px 15px; 
        border-radius: 4px; 
        cursor: pointer; 
        margin-top: 15px; 
            width: 100%; 
        }

        button[type="submit"]:hover {
            background-color: darkorange; 
        }
    </style>
</head>
<body>
    <section id="ordenes">
        <h2>Maintenance Reports</h2>
        <table>
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Maintenance Date</th>
                    <th>Results</th>
                    <th>Observations</th>
                    <th>Equipment</th>
                    <th>Used Spare Part</th>
                    <th>Used Quantity</th>
                    <th>Usage Date</th>
                    <th>Actions</th> 
                </tr>
            </thead>
            <tbody>
                <?php if ($result_reports->num_rows > 0): ?>
                    <?php while ($row = $result_reports->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_history']); ?></td>
                            <td><?php echo htmlspecialchars($row['completionDate']); ?></td>
                            <td><?php echo htmlspecialchars($row['results']); ?></td>
                            <td><?php echo htmlspecialchars($row['observations']); ?></td>
                            <td><?php echo htmlspecialchars($row['equipment']); ?></td>
                            <td><?php echo htmlspecialchars($row['spare_part_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['usedQuantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['usageDate']); ?></td>
                            <td>
                                <button class="edit-button" onclick="openModal(<?php echo $row['id_history']; ?>, '<?php echo htmlspecialchars($row['observations']); ?>')">Edit</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No maintenance reports available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Observations</h2>
            <form id="editForm" method="POST" action="ViewTecReports.php">
                <input type="hidden" id="reportId" name="reportId">
                <label for="observations">Observations:</label>
                <textarea id="observations" name="observations" rows="4" required></textarea>
                <button type="submit">Update Report</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id, observations) {
            document.getElementById("reportId").value = id; 
            document.getElementById("observations").value = observations; 
            document.getElementById("editModal").style.display = "block"; 
        }

        function closeModal() {
            document.getElementById("editModal").style.display = "none"; 
        }
        
        window.onclick = function(event) {
            var modal = document.getElementById("editModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>

<?php
$stmt_reports->close();
$conexion->close();
?>
