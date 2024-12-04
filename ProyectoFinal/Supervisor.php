<?php
session_start();
include "includes/header.php";
$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

if (!isset($_SESSION['id_user'])) {
    echo "<div class='alert alert-danger'>Error: Administrator ID not found in session. Please log in.</div>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tecnico_id = $_POST['tecnico'];
    $equipo_id = $_POST['equipo'];
    $fecha_limite = $_POST['fecha-limite'];
    $tipo_mantenimiento_id = $_POST['tipo-mantenimiento'];
    $descripcion = $_POST['descripcion'];

    $administrator_id = $_SESSION['id_user']; 

    $sql_work_order = "INSERT INTO work_orders (description, equipment, technician, administrator, status, id_user, creationDate) VALUES (?, ?, ?, ?, 'Pendiente', ?, NOW())";
    $stmt_work_order = $conexion->prepare($sql_work_order);

    if (!$stmt_work_order) {
        echo "<div class='alert alert-danger'>Error preparing statement: " . $conexion->error . "</div>";
        exit();
    }

    $stmt_work_order->bind_param("siisi", $descripcion, $equipo_id, $tecnico_id, $administrator_id, $tecnico_id);

    if ($stmt_work_order->execute()) {
        $work_order_id = $conexion->insert_id;
        echo "<script>alert('Work order created successfully.');</script>";

        $sql_maintenance = "INSERT INTO maintenance (assignedDate, description, status, equipment, technician, maintenance_types) VALUES (?, ?, 'Pendiente', ?, ?, ?)";
        $stmt_maintenance = $conexion->prepare($sql_maintenance);

        if (!$stmt_maintenance) {
            echo "<div class='alert alert-danger'>Error preparing maintenance statement: " . $conexion->error . "</div>";
            exit();
        }

        $stmt_maintenance->bind_param("ssiii", $fecha_limite, $descripcion, $equipo_id, $tecnico_id, $tipo_mantenimiento_id);

        if ($stmt_maintenance->execute()) {
            echo "<div class='alert alert-success'></div>";
        } else {
            echo "<div class='alert alert-danger'>Error registering maintenance: " . $stmt_maintenance->error . "</div>";
        }
    } else {
        echo "<script>alert('Error registering work order: " . $stmt_work_order->error . "');</script>";
    }

    $stmt_work_order->close();
    $stmt_maintenance->close();
}

$sql_mtbf = "SELECT 
                (MAX(date) - MIN(date)) / COUNT(*) AS avg_mtbf
             FROM 
                failure";
$result_mtbf = $conexion->query($sql_mtbf);
$mtbf = $result_mtbf->fetch_assoc()['avg_mtbf'] ?? 0;

$sql_failure_rate = "SELECT COUNT(*) AS total_failures, 
                             (MAX(date) - MIN(date)) AS total_operation_time
                     FROM failure";
$result_failure_rate = $conexion->query($sql_failure_rate);
$data = $result_failure_rate->fetch_assoc();

$total_failures = $data['total_failures'];
$total_operation_time = $data['total_operation_time'] ?? 0;

$total_operation_hours = $total_operation_time ? $total_operation_time * 24 : 0;

$failure_rate = $total_operation_hours > 0 ? $total_failures / $total_operation_hours : 0;

$sql_oee = "SELECT (SUM(CASE WHEN status = 'Completada' THEN 1 ELSE 0 END) / COUNT(*)) * 100 AS oee
            FROM work_orders";
$result_oee = $conexion->query($sql_oee);
$oee = $result_oee->fetch_assoc()['oee'] ?? 0;

$sql_pending = "SELECT COUNT(*) AS pending_orders FROM work_orders WHERE status = 'Pendiente'";
$result_pending = $conexion->query($sql_pending);
$pending_orders = $result_pending->fetch_assoc()['pending_orders'] ?? 0;

$sql_history = "SELECT mh.completionDate, e.name AS equipment_name, mh.observations, m.status
                FROM maintenance_history mh
                JOIN equipment e ON mh.equipment = e.id_equipment
                JOIN maintenance m ON mh.maintenance = m.id_maintenance
                ORDER BY mh.completionDate DESC
                LIMIT 3";
$result_history = $conexion->query($sql_history);
?>

<main class="collage-container">
    <section id="crear-orden" class="collage-item">
        <div class="card-content">
            <h2><i></i> Create Work Order</h2>
            <form id="orden-form" action="Supervisor.php" method="POST">
                <label for="tecnico">Technician:</label>
                <select id="tecnico" name="tecnico" required>
                    <option value="">Select a technician</option>
                    <?php
                    $sql_tecnico = "SELECT id_technician, name, lastName FROM technician";
                    $result_tecnico = $conexion->query($sql_tecnico);

                    if ($result_tecnico->num_rows > 0) {
                        while ($row = $result_tecnico->fetch_assoc()) {
                            echo "<option value='" . $row['id_technician'] . "'>" . $row['name'] . " " . $row['lastName'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No technicians available</option>";
                    }
                    ?>
                </select>

                <label for="equipo">Equipment:</label>
                <select id="equipo" name="equipo" required>
                    <option value="">Select equipment</option>
                    <?php
                    $sql_equipo = "SELECT id_equipment, name FROM equipment WHERE status = 'Operativo'";
                    $result_equipo = $conexion->query($sql_equipo);

                    if ($result_equipo->num_rows > 0) {
                        while ($row = $result_equipo->fetch_assoc()) {
                            echo "<option value='" . $row['id_equipment'] . "'>" . $row['name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No equipment available</option>";
                    }
                    ?>
                </select>

                <label for="fecha-limite">Due Date:</label>
                <input type="date" id="fecha-limite" name="fecha-limite" required onchange="validateDate(this)">

                <label for="tipo-mantenimiento">Type of Maintenance:</label>
                <select id="tipo-mantenimiento" name="tipo-mantenimiento" required>
                    <option value="">Select a type of maintenance</option>
                    <?php
                    $sql_mantenimiento = "SELECT id_maintType, name FROM maintenance_types";
                    $result_mantenimiento = $conexion->query($sql_mantenimiento);

                    if ($result_mantenimiento->num_rows > 0) {
                        while ($row = $result_mantenimiento->fetch_assoc()) {
                            echo "<option value='" . $row['id_maintType'] . "'>" . $row['name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No maintenance types available</option>";
                    }
                    ?>
                </select>

                <label for="descripcion">Description of Work Order:</label>
                <textarea 
                    id="descripcion" 
                    name="descripcion" 
                    required 
                    rows="6"
                    style="max-height: 150px; min-height: 100px; resize: vertical;"
                    placeholder="Specify: 1. Work to be done&#10;2. Necessary parts&#10;3. Required tools&#10;4. Safety measures">
                </textarea>

                <button type="submit">
                    <i></i> Generate Work Order
                </button>
            </form>
        </div>
    </section>

    <section id="comprar-repuestos" class="collage-item">
        <div class="card-content">
            <h2><i class="fas fa-tools"></i> Spare Parts Inventory</h2>
            <div class="repuestos-grid">
                <?php
                $sql = "SELECT id_spareParts, name, stock, price FROM spare_parts LIMIT 3";
                $result = $conexion->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='repuesto-card'>";
                        echo "<span class='stock " . ($row['stock'] < 3 ? 'critico' : 'bajo') . "'>Stock: " . htmlspecialchars($row['stock']) . "</span>";
                        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                        echo "<p class='specs'>Part Description</p>";
                        echo "<p class='precio'>$" . htmlspecialchars($row['price']) . "/unit</p>";
                        echo "<a href='Repuesto.php?id=" . htmlspecialchars($row['id_spareParts']) . "'><button>Request Purchase</button></a>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='repuesto-card'><p>No spare parts available.</p></div>";
                }
                ?>
            </div>
            <button>
                <a href="ListSprParts.php" style="text-decoration: none; color: white;">
                    <i></i> View Full Inventory
                </a>
            </button>
        </div>
    </section>

    <section id="reportes-mantenimiento" class="collage-item">
        <div class="card-content">
            <h2><i></i> Maintenance Metrics</h2>
            <div class="stats-container">
                <div class="stat-card">
                    <h4>MTBF</h4>
                    <p class="stat-number"><?php echo round($mtbf, 2) . 'h'; ?></p>
                    <p class="stat-label">Mean Time Between Failures</p>
                </div>
                <div class="stat-card">
                    <h4>Failure Rate</h4>
                    <p class="stat-number"><?php echo round($failure_rate, 4); ?></p>
                    <p class="stat-label">Failures per Hour of Operation</p>
                </div>
                <div class="stat-card">
                    <h4>OEE</h4>
                    <p class="stat-number"><?php echo round($oee, 2) . '%'; ?></p>
                    <p class="stat-label">Overall Efficiency</p>
                </div>
                <div class="stat-card">
                    <h4>Pending</h4>
                    <p class="stat-number"><?php echo $pending_orders; ?></p>
                    <p class="stat-label">Active Orders</p>
                </div>
            </div>
        </div>
    </section>

    <section id="historial-mantenimiento" class="collage-item">
        <div class="card-content">
            <h2><i></i> Maintenance History</h2>
            <div class="timeline">
                <?php
                if ($result_history->num_rows > 0) {
                    while ($row = $result_history->fetch_assoc()) {
                        echo "<div class='timeline-item " . strtolower($row['status']) . "'>";
                        echo "<div class='date'>" . htmlspecialchars($row['completionDate']) . "</div>";
                        echo "<div class='content'>";
                        echo "<strong>" . htmlspecialchars($row['equipment_name']) . "</strong>";
                        echo "<p>" . htmlspecialchars($row['observations']) . "</p>";
                        echo "<span class='status " . strtolower($row['status']) . "'>" . htmlspecialchars(ucfirst($row['status'])) . "</span>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='timeline-item'><div class='content'><p>No maintenance history available.</p></div></div>";
                }
                ?>
            </div>
            <button>
                <a href="Historial.php" style="text-decoration: none; color: white;">
                    <i></i> View Full History
                </a>
            </button>
        </div>
    </section>
</main>


<script src="functions.js"></script>
</body>

</html>
