<?php
session_start();
include("db.php");

// Get all specialists (specialization column)
$specialists_result = $conn->query("SELECT DISTINCT specialization FROM doctors ORDER BY specialization");

// Get all cities (saari cities)
$cities_result = $conn->query("SELECT DISTINCT city FROM doctors ORDER BY city");

// Selected values agar form submit ho chuka ho
$selected_specialist = $_POST['specialist'] ?? '';
$selected_city = $_POST['city'] ?? '';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$selected_specialist || !$selected_city) {
        $error = "Please select both Specialist and City.";
    } else {
        // Dono select hain, redirect kar do doctors.php with params
        header("Location: doctors.php?specialist=" . urlencode($selected_specialist) . "&city=" . urlencode($selected_city));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

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

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
 .container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

.card-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.card {
    flex: 1 1 calc(33.333% - 20px);
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.card-body {
    padding: 15px;
    flex-grow: 1;
}

.card-title {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 10px;
}

.card-text {
    font-size: 14px;
    color: #555;
    margin-bottom: 15px;
}

.card-footer {
    padding: 15px;
    background-color: #f9f9f9;
    font-size: 13px;
    color: #888;
    border-top: 1px solid #eee;
}

.card-footer .btn {
    margin-right: 5px;
    padding: 5px 10px;
    font-size: 13px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-warning {
    background-color: #ffc107;
    color: #000;
}

.btn-danger {
    background-color: #dc3545;
    color: #fff;
}
 .dropdown-submenu {
  position: relative;
}

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
          <a href="index.php" class="nav-item nav-link ">Home</a>
          <a href="about.php" class="nav-item nav-link">About</a>
          <a href="doctors.php" class="nav-item nav-link">Doctors</a>
          <a href="appointment.php" class="nav-item nav-link">Appoiontment</a>

          <div class="nav-item dropdown">
            <a href="#" class="nav-link active dropdown-toggle" data-bs-toggle="dropdown">Medical Info</a>
            <div class="dropdown-menu m-0">
              <a href="blog.php" class="dropdown-item">Medical News</a>
              <a href="Disease.php" class="dropdown-item active">Diseas Info</a>
    
            </div>
          </div>

          <a href="contact.php" class="nav-item nav-link">Contact</a>
        </div>


      </div>
    </nav>
  </div>
</div>
<!-- Navbar End -->


   <!-- main content start-->
<div class="container mt-4">
    <h2 class="mb-4">Disease Records</h2>
    <div class="row">
        <?php
        include 'db.php'; // your DB connection
        $result = $conn->query("SELECT * FROM diseases ORDER BY reviewed_date DESC");
        while ($row = $result->fetch_assoc()):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <?php if ($row['image']): ?>
                    <img src="<?= $row['image'] ?>" class="card-img-top" alt="Disease Image">
                <?php else: ?>
                    <img src="no-image.jpg" class="card-img-top" alt="No Image">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= $row['name'] ?></h5>
                    <p class="card-text"><?= $row['description'] ?></p>
                </div>
                <div class="card-footer text-muted">
                    Reviewed: <?= date('d M Y, h:i A', strtotime($row['reviewed_date'])) ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>


    <!--main content end-->
    


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