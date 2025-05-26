<?php
session_start();
include("db.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Allow only logged-in users
if (!isset($_SESSION['patient_id'])) {
    // User not logged in
    echo "<script>alert('Please log in to submit or view feedback.'); window.location.href='login.php';</script>";
    exit();
}

$patient_id = $_SESSION['patient_id'];

// Fetch only current patient's feedback
$result = $conn->query("SELECT * FROM feedback WHERE patient_id = $patient_id ORDER BY submitted_at DESC");

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and get form values
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    // Insert feedback
    $sql = "INSERT INTO feedback (patient_id, name, email, subject, message)
            VALUES ('$patient_id', '$name', '$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href = document.referrer;</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<style>
    .container {
            max-width: 900px;
            margin: auto;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .feedback-card {
            background: #ffffff;
            border-left: 5px solid #007bff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .feedback-card:hover {
            transform: scale(1.01);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.12);
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .feedback-name {
            font-weight: bold;
            font-size: 18px;
            color: #333;
        }

        .feedback-email {
            font-size: 14px;
            color: #666;
        }

        .feedback-subject {
            font-weight: 600;
            color: #007bff;
            margin-bottom: 8px;
        }

        .feedback-message {
            font-size: 15px;
            color: #444;
            line-height: 1.6;
        }

        .feedback-time {
            text-align: right;
            font-size: 13px;
            color: #888;
            margin-top: 10px;
        }

        @media (max-width: 600px) {
            .feedback-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .feedback-time {
                text-align: left;
            }
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
    background-color:rgb(178, 188, 202);
    color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

     /* Position submenus properly */
     .dropdown-submenu {
      position: relative;
    }
    .dropdown-submenu .dropdown-menu {
      top: 0;
      left: 100%;
      margin-top: -1px;
    }

/* Submenu Styling */
.dropdown-submenu .dropdown-menu {
    top: 0;
    left: 100%;
    margin-left: 0.1rem;
    margin-right: 0.1rem;
}

.dropdown-submenu:hover > .dropdown-menu {
    display: block;
}

.sub-menu {
    display: none;
    position: absolute;
    top: 0;
    left: 100%;
}

.dropdown-submenu:hover > .sub-menu {
    display: block;
}

</style>

<head>
    <meta charset="utf-8">
    <title>care - Hospital .</title>
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
                <h1 class="m-0 text-uppercase text-primary">
                    <i class="fa fa-clinic-medical me-2"></i>Care
                </h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="doctors.php" class="nav-link">Doctor</a></li>

                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Service</a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-submenu position-relative">
                                <a class="dropdown-item dropdown-toggle" href="#">Dermatologist</a>
                                <ul class="dropdown-menu sub-menu">
                                    <li><a class="dropdown-item" href="#">Dermatologist in Lahore</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu position-relative">
                                <a class="dropdown-item dropdown-toggle" href="#">Gynecologist</a>
                                <ul class="dropdown-menu sub-menu">
                                    <li><a class="dropdown-item" href="#">Gynecologist in Karachi</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu position-relative">
                                <a class="dropdown-item dropdown-toggle" href="#">Urologist</a>
                                <ul class="dropdown-menu sub-menu">
                                    <li><a class="dropdown-item" href="#">Urologist in Islamabad</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item"><a href="contact.php" class="nav-link active">Contact</a></li>
                    <li class="nav-item ms-lg-3">
                        <a href="doctorform.php" class="btn btn-primary join-doctor-btn">Join As Doctor</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
    <!-- Navbar End -->


    <!-- Contact Start -->
    <div class="container-fluid pt-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Any Questions?</h5>
                <h1 class="display-4">Please Feel Free To Contact Us</h1>
            </div>
            <div class="row g-5 mb-5">
                <div class="col-lg-4">
                    <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center text-center" style="height: 200px;">
                        <div class="d-flex align-items-center justify-content-center bg-primary rounded-circle mb-4" style="width: 100px; height: 70px; transform: rotate(-15deg);">
                            <i class="fa fa-2x fa-location-arrow text-white" style="transform: rotate(15deg);"></i>
                        </div>
                        <h6 class="mb-0">123 Street, New York, USA</h6>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center text-center" style="height: 200px;">
                        <div class="d-flex align-items-center justify-content-center bg-primary rounded-circle mb-4" style="width: 100px; height: 70px; transform: rotate(-15deg);">
                            <i class="fa fa-2x fa-phone text-white" style="transform: rotate(15deg);"></i>
                        </div>
                        <h6 class="mb-0">+012 345 6789</h6>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center text-center" style="height: 200px;">
                        <div class="d-flex align-items-center justify-content-center bg-primary rounded-circle mb-4" style="width: 100px; height: 70px; transform: rotate(-15deg);">
                            <i class="fa fa-2x fa-envelope-open text-white" style="transform: rotate(15deg);"></i>
                        </div>
                        <h6 class="mb-0">info@example.com</h6>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" style="height: 500px;">
                    <div class="position-relative h-100">
                        <iframe class="position-relative w-100 h-100"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3001156.4288297426!2d-78.01371936852176!3d42.72876761954724!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4ccc4bf0f123a5a9%3A0xddcfc6c1de189567!2sNew%20York%2C%20USA!5e0!3m2!1sen!2sbd!4v1603794290143!5m2!1sen!2sbd"
                            frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
                            tabindex="0">
                        </iframe>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center position-relative" style="margin-top: -200px; z-index: 1;">
                <div class="col-lg-8">
                    <div class="bg-white rounded p-5 m-5 mb-0">
                        <form action="" method="POST">
    <div class="row g-3">
        <div class="col-12 col-sm-6">
            <input type="text" name="name" class="form-control bg-light border-0" placeholder="Your Name" style="height: 55px;" required>
        </div>
        <div class="col-12 col-sm-6">
            <input type="email" name="email" class="form-control bg-light border-0" placeholder="Your Email" style="height: 55px;" required>
        </div>
        <div class="col-12">
            <input type="text" name="subject" class="form-control bg-light border-0" placeholder="Subject" style="height: 55px;" required>
        </div>
        <div class="col-12">
            <textarea name="message" class="form-control bg-light border-0" rows="5" placeholder="Message" required></textarea>
        </div>
        <div class="col-12">
            <button class="btn btn-primary w-100 py-3" type="submit">Send Message</button>
        </div>
    </div>
</form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact End -->
<?php


if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '
        <div class="feedback-card">
            <h4>' . htmlspecialchars($row["name"]) . ' </h4>
            <strong>Subject:</strong> ' . htmlspecialchars($row["subject"]) . '<br>
            <p>' . nl2br(htmlspecialchars($row["message"])) . '</p>
            <small>Submitted at: ' . $row["submitted_at"] . '</small>
        </div>';
    }
} else {
    echo "<p>No feedback submitted yet.</p>";
}

$conn->close();
?>


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
    <script>
  document.querySelectorAll('.dropdown-submenu .dropdown-toggle').forEach(function (el) {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      let nextEl = this.nextElementSibling;
      if (nextEl && nextEl.classList.contains('dropdown-menu')) {
        nextEl.classList.toggle('show');
      }
    });
  });
</script>
</body>

</html>