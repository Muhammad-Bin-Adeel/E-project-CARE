<?php
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
// Handle Like Button
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['like_id'])) {
    $like_id = intval($_POST['like_id']);
    $conn->query("UPDATE medical_news SET likes = likes + 1 WHERE id = $like_id");
}

// Fetch news from the table
$query = "SELECT * FROM medical_news ORDER BY id DESC";
$result = $conn->query($query);
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
     .section-title {
            text-align: center;
            padding: 40px 0 20px;
        }

        .section-title h2 {
            color: var(--dark);
            font-weight: 700;
        }

        .news-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .news-card:hover {
            transform: translateY(-5px);
        }

        .news-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .news-content {
            padding: 20px;
        }

        .news-content h5 {
            color: var(--secondary);
            font-weight: bold;
        }

        .news-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            background-color: var(--light);
        }

        .news-footer img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .author {
            display: flex;
            align-items: center;
            color: var(--dark);
        }

        .meta form {
            display: inline-block;
            margin: 0;
        }

        .like-btn {
            background: none;
            border: none;
            color: #888;
            cursor: pointer;
        }

        .like-btn:hover {
            color: red;
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
              <a href="blog.php" class="dropdown-item active">Medical News</a>
              <a href="Disease.php" class="dropdown-item">Diseas Info</a>
    
            </div>
          </div>

          <a href="contact.php" class="nav-item nav-link">Contact</a>
        </div>

        
      </div>
    </nav>
  </div>
</div>
<!-- Navbar End -->


    <!-- Blog Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Blog Post</h5>
                <h1 class="display-4">Our Latest Medical Blog Posts</h1>
            </div>
            <div class="row g-4">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="news-card">
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="News Image">
                        <div class="news-content">
                            <h5><?= htmlspecialchars($row['title']) ?></h5>
                            <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                        </div>
                        <div class="news-footer">
                            <div class="author">
                                <img src="img/user.png" alt="Author">
                                <?= htmlspecialchars($row['author']) ?>
                            </div>
                            <div class="meta">
                                <form method="POST">
                                    <input type="hidden" name="like_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="like-btn">
                                        <i class="fa fa-heart text-danger"></i> <?= $row['likes'] ?>
                                    </button>
                                </form>
                                &nbsp;&nbsp;
                                <i class="fa fa-calendar-alt text-primary"></i> <?= date("d M Y", strtotime($row['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted">No medical news available.</div>
        <?php endif; ?>
    </div>
                </div>
                <div class="col-12 text-center">
                    <button class="btn btn-primary py-3 px-5">Load More</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Blog End -->
    

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