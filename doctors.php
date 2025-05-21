<?php
session_start();
include("db.php");



// Fetch only approved doctors
$result = $conn->query("SELECT * FROM doctors WHERE status = 'approved' ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MEDINOVA - Hospital Website Template</title>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


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
                    <a href="index.php" class="nav-item nav-link ">Home</a>
                    <a href="about.php" class="nav-item nav-link">About</a>
                   
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
                <!-- Corrected Button -->
                <div class="ms-3">
                    <a href="doctorform.php" class="btn btn-primary join-doctor-btn">Join As Doctor</a>
                </div>
            </div>
        </nav>
    </div>
</div>
    <!-- Navbar End -->
     
     
    <!-- Team Start -->
    <div class="container-fluid py-5">
    <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Our Doctors</h5>
                <h1 class="display-4">Qualified Healthcare Professionals</h1>
            </div>

            <div class="row g-4">
                <?php $counter = 1; while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm h-100">
                            <img src="<?= htmlspecialchars($row['image']) ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="<?= htmlspecialchars($row['name']) ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                                <h6 class="text-primary"><?= htmlspecialchars($row['specialization']) ?></h6>
                                <p><strong>City:</strong> <?= htmlspecialchars($row['city']) ?></p>
                                <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
                                <p><strong>Experience:</strong> <?= htmlspecialchars($row['experience']) ?></p>

                                <div class="collapse" id="doctorDetails<?= $counter ?>">
                                    <p><strong>Hospital:</strong> <?= htmlspecialchars($row['hospital_name']) ?></p>
                                    <p><strong>Days:</strong> <?= htmlspecialchars($row['days']) ?></p>
                                    <p><strong>Timing:</strong> <?= htmlspecialchars($row['timing']) ?></p>
                                    <p><strong>Degree:</strong> <?= htmlspecialchars($row['degree']) ?></p>
                                    <p><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
                                    <p><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></p>
                                    <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
                                </div>

                                <button class="btn btn-sm btn-outline-primary mt-2 toggle-details-btn" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#doctorDetails<?= $counter ?>" 
                                    aria-expanded="false" 
                                    aria-controls="doctorDetails<?= $counter ?>">
                                    More Details
                                </button>
                            </div>
                        </div>
                    </div>
                <?php $counter++; endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-muted">No approved doctors found.</p>
        <?php endif; ?>
    </div>
</div>

    <!-- Team End -->



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
    <!-- Template Javascript -->

    <script src="js/main.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.toggle-details-btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = btn.getAttribute('data-bs-target');
            const collapseEl = document.querySelector(targetId);

            const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl);

            // No need for manual toggle, Bootstrap does this by data-bs-toggle automatically

            // Delay to wait for collapse animation
            setTimeout(() => {
                if (collapseEl.classList.contains('show')) {
                    btn.textContent = 'Less Details';
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-outline-danger');
                } else {
                    btn.textContent = 'More Details';
                    btn.classList.remove('btn-outline-danger');
                    btn.classList.add('btn-outline-primary');
                }
            }, 300); // Delay for smooth animation check
        });
    });
});
</script>
</body>

</html>