<?php
    include 'includes/header.php';
        
    $conexion = mysqli_connect("127.0.0.1", "root", "", "industrial_maintenance");

    if (!$conexion) {
        die("Connection error: " . mysqli_connect_error());
    } else {
        echo "";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $lastName = $_POST['lastName'];
        $secLastName = $_POST['secLastName'];
        $numTel = $_POST['numTel'];
        $specialty = $_POST['specialty'];
        
        $email = strtolower($name . '.' . $lastName . $randomNumbers . '@autoindustry.com');
    
        $user = strtolower(substr($name, 0, 1) . $lastName . $randomNumbers);
    
        $password = substr(md5(uniqid()), 0, 8);

        $sql = "CALL RegisterTechnician(?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssss", $name, $lastName, $secLastName, $numTel, $email, $specialty, $user, $password);
        
        if ($stmt->execute()) {
            echo "<script>alert('New technician registered successfully.');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        
    }
?>
<main class="machines-container">
        <form id="register-technician-form" action="UserTecnico.php" method="POST" class="form-container">
            <h2>Technician Registration</h2>

            <label for="name">First Name:</label>
            <input type="text" id="name" name="name" required placeholder="Enter first name" oninput="validateLetters(this)">

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required placeholder="Enter last name" oninput="validateLetters(this)" >

            <label for="secLastName">Second Last Name:</label>
            <input type="text" id="secLastName" name="secLastName" placeholder="Enter second last name" oninput="validateLetters(this)">

            <label for="numTel">Phone Number:</label>
            <input type="text" id="numTel" name="numTel" required placeholder="(666) 666-6666" oninput="formatPhoneNumber(this)">

            <label for="specialty">Specialty:</label>
            <input type="text" id="specialty" name="specialty" required placeholder="Enter technician's specialty" oninput="validateLetters(this)">

            <button type="submit" name="submit">Register Technician</button>
        </form>
    </main>

    <script src="functions.js"></script>
</body>
</html>