<?php
session_start();
include("db.php");

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

// Create appointments table with status column if not exists (optional)
$conn->query("CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    name VARCHAR(255),
    email VARCHAR(255),
    specialization VARCHAR(255),
    doctor_id INT NOT NULL,
    appointment_date DATE,
    appointment_time TIME,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Fetch distinct specializations from doctors
$specializations = $conn->query("SELECT DISTINCT specialization FROM doctors");

// Initialize variables for form input
$specialization = $_POST['specialization'] ?? '';
$doctor_id = $_POST['doctor_id'] ?? '';
$appointment_date = $_POST['days'] ?? '';
$appointment_time = $_POST['time'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';

// Fetch doctors list based on selected specialization
$doctors = [];
if (!empty($specialization)) {
    $stmt = $conn->prepare("SELECT id, name FROM doctors WHERE specialization = ?");
    $stmt->bind_param("s", $specialization);
    $stmt->execute();
    $doctors = $stmt->get_result();
}

// Prepare available dates and times arrays
$dates = [];
$times = [];

if (!empty($doctor_id)) {
    $stmt = $conn->prepare("SELECT days, timing FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $days = array_map('trim', explode(",", $row['days']));
        $time_str = strtolower(trim($row['timing']));
        $parts = preg_split('/\s*to\s*/', $time_str);

        if (count($parts) == 2) {
            try {
                $start_time = new DateTime($parts[0]);
                $end_time = new DateTime($parts[1]);
            } catch (Exception $e) {
                $start_time = new DateTime('09:00');
                $end_time = new DateTime('11:00');
            }
        } else {
            $start_time = new DateTime('09:00');
            $end_time = new DateTime('11:00');
        }

        $today = new DateTime();
        for ($i = 0; $i < 30; $i++) {
            $checkDate = clone $today;
            $checkDate->modify("+$i day");
            if (in_array($checkDate->format('l'), $days)) {
                $dates[] = $checkDate->format('Y-m-d');
            }
        }

        // Get already booked times for this doctor and date
        $booked_times = [];
        if (!empty($appointment_date)) {
            $stmt2 = $conn->prepare("SELECT appointment_time FROM appointments WHERE doctor_id = ? AND appointment_date = ?");
            $stmt2->bind_param("is", $doctor_id, $appointment_date);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            while ($r = $result2->fetch_assoc()) {
                $booked_times[] = $r['appointment_time'];
            }
        }

        $interval_minutes = 15;
        $available_times = [];
        $current_time = clone $start_time;
        while ($current_time < $end_time) {
            $available_times[] = $current_time->format('H:i:s');
            $current_time->modify("+{$interval_minutes} minutes");
        }

        // Filter out booked times
        $times = array_filter($available_times, function($t) use ($booked_times) {
            return !in_array($t, $booked_times);
        });
    }
}

// Handle final appointment submission
if (isset($_POST['final_submit'])) {
    if (empty($name) || empty($email) || empty($specialization) || empty($doctor_id) || empty($appointment_date) || empty($appointment_time)) {
        $error = "Please fill all required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO appointments (patient_id, name, email, specialization, doctor_id, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param(
            "isssiss",
            $patient_id,
            $name,
            $email,
            $specialization,
            $doctor_id,
            $appointment_date,
            $appointment_time
        );
        if ($stmt->execute()) {
            $success = "Appointment booked successfully and is pending approval.";
            // Clear form values after successful booking
            $specialization = $doctor_id = $appointment_date = $appointment_time = $name = $email = '';
        } else {
            $error = "Failed to book appointment.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<style>
     /* Position submenus properly */
     .dropdown-submenu {
      position: relative;
    }
    .dropdown-submenu .dropdown-menu {
      top: 0;
      left: 100%;
      margin-top: -1px;
    }
 /* General Input Styling */
form input[type="text"],
form input[type="email"],
form select {
    color: #1D2A4D; /* Dark readable text */
    font-size: 16px;
    font-weight: 500;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 10px 14px;
    transition: all 0.3s ease;
    background-color: #fff;
}

/* On Focus - Add subtle glow */
form input[type="text"]:focus,
form input[type="email"]:focus,
form select:focus {
    border-color: #13C5DD;
    box-shadow: 0 0 8px rgba(19, 197, 221, 0.3);
    outline: none;
    background-color: #EFF5F9;
}

/* Label Styling */
form label {
    font-weight: 600;
    color: #354F8E;
    margin-bottom: 6px;
    display: block;
}

/* Center Patient ID Text */
#patient_id {
    text-align: center;
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Button Styling */
form button[type="submit"] {
    background-color: #13C5DD;
    border: none;
    color: #fff;
    font-weight: bold;
    font-size: 16px;
    padding: 12px 20px;
    border-radius: 8px;
    width: 100%;
    margin-top: 15px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

form button[type="submit"]:hover {
    background-color: #0fb2c5;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(19, 197, 221, 0.4);
}


</style>

<head>
    <meta charset="utf-8">
    <title>care - Hospital </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">  

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid py-2 border-bottom d-none d-lg-block">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-lg-start mb-2 mb-lg-0">
                    <div class="d-inline-flex align-items-center">
                        <a class="text-decoration-none text-body pe-3" href=""><i class="bi bi-telephone me-2"></i>+012 345 6789</a>
                        <span class="text-body">|</span>
                        <a class="text-decoration-none text-body px-3" href=""><i class="bi bi-envelope me-2"></i>info@example.com</a>
                    </div>
                </div>
                <div class="col-md-6 text-center text-lg-end">
                    <div class="d-inline-flex align-items-center">
                        <a class="text-body px-2" href="">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="text-body px-2" href="">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a class="text-body px-2" href="">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a class="text-body px-2" href="">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a class="text-body ps-2" href="">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

<!-- Navbar Start -->
<div class="container-fluid sticky-top bg-white shadow-sm mb-5">
        <div class="container">
            <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0">
                <a href="index.php" class="navbar-brand">
                    <h1 class="m-0 text-uppercase text-primary"><i class="fa fa-clinic-medical me-2"></i>care</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="index.php" class="nav-item nav-link">Home</a>
                        <a href="about.php" class="nav-item nav-link">About</a>
                        <a href="doctors.php" class="nav-item nav-link">Doctor</a>
                   

                       <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Service</a>
                            <!-- <a class="dropdown-item dropdown-toggle" href="#">Find doctor by speciality</a> -->
                            <div class="dropdown-menu m-0">

                                 <li class="dropdown-submenu">
                             <a class="dropdown-item dropdown-toggle" href="#">Dermatologist</a>
                             <ul class="dropdown-menu">
                             <a class="dropdown-item dropdown-toggle" href="#">Dermatologist in lahore </a>
                             </ul>
                             </li>

                                  <li class="dropdown-submenu">
                             <a class="dropdown-item dropdown-toggle" href="#">Gynecologistt</a>
                             <ul class="dropdown-menu">
                             <li><a class="dropdown-item" href="#">Gynecologist in karachi </a></li>
                             </ul>
                             </li>

                                <li class="dropdown-submenu">
                             <a class="dropdown-item dropdown-toggle" href="#">Urologist</a>
                             <ul class="dropdown-menu">
                             <li><a class="dropdown-item" href="#">Urologistin Islamabad </a></li>
                             </ul>
                             </li>

                            </div>
                        </div>
                        <a href="contact.php" class="nav-item nav-link">Contact</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->


    <!-- Appointment Section -->
<div class="container-fluid py-5">
  <div class="container">
    <div class="row gx-5">
      <div class="col-lg-6 mb-5 mb-lg-0">
        <div class="mb-4">
          <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Appointment</h5>
          <h1 class="display-4">Make An Appointment For Your Family</h1>
        </div>
        <p class="mb-5">Book a consultation with our specialists.</p>
        <a href="doctors.php" class="btn btn-primary rounded-pill py-3 px-5 me-3">Find Doctor</a>
      </div>
     <div class="col-lg-6">
    <div class="bg-light text-center rounded p-5">
        <h1 class="mb-4">Book An Appointment</h1>

        <?php if (!empty($success)): ?>
    <div class="alert alert-success">Appointment booked successfully!</div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

        <form method="POST" action="">
    <div class="form-group text-center mb-4">
        <label for="patient_id" class="form-label">Patient ID:</label>
        <input type="text" id="patient_id" name="patient_id" class="form-control w-50 mx-auto"
            value="<?= htmlspecialchars($patient_id) ?>" readonly />
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" class="form-control" required />
        </div>
        <div class="form-group col-md-6">
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" class="form-control" required />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Medical Concern:</label>
            <select name="specialization" class="form-select" onchange="this.form.submit()">
                <option value="">Select Medical Concern</option>
                <?php while ($spec = $specializations->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($spec['specialization']) ?>" <?= ($specialization == $spec['specialization']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($spec['specialization']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>Doctor:</label>
            <select name="doctor_id" class="form-select" onchange="this.form.submit()">
                <option value="">Select Doctor</option>
                <?php if ($doctors): ?>
                    <?php while ($doc = $doctors->fetch_assoc()): ?>
                        <option value="<?= $doc['id'] ?>" <?= ($doctor_id == $doc['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($doc['name']) ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Date:</label>
            <select name="days" class="form-select" onchange="this.form.submit()">
                <option value="">Select Date</option>
                <?php foreach ($dates as $date): ?>
                    <option value="<?= $date ?>" <?= ($appointment_date == $date) ? 'selected' : '' ?>>
                        <?= $date ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>Time:</label>
            <select name="time" class="form-select" required>
                <option value="">Select Time</option>
                <?php foreach ($times as $time): ?>
                    <option value="<?= $time ?>" <?= ($appointment_time == $time) ? 'selected' : '' ?>>
                        <?= date('h:i A', strtotime($time)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" name="final_submit">Make An Appointment</button>
    </div>
</form>
    </div>
</div>
    </div>
  </div>
</div>


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light mt-5 py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="d-inline-block text-primary text-uppercase border-bottom border-5 border-secondary mb-4">Get In Touch</h4>
                    <p class="mb-4">No dolore ipsum accusam no lorem. Invidunt sed clita kasd clita et et dolor sed dolor</p>
                    <p class="mb-2"><i class="fa fa-map-marker-alt text-primary me-3"></i>123 Street, New York, USA</p>
                    <p class="mb-2"><i class="fa fa-envelope text-primary me-3"></i>info@example.com</p>
                    <p class="mb-0"><i class="fa fa-phone-alt text-primary me-3"></i>+012 345 67890</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="d-inline-block text-primary text-uppercase border-bottom border-5 border-secondary mb-4">Quick Links</h4>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-light mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Home</a>
                        <a class="text-light mb-2" href="#"><i class="fa fa-angle-right me-2"></i>About Us</a>
                        <a class="text-light mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Our Services</a>
                        <a class="text-light mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Meet The Team</a>
                        <a class="text-light mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Latest Blog</a>
                        <a class="text-light" href="#"><i class="fa fa-angle-right me-2"></i>Contact Us</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="d-inline-block text-primary text-uppercase border-bottom border-5 border-secondary mb-4">Popular Links</h4>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-light mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Home</a>
                        <a class="text-light mb-2" href="#"><i class="fa fa-angle-right me-2"></i>About Us</a>
                        <a class="text-light mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Our Services</a>
                        <a class="text-light mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Meet The Team</a>
                        <a class="text-light mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Latest Blog</a>
                        <a class="text-light" href="#"><i class="fa fa-angle-right me-2"></i>Contact Us</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="d-inline-block text-primary text-uppercase border-bottom border-5 border-secondary mb-4">Newsletter</h4>
                    <form action="">
                        <div class="input-group">
                            <input type="text" class="form-control p-3 border-0" placeholder="Your Email Address">
                            <button class="btn btn-primary">Sign Up</button>
                        </div>
                    </form>
                    <h6 class="text-primary text-uppercase mt-4 mb-3">Follow Us</h6>
                    <div class="d-flex">
                        <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a class="btn btn-lg btn-primary btn-lg-square rounded-circle" href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-dark text-light border-top border-secondary py-4">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-md-0">&copy; <a class="text-primary" href="#">Your Site Name</a>. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Designed by <a class="text-primary" href="https://htmlcodex.com">HTML Codex</a></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    
</body>

</html>