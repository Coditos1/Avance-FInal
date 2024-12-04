<?php include "includes/header.php"; 

$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $repuesto = $_POST['repuesto'];
    $cantidad = $_POST['cantidad'];
    $proveedor = $_POST['proveedor'];
    $amount = 0;

    $query = "SELECT price FROM spare_parts WHERE id_spareParts = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $repuesto);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $stmt->close();

    $amount = $price * $cantidad;

    $status = 'Pendiente'; 
    $creationDate = date('Y-m-d H:i:s'); 
    $query = "INSERT INTO purchase_orders (creationDate, status, suppliers, administrator) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $adminId = 1; 
    $stmt->bind_param("ssii", $creationDate, $status, $proveedor, $adminId);
    $stmt->execute();
    $purchaseOrderId = $stmt->insert_id; 
    $stmt->close();

    $query = "INSERT INTO spare_purchases (spare_parts, purchase_orders, quantity, amount) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iiid", $repuesto, $purchaseOrderId, $cantidad, $amount);
    
    if ($stmt->execute()) {
        echo "<script>alert('Repuesto comprado con éxito.'); window.location.href='Repuesto.php';</script>";
    } else {
        echo "<script>alert('Error al comprar el repuesto.'); window.location.href='Repuesto.php';</script>";
    }

    $stmt->close();
}

$conexion->close();

?> 

<main class="compra-repuestos-container">
    <section class="form-container">
        <h2>Spare Parts Purchase Form</h2>
        <form id="compra-repuestos-form" method="POST" action="Repuesto.php">
            <label for="repuesto">Spare Part:</label>
            <select id="repuesto" name="repuesto" required>
                <option value="">Select a spare part</option>
                <?php
                $conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");
                if (!$conexion) {
                    die("Error en la conexión: " . mysqli_connect_error());
                }

                $query = "SELECT id_spareParts, name, price FROM spare_parts";
                $result = mysqli_query($conexion, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['id_spareParts'] . "' data-price='" . $row['price'] . "'>" . $row['name'] . " - $" . $row['price'] . "</option>";
                }
                ?>
            </select>

            <label for="cantidad">Quantity:</label>
            <input type="number" id="cantidad" name="cantidad" required placeholder="Enter the quantity" min="1" onchange="validateQuantity(this)">

            <label for="proveedor">Supplier:</label>
            <select id="proveedor" name="proveedor" required>
                <option value="">Select a supplier</option>
                <?php
                $query = "SELECT id_suppliers, name FROM suppliers";
                $result = mysqli_query($conexion, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['id_suppliers'] . "'>" . $row['name'] . "</option>";
                }
                ?>
            </select>

            <button type="submit">
                <i></i> Make Purchase
            </button>
        </form>
    </section>
</main>

<script>
    document.getElementById('compra-repuestos-form').addEventListener('change', function() {
        const repuestoSelect = document.getElementById('repuesto');
        const cantidadInput = document.getElementById('cantidad');
        const selectedOption = repuestoSelect.options[repuestoSelect.selectedIndex];
        const price = selectedOption ? parseFloat(selectedOption.getAttribute('data-price')) : 0;
        const quantity = parseInt(cantidadInput.value) || 0;
        const totalAmount = price * quantity;

        console.log("Total Amount: $" + totalAmount.toFixed(2));
    });

    function validateQuantity(input) {
        const value = parseInt(input.value);
        if (value <= 0) {
            alert("La cantidad debe ser un número positivo mayor que cero.");
            input.value = ""; 
        }
    }
</script>

</body>
</html>
