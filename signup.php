<?php
session_start();
include("db.php");

 $conn->query("CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    gender ENUM('Male', 'Female', 'Other'),
    age INT,
    address TEXT,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
// Form submit handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $conn->real_escape_string($_POST["name"]);
    $email    = $conn->real_escape_string($_POST["email"]);
    $phone    = $conn->real_escape_string($_POST["phone"]);
    $gender   = $conn->real_escape_string($_POST["gender"]);
    $age      = intval($_POST["age"]);
    $address  = $conn->real_escape_string($_POST["address"]);
    $password = $conn->real_escape_string($_POST["password"]);

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = "Name, Email & Password are required.";
    } else {
        // Email already exists?
        $check = $conn->query("SELECT id FROM patients WHERE email='$email'");
        if ($check->num_rows > 0) {
            $error = "Email already registered.";
        } else {
        $conn->query("INSERT INTO patients (name, email, phone, gender, age, address, password) 
              VALUES ('$name', '$email', '$phone', '$gender', $age, '$address', '$password')");
             $_SESSION["success"] = "Signup successful! You can now log in.";
             header("Location: login.php");
             exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MEDINOVA</title>
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
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes bounceHover {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-3px);
      }
    }

    .signup-container {
      max-width: 520px;
      margin: 60px auto;
      padding: 40px;
      background-color: #ffffff;
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.08);
      text-align: center;
      animation: fadeInUp 0.8s ease-in-out;
    }

    .form-title {
      font-size: 30px;
      color: #354F8E;
      margin-bottom: 25px;
      font-weight: bold;
    }

    .form-message {
      background-color: #f8d7da;
      color: #842029;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      border: 1px solid #f5c2c7;
    }

    .signup-form {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .form-group,
    .form-row {
      width: 100%;
      margin-bottom: 20px;
      text-align: left;
    }

    .signup-form label {
      display: block;
      margin-bottom: 6px;
      color: #1D2A4D;
      font-weight: 600;
    }

    .signup-form input,
    .signup-form select,
    .signup-form textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 15px;
      background-color: #f9f9f9;
      box-sizing: border-box;
      transition: all 0.3s ease;
    }

    .signup-form input:focus,
    .signup-form select:focus,
    .signup-form textarea:focus {
      outline: none;
      border-color: #13C5DD;
      box-shadow: 0 0 5px rgba(19, 197, 221, 0.4);
    }

    .signup-form textarea {
      resize: vertical;
    }

    .btn-submit {
      background-color: #13C5DD;
      color: white;
      padding: 14px;
      width: 100%;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      margin-top: 10px;
      transition: background-color 0.3s ease;
    }

    .btn-submit:hover {
      background-color: #0fb3cb;
      animation: bounceHover 0.4s;
    }

    /* Two-column layout for gender and age */
    .form-row {
      display: flex;
      gap: 20px;
    }

    .form-col {
      flex: 1;
    }

</style>
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
<div class="container-fluid sticky-top bg-white shadow-sm">
  <div class="container">
    <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0">
      <a href="index.php" class="navbar-brand">
        <h1 class="m-0 text-uppercase text-primary">
          <i class="fa fa-clinic-medical me-2"></i>Medinova
        </h1>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0">
          <a href="index.php" class="nav-item nav-link active ">Home</a>
          <a href="about.php" class="nav-item nav-link">About</a>
          <a href="doctors.php" class="nav-item nav-link">Doctors</a>
           <a href="Disease.php" class="nav-item nav-link ">Disease</a>

          

          <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
            <div class="dropdown-menu m-0">
              <a href="blog.php" class="dropdown-item">Blog Grid</a>
              <a href="#" class="dropdown-item">Blog Detail</a>
              <a href="#" class="dropdown-item">The Team</a>
              <a href="#" class="dropdown-item">Testimonial</a>
              <a href="appointment.php" class="dropdown-item">Appointment</a>
              <a href="search.php" class="dropdown-item">Search</a>
            </div>
          </div>

          <a href="contact.php" class="nav-item nav-link">Contact</a>
        </div>

        <!-- Buttons -->
        <div class="ms-3">
          <a href="doctorform.php" class="btn btn-primary join-doctor-btn">Join As Doctor</a>
        </div>
        <div class="button-container ms-2">
          <a href="login.php" class="btn btn-outline-secondary btn-sm">Login</a>
        </div>
      </div>
    </nav>
  </div>
</div>
<!-- Navbar End -->

<div class="signup-container">
    <h2 class="form-title">Signup</h2>

    <?php if (isset($error)): ?>
        <div class="form-message"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="" class="signup-form">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" required />
      </div>

      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" required />
      </div>

      <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" />
      </div>

      <div class="form-row">
        <div class="form-col">
          <label>Gender</label>
          <select name="gender">
            <option value="">-- Select --</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
          </select>
        </div>
        <div class="form-col">
          <label>Age</label>
          <input type="number" name="age" min="0" />
        </div>
      </div>

      <div class="form-group">
        <label>Address</label>
        <textarea name="address"></textarea>
      </div>

      <div class="form-group">
        <label>Create Password</label>
        <input type="password" name="password" required />
      </div>

      <button type="submit" class="btn-submit">Signup</button>
    </form>
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

    
</body>

</html>
