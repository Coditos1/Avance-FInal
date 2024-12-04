<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<style>
        body {
            background-color: #f0f0f0; /* Color de fondo gris */
        }
        .btn-orange {
            background-color: #ff9800;
            color: white;
        }
        .btn-orange:hover {
            background-color: #e68900;
            color: white;
        }
        
    </style>
<div class="container vh-100 d-flex align-items-center justify-content-center">
        <div class="row shadow-lg p-4 rounded-4">
            <div class="col-md-6 bg-light p-4 rounded-start d-flex flex-column justify-content-center">
                <h2 class="mb-3">Welcome</h2>
                <p>
                    This is the Industrial Maintenance Management system.
                    Access to schedule, manage and monitor your maintenance.
                </p>
                <p><strong>What do we offer?</strong></p>
                <ul>
                    <li>Equipment management.</li>
                    <li>Preventive maintenance scheduling.</li>
                    <li>Customized reports.</li>
                </ul>
            </div>
            <!-- Formulario de Login -->
            <div class="col-md-6 p-4">
                <h3 class="text-center mb-4">Log In</h3>
                <form method="post" action="login.php">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Username</label>
                        <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Enter your username" required>
                    </div>
                    <div class="mb-3">
                        <label for="contraseña" class="form-label">Password</label>
                        <input type="password" id="contraseña" name="contraseña" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-orange w-100">Log In</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<form action="login.php" method="POST" class="form-container">
            <label for="usuario">Username:</label>
            <input type="text" id="usuario" name="usuario" required placeholder="Enter your username">

            <label for="contraseña">Password:</label>
            <input type="password" id="contraseña" name="contraseña" required placeholder="Enter your password">

            <button type="submit">Log In</button>
</form>