<?php
session_start();
include("db.php");

// Add new columns if not already added
$conn->query("ALTER TABLE doctors ADD COLUMN IF NOT EXISTS location VARCHAR(255)");
$conn->query("ALTER TABLE doctors ADD COLUMN IF NOT EXISTS address TEXT");
$conn->query("ALTER TABLE doctors ADD COLUMN IF NOT EXISTS degree VARCHAR(255)");

// Create table if not exists (with address and degree)
$conn->query("CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    hospital_name VARCHAR(150),
    phone VARCHAR(20),
    specialization VARCHAR(100),
    city VARCHAR(100),
    days VARCHAR(100),
    timing VARCHAR(100),
    experience VARCHAR(100),
    description TEXT,
    image VARCHAR(255),
    location VARCHAR(255),
    address TEXT,
    degree VARCHAR(255),
    status ENUM('pending','approved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");


// Fetch all
$doctors = $conn->query("SELECT * FROM doctors ORDER BY status DESC, id DESC");
?>

<!-- Messages -->
<?php if (isset($_SESSION['message'])): ?>
<div class="alert alert-success">
    <?= $_SESSION['message'] ?>
    <?php unset($_SESSION['message']); ?>
</div>
<?php endif; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MEDINOVA - Hospital Website </title>
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
    /* Form Grid Layout */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

/* Full Width Fields (like Description, Location etc.) */
.form-group.full-width {
    grid-column: 1 / -1;
}

/* Form Labels */
.form-label {
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

/* Inputs & Textareas */
.form-control {
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 5px rgba(13, 110, 253, 0.3);
}

/* Buttons */
.form-actions .btn-primary {
    padding: 10px 30px;
    border-radius: 30px;
    font-weight: 600;
    background: linear-gradient(90deg, #0d6efd, #0b5ed7);
    border: none;
}

.form-actions .btn-primary:hover {
    background: linear-gradient(90deg, #0b5ed7, #0a58ca);
}

/* Card Styling */
.card {
    border-radius: 12px;
    border: none;
}

/* Heading Icon */
h4.text-primary i {
    color: #0d6efd;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
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
                    <h1 class="m-0 text-uppercase text-primary"><i class="fa fa-clinic-medical me-2"></i>Medinova</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="index.php" class="nav-item nav-link ">Home</a>
                        <a href="about.php" class="nav-item nav-link">About</a>
                        <a href="service.php" class="nav-item nav-link">Service</a>
                        <a href="doctors.php" class="nav-item nav-link active">Doctor</a>
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
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->
 <!-- Content Area -->
 <div class="content-wrapper">
  <div class="row">
    <div class="col-md-12">
      <div class="card shadow p-4">
        <h4 class="mb-4 text-primary">
          <i class="fas fa-user-md me-2"></i> <?= isset($edit) ? 'Edit Doctor' : 'Add New Doctor' ?>
        </h4>

        <form action="thanks.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

          <div class="form-grid">
    <div class="form-group">
      <label class="form-label">Name:</label>
      <input type="text" name="name" class="form-control" value="<?= $edit['name'] ?? '' ?>" required>
    </div>

    <div class="form-group">
      <label class="form-label">Hospital Name:</label>
      <input type="text" name="hospital_name" class="form-control" value="<?= $edit['hospital_name'] ?? '' ?>" required>
    </div>

    <div class="form-group">
      <label class="form-label">Phone:</label>
      <input type="text" name="phone" class="form-control" value="<?= $edit['phone'] ?? '' ?>" required>
    </div>

    <div class="form-group">
      <label class="form-label">Specialization:</label>
      <input type="text" name="specialization" class="form-control" value="<?= $edit['specialization'] ?? '' ?>" required>
    </div>

        <div class="form-group">
    <label for="degree">Degree</label>
    <select name="degree" id="degree" class="form-control" required onchange="toggleOtherDegree(this)">
        <option value="">-- Select Degree --</option>
        <option value="MBBS" <?= isset($edit['degree']) && $edit['degree'] == 'MBBS' ? 'selected' : '' ?>>MBBS</option>
        <option value="MD" <?= isset($edit['degree']) && $edit['degree'] == 'MD' ? 'selected' : '' ?>>MD (Doctor of Medicine)</option>
        <option value="MS" <?= isset($edit['degree']) && $edit['degree'] == 'MS' ? 'selected' : '' ?>>MS (Master of Surgery)</option>
        <option value="BDS" <?= isset($edit['degree']) && $edit['degree'] == 'BDS' ? 'selected' : '' ?>>BDS (Dental)</option>
        <option value="MDS" <?= isset($edit['degree']) && $edit['degree'] == 'MDS' ? 'selected' : '' ?>>MDS (Dental Surgery)</option>
        <option value="BHMS" <?= isset($edit['degree']) && $edit['degree'] == 'BHMS' ? 'selected' : '' ?>>BHMS (Homeopathy)</option>
        <option value="BAMS" <?= isset($edit['degree']) && $edit['degree'] == 'BAMS' ? 'selected' : '' ?>>BAMS (Ayurveda)</option>
        <option value="DNB" <?= isset($edit['degree']) && $edit['degree'] == 'DNB' ? 'selected' : '' ?>>DNB</option>
        <option value="PhD" <?= isset($edit['degree']) && $edit['degree'] == 'PhD' ? 'selected' : '' ?>>PhD</option>
        <option value="Other" <?= isset($edit['degree']) && !in_array($edit['degree'], ['MBBS','MD','MS','BDS','MDS','BHMS','BAMS','DNB','PhD']) ? 'selected' : '' ?>>Other</option>
    </select>
</div>

<div class="form-group" id="other-degree-group" style="display: none;">
    <label for="other_degree">Please specify</label>
    <input type="text" name="other_degree" id="other_degree" class="form-control"
           value="<?= (!in_array($edit['degree'] ?? '', ['MBBS','MD','MS','BDS','MDS','BHMS','BAMS','DNB','PhD']) && isset($edit['degree'])) ? $edit['degree'] : '' ?>">
</div>
    
    <div class="form-group">
      <label class="form-label">City:</label>
      <input type="text" name="city" class="form-control" value="<?= $edit['city'] ?? '' ?>" required>
    </div>
    

    <div class="form-group">
      <label class="form-label">Days:</label>
      <input type="text" name="days" class="form-control" value="<?= $edit['days'] ?? '' ?>" required>
    </div>

    <div class="form-group">
      <label class="form-label">Timing:</label>
      <input type="text" name="timing" class="form-control" value="<?= $edit['timing'] ?? '' ?>" required>
    </div>

    <div class="form-group">
      <label class="form-label">Experience:</label>
      <input type="text" name="experience" class="form-control" value="<?= $edit['experience'] ?? '' ?>" required>
    </div>

    <div class="form-group full-width">
      <label class="form-label">Description:</label>
      <textarea name="description" class="form-control"><?= $edit['description'] ?? '' ?></textarea>
    </div>

    <div class="form-group">
    <label for="address">Full Address</label>
    <textarea name="address" id="address" class="form-control"><?= $edit['address'] ?? '' ?></textarea>
</div>

   
    <div class="form-group full-width">
              <label class="form-label">Location (Google Maps)</label>
              <input type="text" name="location" class="form-control" value="<?= $edit['location'] ?? '' ?>" required>
            </div>

            <div class="form-group full-width">
              <label class="form-label">Image</label>
              <input type="file" name="image" class="form-control">
            </div>
          </div>

          <div class="form-actions text-end mt-4">
            <button action="thanks.php" type="submit" class="btn btn-primary px-4">Save Doctor</button>
          </div>
        </form>
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
