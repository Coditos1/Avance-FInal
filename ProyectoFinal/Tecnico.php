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
    die("Connection error: " . mysqli_connect_error());
}

$sql_ordenes = "SELECT wo.id_workOrders AS id_order, wo.creationDate AS assigned_date, wo.description, wo.status, e.name AS equipment_name 
                FROM work_orders wo 
                JOIN equipment e ON wo.equipment = e.id_equipment
                WHERE wo.id_user = ? AND wo.status = 'Pendiente'";
$stmt_ordenes = $conexion->prepare($sql_ordenes);
$stmt_ordenes->bind_param("i", $user_id);
$stmt_ordenes->execute();
$result_ordenes = $stmt_ordenes->get_result();
?>

<section id="ordenes">
    <h2>Assigned Work Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Assigned Date</th>
                <th>Description</th>
                <th>Status</th>
                <th>Equipment</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_ordenes->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_order']; ?></td>
                    <td><?php echo $row['assigned_date']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['equipment_name']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

</main>
</body>
</html>