<?php
$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

if (!$conexion) {
    die("Connection error: " . mysqli_connect_error());
}


if (isset($_POST['delete_purchase'])) {
    $purchaseId = $_POST['delete_purchase'];


    $query = "DELETE FROM spare_purchases WHERE purchase_orders = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $purchaseId);
    
    if ($stmt->execute()) {
        echo "<script>alert('Purchase deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting the purchase.');</script>";
    }
    $stmt->close();
}

$adminId = $_SESSION['id_user']; 

if (empty($adminId)) {
    die("Administrator ID not found in session.");
}


$query = "SELECT sp.purchase_orders, sp.quantity, sp.amount, po.creationDate, s.name AS supplier_name
          FROM spare_purchases sp
          JOIN purchase_orders po ON sp.purchase_orders = po.id_purchaseOrders
          JOIN suppliers s ON po.suppliers = s.id_suppliers
          WHERE po.administrator = ?";
$stmt = $conexion->prepare($query);
if (!$stmt) {
    die("Error preparing the query: " . $conexion->error);
}
$stmt->bind_param("i", $adminId);
if (!$stmt->execute()) {
    die("Error executing the query: " . $stmt->error);
}
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spare Purchases</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <main class="compras-container">
        <h2>Spare Purchases</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Supplier</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    <th>Creation Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['purchase_orders'] . "</td>
                                <td>" . $row['supplier_name'] . "</td>
                                <td>" . $row['quantity'] . "</td>
                                <td>$" . number_format($row['amount'], 2) . "</td>
                                <td>" . $row['creationDate'] . "</td>
                                <td>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='delete_purchase' value='" . $row['purchase_orders'] . "'>
                                        <button type='submit' onclick='return confirm(\"Are you sure you want to delete this purchase?\");'>Delete</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No spare purchases found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>
<?php
?>

