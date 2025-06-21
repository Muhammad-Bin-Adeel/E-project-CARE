<?php
session_start();
include("db.php");

// Check if email is set in session
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];

// Now query using email instead of patient_id
$sql = "SELECT a.*, d.name AS doctor_name 
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.id
        WHERE a.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email); // 's' = string type
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <title>CARE</title>
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
   <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>

    <!-- Template Stylesheet -->
     <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link href="css/style.css" rel="stylesheet">
    <title>My Appointments</title>
    
    <style>

.dropdown-submenu .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -1px;
}

.dropdown-menu > .dropdown-submenu > .dropdown-toggle::after {
  content: " \25B8";
  float: right;
}
        .join-doctor-btn {
    padding: 8px 20px;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    border-radius: 30px;
    background-color: #0d6efd; /* Bootstrap primary */
    color: white;
    transition: all 0.3s ease;
}

.join-doctor-btn:hover {
    background-color: #0b5ed7;
    color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}
        body {
            background-color: #EFF5F9;
            font-family: Arial, sans-serif;
        }
        h2 {
            color: #354F8E;
        }
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
        .badge-secondary { background-color: #6c757d; }
        .badge-warning { background-color: #ffc107; color: #000; }
    </style>
</head>
<body>
    
<!-- Navbar Start -->
<div class="container-fluid sticky-top bg-white shadow-sm">
  <div class="container">
    <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0">
      <a href="index.php" class="navbar-brand">
        <h1 class="m-0 text-uppercase text-primary">
          <i class="fa fa-clinic-medical me-2"></i>CARE
        </h1>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0">
          <a href="index.php" class="nav-item nav-link active">Home</a>
          <a href="about.php" class="nav-item nav-link">About</a>
          <a href="doctors.php" class="nav-item nav-link">Doctors</a>
          <a href="appointment.php" class="nav-item nav-link">Appoiontment</a>

          <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Medical Info</a>
            <div class="dropdown-menu m-0">
              <a href="blog.php" class="dropdown-item">Medical News</a>
              <a href="Disease.php" class="dropdown-item">Diseas Info</a>
    
            </div>
          </div>

          <a href="contact.php" class="nav-item nav-link">Contact</a>
        </div>

        <!-- Buttons -->
        <div class="ms-3">
          <a href="doctorform.php" class="btn btn-primary join-doctor-btn">Join As Doctor</a>
        </div>

        <?php if (isset($_SESSION['patient_id'])): ?>
          <div class="button-container ms-2">
            
        
        <?php else: ?>
          <div class="button-container ms-2">
            <a href="login.php" class="btn btn-outline-secondary btn-sm">Login</a>
          </div>
        <?php endif; ?>

      </div>
    </nav>
  </div>
</div>
<!-- Navbar End -->

<div class="container mt-5">
    <h2 class="mb-4">My Appointments</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered bg-white">
            <thead class="thead-primary">
                <tr>
                    <th>Patient_Name</th>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Reciept</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>

                    <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($row['specialization']) ?></td>
                    <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                    <td>
                        <?php
                        $status = strtolower($row['status']);
                        if ($status === 'approved' || $status === 'accepted') {
                            echo '<span class="badge badge-success">Approved</span>';
                        } elseif ($status === 'declined' || $status === 'rejected') {
                            echo '<span class="badge badge-danger">Declined</span>';
                        } elseif ($status === 'pending') {
                            echo '<span class="badge badge-warning">Pending</span>';
                        } else {
                            echo '<span class="badge badge-secondary">Unknown</span>';
                        }
                        ?>
                    </td>
                    <td>
                    <?php if ($status === 'approved' || $status === 'accepted'): ?>
    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#receiptModal"
    data-receipt-id="<?= $row['id'] ?>"
    data-patient-name="<?= htmlspecialchars($row['patient_name']) ?>"
    data-doctor-name="<?= htmlspecialchars($row['doctor_name']) ?>"
    data-specialization="<?= htmlspecialchars($row['specialization']) ?>"
    data-appointment-date="<?= htmlspecialchars($row['appointment_date']) ?>"
    data-appointment-time="<?= htmlspecialchars($row['appointment_time']) ?>">
    View Receipt
</button>

<?php else: ?>
    <span class="text-muted">N/A</span>
<?php endif; ?>

                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No appointments found.</div>
    <?php endif; ?>
</div>
<!-- Add this modal to the bottom of your HTML -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content position-relative p-3" id="receiptContent">
       <div style="
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      opacity: 0.05;
      z-index: 0;
      pointer-events: none;
  ">
      <h1 class="m-0 text-uppercase text-primary">
          <i class="fa fa-clinic-medical me-2"></i>CARE
        </h1>
  </div>
      <!-- Watermark -->
      <div style="position: absolute; bottom: 10px; right: 10px; font-size: 10px; color: rgba(0,0,0,0.15); transform: rotate(-30deg); pointer-events: none;">
        Generated by Care.com
      </div>

      <div class="modal-header d-block text-center border-0">
        <h1 class="m-0 text-uppercase text-primary">
          <i class="fa fa-clinic-medical me-2"></i>CARE
        </h1>
      </div>

      <div class="modal-body">
        <p><strong>Receipt ID:</strong> <span id="modalReceiptId"></span></p>
        <p><strong>Patient Name:</strong> <span id="modalPatient"></span></p>
        <p><strong>Doctor Name:</strong> <span id="modalDoctor"></span></p>
        <p><strong>Specialization:</strong> <span id="modalSpecialization"></span></p>
        <p><strong>Date:</strong> <span id="modalDate"></span></p>
        <p><strong>Time:</strong> <span id="modalTime"></span></p>
        <p><strong>Status:</strong> <span id="modalStatus">Approved</span></p>
        <p><strong>Generated On:</strong> <span id="modalGenerated"></span></p>
      </div>

      <div class="modal-footer">
        <button id="saveReceiptBtn" class="btn btn-success">Save to Gallery</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <!-- Contact  -->

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
                        <a class="text-light" href="doctorform.php"><i class="fa fa-angle-right me-2"></i>Join As Doctor </a>
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
<!-- Swiper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const receiptModal = document.getElementById('receiptModal');

    receiptModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        // Fill receipt data
        document.getElementById('modalReceiptId').textContent = button.getAttribute('data-receipt-id');
        document.getElementById('modalPatient').textContent = button.getAttribute('data-patient-name');
        document.getElementById('modalDoctor').textContent = button.getAttribute('data-doctor-name');
        document.getElementById('modalSpecialization').textContent = button.getAttribute('data-specialization');
        document.getElementById('modalDate').textContent = button.getAttribute('data-appointment-date');
        document.getElementById('modalTime').textContent = button.getAttribute('data-appointment-time');

        // Set current time
        const now = new Date().toLocaleString();
        document.getElementById('modalGenerated').textContent = now;
    });

    // Save modal content as image
    document.getElementById('saveReceiptBtn').addEventListener('click', function () {
        const receiptContent = document.getElementById("receiptContent");
        html2canvas(receiptContent).then(function (canvas) {
            const link = document.createElement("a");
            link.download = "receipt_" + Date.now() + ".png";
            link.href = canvas.toDataURL();
            link.click();
        });
    });
});
</script>
</body>
</html>
