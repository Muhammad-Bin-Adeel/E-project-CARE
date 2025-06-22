<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email']; // Logged-in patient's email

// (Optional) Create table if not exists (only run once)
$conn->query("CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    specialization VARCHAR(255),
    doctor_id INT NOT NULL,
    appointment_date DATE,
    appointment_time TIME,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Fetch distinct specializations
$specializations = $conn->query("SELECT DISTINCT specialization FROM doctors");

// Input variables
$specialization = $_POST['specialization'] ?? '';
$doctor_id = $_POST['doctor_id'] ?? '';
$appointment_date = $_POST['days'] ?? '';
$appointment_time = $_POST['time'] ?? '';
$name = $_POST['name'] ?? ''; // From form input

// Fetch doctors list for selected specialization
$doctors = [];
if (!empty($specialization)) {
    $stmt = $conn->prepare("SELECT id, name FROM doctors WHERE specialization = ?");
    $stmt->bind_param("s", $specialization);
    $stmt->execute();
    $doctors = $stmt->get_result();
}

// Prepare available dates and times
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

        // Time range
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

        // Generate available dates for next 30 days
        $today = new DateTime();
        for ($i = 0; $i < 30; $i++) {
            $checkDate = clone $today;
            $checkDate->modify("+$i day");
            if (in_array($checkDate->format('l'), $days)) {
                $dates[] = $checkDate->format('Y-m-d');
            }
        }

        // Get already booked times
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

        // Generate time slots
        $interval_minutes = 15;
        $available_times = [];
        $current_time = clone $start_time;
        while ($current_time < $end_time) {
            $available_times[] = $current_time->format('H:i:s');
            $current_time->modify("+{$interval_minutes} minutes");
        }

        // Filter out booked times
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
        $stmt->bind_param(
            "sssiss",
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
        <!-- 🔹 Patient Name -->
        <div class="form-group text-center mb-4">
            <label for="name" class="form-label">Patient Name:</label>
            <input type="text" id="name" name="name" class="form-control w-50 mx-auto"
                value="<?= htmlspecialchars($name ?? '') ?>" required />
        </div>

        <!-- 🔹 Email (read-only from session) -->
        <div class="form-group text-center mb-4">
            <label for="email" class="form-label">Email Address:</label>
            <input type="email" id="email" name="email" class="form-control w-50 mx-auto"
                value="<?= htmlspecialchars($_SESSION['email']) ?>" readonly />
        </div>

        <!-- 🔹 Specialization -->
        <div class="row">
            <div class="form-group col-md-6">
                <label>Medical Concern:</label>
                <select name="specialization" class="form-select" onchange="this.form.submit()" required>
                    <option value="">Select Medical Concern</option>
                    <?php while ($spec = $specializations->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($spec['specialization']) ?>" <?= ($specialization == $spec['specialization']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($spec['specialization']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- 🔹 Doctor -->
            <div class="form-group col-md-6">
                <label>Doctor:</label>
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
            </div>
        </div>

        <!-- 🔹 Date & Time -->
        <div class="row mt-3">
            <div class="form-group col-md-6">
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

        <!-- 🔹 Submit -->
        <div class="form-group mt-4">
            <button type="submit" name="final_submit" class="btn btn-primary px-4">Make An Appointment</button>
        </div>
    </form>
</div>

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