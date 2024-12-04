<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Industrial Maintenance Management System</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
     <header>
        <div class="container">
            <h1><a href="index.html">Maintenance Management</a></h1>
            <nav>
                <ul>
                    <li><a href="Inicio.php"><i class="icon-home"></i>Home</a></li>
                    <li><a href="detalles.php"><i class="icon-login"></i>Details</a></li>
                    <li><a href="loggin.php"><i class="icon-register"></i>Log In</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="intro">
            <h1><i class="fas fa-cogs"></i> Discover Our Industrial Maintenance Management System</h1>
            <p><i class="fas fa-tools"></i> A comprehensive system designed to improve efficiency and coordination in industrial maintenance. It facilitates task management, equipment tracking, and communication between operators, technicians, and supervisors.</p>
            <div class="separator"></div>
            <p><i class="fas fa-briefcase"></i> On our platform, you can manage machinery maintenance, log failures, generate work orders, and consult historical reports. Each user has access to functions tailored to their role, enhancing operational efficiency and enabling informed decision-making.</p>
            <div class="separator"></div>
            <h2><i class="fas fa-question-circle"></i> How does the system work?</h2>
            <p><i class="fas fa-users"></i> The system is designed with three types of users: Supervisors, Technicians, and Operators. Each has access to different functionalities according to their needs:</p>
            <div class="user-types">
                <div class="user-type">
                    <h3><i class="fas fa-user-shield"></i> Supervisor</h3>
                    <p>The supervisor has full control over the system. They can register new users, create work orders, assign tasks to technicians and operators, and consult detailed reports on the status of machines.</p>
                </div>
                <div class="user-type">
                    <h3><i class="fas fa-user-cog"></i> Technician</h3>
                    <p>Technicians can view assigned work orders, perform preventive or corrective maintenance, and report the status of machines, ensuring that all equipment is in optimal condition.</p>
                </div>
                <div class="user-type">
                    <h3><i class="fas fa-user-tie"></i> Operator</h3>
                    <p>Operators are the first to detect failures in machines. Through the system, they can generate failure reports and alert technicians to take corrective actions quickly.</p>
                </div>
            </div>
        </section>
        
        <main>
        </section>
        <section class="gallery">
           <h2><i class="fas fa-images"></i> Explore the features of our gallery</h2>
           <div class="gallery-container">
               <a href="#img1" class="gallery-item">
                   <img src="images/imagen1.png" alt="Industrial Machine">
               </a>
               <a href="#img2" class="gallery-item">
                   <img src="images/imagen 2.png" alt="Maintenance Equipment">
               </a>
               <a href="#img3" class="gallery-item">
                   <img src="images/imagen 3.png" alt="System in Action">
               </a>
               <a href="#img4" class="gallery-item">
                   <img src="images/imagen 4.png" alt="Factory">
               </a>
           </div>
       
           <div id="img1" class="lightbox">
               <a href="#!" class="close">&times;</a>
               <img src="images/imagen1.png" alt="Industrial Machine">
           </div>
           <div id="img2" class="lightbox">
               <a href="#!" class="close">&times;</a>
               <img src="images/imagen 2.png" alt="Maintenance Equipment">
           </div>
           <div id="img3" class="lightbox">
               <a href="#!" class="close">&times;</a>
               <img src="images/imagen 3.png" alt="System in Action">
           </div>
           <div id="img4" class="lightbox">
               <a href="#!" class="close">&times;</a>
               <img src="images/imagen 4.png" alt="Factory">
           </div>
       </section>
        </main>
    </main>

</body>
</html>
