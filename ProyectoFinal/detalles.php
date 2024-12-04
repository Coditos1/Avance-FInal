<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Mantenimiento Industrial</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="Inicio.html">Gestión de Mantenimiento</a></h1>
            <nav>
                <ul>
                    <li><a href="Inicio.php"><i class="icon-home"></i>Inicio</a></li>
                    <li><a href="loggin.php"><i class="icon-register"></i>Log In</a></li>
                </ul>
            </nav>
        </div>
    </header>


    <main>
        <section class="testimonials">
            <h2><i class="fas fa-quote-left"></i> What Our Users Say</h2>
            <div class="testimonial-container">
                <div class="testimonial">
                    <p>"The system has transformed our way of managing maintenance. Now everything is centralized and it's much easier to keep track of tasks." - <strong>Juan Pérez, Supervisor</strong></p>
                </div>
                <div class="testimonial">
                    <p>"As a technician, I can quickly access my work orders and report the status of machines efficiently. This has improved our productivity." - <strong>Ana García, Technician</strong></p>
                </div>
                <div class="testimonial">
                    <p>"As an operator, I can generate failure reports in a simple way. The system has improved communication between teams." - <strong>Carlos Ruiz, Operator</strong></p>
                </div>
            </div>
        </section>
        <section class="faq">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-item">
                <input type="checkbox" id="faq1">
                <label for="faq1">What is the Industrial Maintenance Management System?</label>
                <div class="answer">
                    <p>It is a platform designed to optimize maintenance management, improving efficiency in organization and task tracking.</p>
                </div>
            </div>
            <div class="faq-item">
                <input type="checkbox" id="faq2">
                <label for="faq2">What benefits does it offer for supervisors?</label>
                <div class="answer">
                    <p>Supervisors can register users, assign tasks, and consult detailed reports on the status of machines.</p>
                </div>
            </div>
            <div class="faq-item">
                <input type="checkbox" id="faq3">
                <label for="faq3">Is the system accessible from any device?</label>
                <div class="answer">
                    <p>Yes, the system is accessible from any device with an internet connection, making remote management easier.</p>
                </div>
            </div>
        </section>
        <section class="success-cases">
            <h2><i class="fas fa-trophy"></i> Success Stories</h2>
            <div class="case">
                <h3><i class="fas fa-industry"></i> TecnoMec Industries</h3>
                <p>
                    <strong>Location:</strong> Monterrey, Mexico<br>
                    <strong>Sector:</strong> Heavy Machinery Manufacturing<br>
                    <strong>Results:</strong> Thanks to the implementation of our system, TecnoMec achieved a 35% reduction in downtime of its critical machinery, improving its productivity and decreasing operational costs by 20%.
                </p>
            </div>
            <div class="case">
                <h3><i class="fas fa-warehouse"></i> AgroSolutions S.A.</h3>
                <p>
                    <strong>Location:</strong> Córdoba, Argentina<br>
                    <strong>Sector:</strong> Agroindustry<br>
                    <strong>Results:</strong> AgroSolutions used our system to optimize its preventive maintenance program, avoiding failures during harvest season. This translated into a 15% increase in operational efficiency.
                </p>
            </div>
            <div class="case">
                <h3><i class="fas fa-oil-can"></i> PetroQuim Leader</h3>
                <p>
                    <strong>Location:</strong> Bogotá, Colombia<br>
                    <strong>Sector:</strong> Energy and Petroleum<br>
                    <strong>Results:</strong> PetroQuim implemented our system to centralize maintenance management in three plants. Real-time tracking allowed for an annual savings of $200,000 in corrective repairs.
                </p>
            </div>
        </section>
         <section class="gallery">
            <h2><i class="fas fa-images"></i> Our Gallery</h2>
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

</body>
</html>
