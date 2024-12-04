<?php
session_start();
include "includes/header.php";

if (!isset($_SESSION['id_user'])) {
    echo "<div class='alert alert-danger'>Error: Administrator ID not found in session. Please log in.</div>";
    exit();
}

$conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

$search_user = isset($_POST['search_user']) ? $_POST['search_user'] : '';

$sql = "
    (SELECT id_operator AS id_user, name, lastname, email, numTel, 'operator' AS role FROM operator WHERE name LIKE '%" . mysqli_real_escape_string($conexion, $search_user) . "%' OR lastname LIKE '%" . mysqli_real_escape_string($conexion, $search_user) . "%')
    UNION
    (SELECT id_technician AS id_user, name, lastname, email, numTel, 'technician' AS role FROM technician WHERE name LIKE '%" . mysqli_real_escape_string($conexion, $search_user) . "%' OR lastname LIKE '%" . mysqli_real_escape_string($conexion, $search_user) . "%')
";

$result = $conexion->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];

    if ($role === 'technician') {
        $check_sql = "SELECT COUNT(*) as count FROM work_orders WHERE technician = ? AND status = 'Pendiente'";
        $stmt = $conexion->prepare($check_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result_check = $stmt->get_result();
        $row = $result_check->fetch_assoc();

        if ($row['count'] > 0) {
            echo "<script>alert('Cannot delete the technician. He has pending work orders.');</script>";
        } else {
            $delete_sql = "DELETE FROM technician WHERE id_technician = ?";
            $stmt = $conexion->prepare($delete_sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            echo "<script>alert('Technician successfully removed.'); window.location.href='DropUsers.php';</script>";
        }
    } else {
        $delete_sql = "DELETE FROM operator WHERE id_operator = ?";
        $stmt = $conexion->prepare($delete_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        echo "<script>alert('Operator successfully removed.'); window.location.href='DropUsers.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
</head>
<body>
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

    select#user_type {
        background-color: #f2f2f2;
        border: 1px solid #ccc; 
        border-radius: 5px;
        padding: 5px;
        width: 100%;
        cursor: not-allowed;
    }
</style>

<main class="reporte-container">
    <section class="reportes-tabla">
        <div class="tabla-header">
            <h2>All Users</h2>
            <div class="filtros">
                <form method="POST">
                    <input type="text" name="search_user" placeholder="Search by User" value="<?php echo htmlspecialchars($search_user); ?>" autocomplete="off">
                    <button type="submit" class="btn-accion">Search</button>
                </form>
            </div>
        </div>

        <table class="table-container">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>" . $row["id_user"] . "</td>
                                <td>" . htmlspecialchars($row["name"]) . "</td>
                                <td>" . htmlspecialchars($row["lastname"]) . "</td>
                                <td>" . htmlspecialchars($row["email"]) . "</td>
                                <td>" . htmlspecialchars($row["numTel"]) . "</td>
                                <td>
                                    <form method='POST' action='DropUsers.php' style='display:inline;'>
                                        <input type='hidden' name='user_id' value='" . $row["id_user"] . "'>
                                        <input type='hidden' name='role' value='" . $row["role"] . "'>
                                        <button type='submit' class='btn-accion eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este usuario?\");'>Delete</button>
                                         <button type='button' class='btn-accion actualizar' onclick='openUserModal(" . $row["id_user"] . ", \"" . htmlspecialchars($row["name"]) . "\", \"" . $row["role"] . "\", \"" . htmlspecialchars($row["lastname"]) . "\", \"" . htmlspecialchars($row["email"]) . "\", \"" . htmlspecialchars($row["numTel"]) . "\");'>Update</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay usuarios disponibles.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</main>

<div id="userModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeUserModal()">&times;</span>
        <h2>Update User</h2>
        <form id="updateUserForm" method="POST" action="">
            <input type="hidden" name="id_user" id="user_id">
            <label for="user_name">Name:</label>
            <input type="text" name="user_name" id="user_name" required>
            <label for="user_lastname">Last Name:</label>
            <input type="text" name="user_lastname" id="user_lastname" required>
            <label for="user_email">Email:</label>
            <input type="email" name="user_email" id="user_email" required>
            <label for="user_phone">Phone:</label>
            <input type="tel" name="user_phone" id="user_phone" required>
            <label for="user_type">User Type:</label>
            <select name="user_type" id="user_type" required disabled>
                <option value="operator">Operator</option>
                <option value="technician">Technician</option>
            </select>
            <button type="submit" class="btn-accion" style="margin-top: 15px;">Update</button>
        </form>
    </div>
</div>

<script>
function openUserModal(id, name, role, lastname, email, phone) {
    document.getElementById('user_id').value = id;
    document.getElementById('user_name').value = name;
    document.getElementById('user_lastname').value = lastname;
    document.getElementById('user_email').value = email;
    document.getElementById('user_phone').value = phone;
    document.getElementById('user_type').value = role.toLowerCase() === 'operator' ? 'operator' : 'technician';
    document.getElementById('user_type').disabled = true;
    document.getElementById('userModal').style.display = 'block';
}

function closeUserModal() {
    document.getElementById('userModal').style.display = 'none';
}

window.onclick = function(event) {
    var modal = document.getElementById('userModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<?php
$conexion->close();
?>
