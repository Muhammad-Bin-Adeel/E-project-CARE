<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$error = '';
$success = '';

// Create appointments table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(255),
    email VARCHAR(255),
    specialization VARCHAR(255),
    doctor_id INT NOT NULL,
    appointment_date DATE,
    appointment_time TIME,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Fetch specializations
$specializations = $conn->query("SELECT DISTINCT specialization FROM doctors");

// Initial values
$specialization = $_POST['specialization'] ?? '';
$doctor_id = $_POST['doctor_id'] ?? ($_GET['doctor_id'] ?? '');
$appointment_date = $_POST['days'] ?? '';
$appointment_time = $_POST['time'] ?? '';
$name = $_POST['name'] ?? '';

// If doctor ID is passed from GET but no specialization selected, get it from DB
if (empty($specialization) && !empty($doctor_id)) {
    $stmt = $conn->prepare("SELECT specialization FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $specialization = $row['specialization'];
    }
}

// Get doctors for selected specialization
$doctors = [];
if (!empty($specialization)) {
    $stmt = $conn->prepare("SELECT id, name FROM doctors WHERE specialization = ?");
    $stmt->bind_param("s", $specialization);
    $stmt->execute();
    $doctors = $stmt->get_result();
}

// Generate available dates and times
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

        try {
            $start_time = new DateTime($parts[0]);
            $end_time = new DateTime($parts[1]);
        } catch (Exception $e) {
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

        $times = array_filter($available_times, function ($t) use ($booked_times) {
            return !in_array($t, $booked_times);
        });
    }
}

// Handle form submission
if (isset($_POST['final_submit'])) {
    if (empty($name) || empty($email) || empty($specialization) || empty($doctor_id) || empty($appointment_date) || empty($appointment_time)) {
        $error = "Please fill all required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO appointments (patient_name, email, specialization, doctor_id, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("sssiss", $name, $email, $specialization, $doctor_id, $appointment_date, $appointment_time);
        if ($stmt->execute()) {
            $success = "Appointment booked successfully.";
            $specialization = $doctor_id = $appointment_date = $appointment_time = $name = '';
        } else {
            $error = "Failed to book appointment.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">



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
    <style>
     
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
</head>

<body>
    <!-- Navbar Start -->
<?php include("nav.php"); ?>
<!-- Navbar End -->


    <!-- Appointment Section -->
<div class="container py-5">
    <div class="row">
        <div class="col-lg-6 mb-5">
            <h5 class="text-primary border-bottom border-3 d-inline-block">Appointment</h5>
            <h1 class="mb-3">Make An Appointment</h1>
            <p>Consult top doctors in your city.</p>
            <a href="doctors.php" class="btn btn-primary rounded-pill px-4">Browse Doctors</a>
        </div>
        <div class="col-lg-6">
            <div class="bg-light p-4 rounded">
                <h3 class="text-center mb-4">Book Appointment</h3>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3 text-center">
                        <label for="name">Patient Name:</label>
                        <input type="text" name="name" class="form-control w-75 mx-auto" required value="<?= htmlspecialchars($name ?? '') ?>">
                    </div>
                    <div class="mb-3 text-center">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="form-control w-75 mx-auto" readonly value="<?= htmlspecialchars($email) ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Specialization:</label>
                            <select name="specialization" class="form-select" onchange="this.form.submit()" required>
                                <option value="">Select Specialization</option>
                                <?php while ($spec = $specializations->fetch_assoc()): ?>
                                    <option value="<?= htmlspecialchars($spec['specialization']) ?>" <?= ($specialization == $spec['specialization']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($spec['specialization']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Doctor:</label>
                            <?php if (!empty($preselected_doctor_name)): ?>
                                <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>">
                                <input type="text" class="form-control" readonly value="<?= htmlspecialchars($preselected_doctor_name) ?>">
                            <?php else: ?>
                                <select name="doctor_id" class="form-select" onchange="this.form.submit()" required>
                                    <option value="">Select Doctor</option>
                                    <?php if ($doctors): ?>
                                        <?php while ($doc = $doctors->fetch_assoc()): ?>
                                            <option value="<?= $doc['id'] ?>" <?= ($doctor_id == $doc['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($doc['name']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Date:</label>
                            <select name="days" class="form-select" onchange="this.form.submit()" required>
                                <option value="">Select Date</option>
                                <?php foreach ($dates as $date): ?>
                                    <option value="<?= $date ?>" <?= ($appointment_date == $date) ? 'selected' : '' ?>>
                                        <?= $date ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
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

                    <button type="submit" name="final_submit" class="btn btn-primary">Confirm Appointment</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Footer Start -->
   <?php include("footer.php"); ?>
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