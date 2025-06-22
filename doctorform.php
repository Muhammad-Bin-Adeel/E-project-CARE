<?php
session_start();
include("db.php");

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
    email VARCHAR(255),
    password VARCHAR(255),
    status ENUM('pending','approved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");



// Add or update doctor
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $name = $conn->real_escape_string($_POST['name']);
    $hospital = $conn->real_escape_string($_POST['hospital_name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $spec = $conn->real_escape_string($_POST['specialization']);
    $city = $conn->real_escape_string($_POST['city']);
    $days = '';
if (isset($_POST['days']) && is_array($_POST['days'])) {
    // Sanitize and join checked days
    $sanitizedDays = array_map(function($day) use ($conn) {
        return $conn->real_escape_string($day);
    }, $_POST['days']);
    $days = implode(',', $sanitizedDays);
}
    $timing = '';
if (isset($_POST['timing']) && is_array($_POST['timing'])) {
    $sanitizedTimes = array_map(function($time) use ($conn) {
        return $conn->real_escape_string($time);
    }, $_POST['timing']);
    $timing = implode(',', $sanitizedTimes);
}
    $exp = $conn->real_escape_string($_POST['experience']);
    $desc = $conn->real_escape_string($_POST['description']);
    $location = $conn->real_escape_string($_POST['location']);
    $address = $conn->real_escape_string($_POST['address']);
    $degree = ($_POST['degree'] === 'Other') 
        ? $conn->real_escape_string($_POST['other_degree']) 
        : $conn->real_escape_string($_POST['degree']);
        $email = $conn->real_escape_string($_POST['email']);

    $password = $conn->real_escape_string($_POST['password'] ?? '');


    $imagePath = '';
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        $imagePath = $targetDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    if ($id) {
        // Update existing doctor
        $query = "UPDATE doctors SET 
                    name='$name', hospital_name='$hospital', phone='$phone',
                    specialization='$spec', city='$city', days='$days',
                    timing='$timing', experience='$exp', description='$desc',
                   location='$location', address='$address', degree='$degree', email='$email'";


           
        if (!empty($password)) {
            $query .= ", password='$password'";
        }
        if ($imagePath) $query .= ", image='$imagePath'";
        $query .= " WHERE id=" . intval($id);
    
        $conn->query($query);
        $_SESSION['message'] = "Doctor updated successfully!";
        header("Location: manage_doctors.php");
        exit;
    } else {
        // Insert new doctor
        $conn->query("INSERT INTO doctors 
        (name, hospital_name, phone, specialization, city, days, timing, experience, description, image, location, address, degree, email, password) 
        VALUES 
        ('$name','$hospital','$phone','$spec','$city','$days','$timing','$exp','$desc','$imagePath','$location','$address','$degree','$email','$password')");
        $_SESSION['message'] = "Doctor Request submited successfully!";
    
        }
        header("Location: thanks.php");
        exit;
    
}



// Fetch all doctors
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
    <title>CARE - Hospital Website </title>
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
        <!-- Navigation Links -->
        <div class="navbar-nav ms-auto py-0">
          <a href="index.php" class="nav-item nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>">Home</a>
          <a href="about.php" class="nav-item nav-link <?php echo ($currentPage == 'about.php') ? 'active' : ''; ?>">About</a>
          <a href="doctors.php" class="nav-item nav-link <?php echo ($currentPage == 'doctors.php') ? 'active' : ''; ?>">Doctors</a>
          <a href="appointment.php" class="nav-item nav-link <?php echo ($currentPage == 'appointment.php') ? 'active' : ''; ?>">Appointment</a>

          <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle <?php echo ($currentPage == 'blog.php' || $currentPage == 'Disease.php') ? 'active' : ''; ?>" data-bs-toggle="dropdown">Medical Info</a>
            <div class="dropdown-menu m-0">
              <a href="blog.php" class="dropdown-item <?php echo ($currentPage == 'blog.php') ? 'active' : ''; ?>">Medical News</a>
              <a href="Disease.php" class="dropdown-item <?php echo ($currentPage == 'Disease.php') ? 'active' : ''; ?>">Disease Info</a>
            </div>
          </div>

          <a href="contact.php" class="nav-item nav-link <?php echo ($currentPage == 'contact.php') ? 'active' : ''; ?>">Contact</a>
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
          <i class="fas fa-user-md me-2"></i> <?= isset($edit) ? 'Edit Doctor' : 'Doctors Request Form' ?>
        </h4>

        <form  method="POST" enctype="multipart/form-data">
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
  <select name="city" class="form-control" required>
    <option value="">-- Select City --</option>
    <?php
      include 'db.php'; // your DB connection file
      $city_query = mysqli_query($conn, "SELECT * FROM city");
      while ($row = mysqli_fetch_assoc($city_query)) {
        $selected = (isset($edit['city']) && $edit['city'] == $row['city_name']) ? 'selected' : '';
        echo "<option value='{$row['city_name']}' $selected>{$row['city_name']}</option>";
      }
    ?>
  </select>
</div> 
    

    <div class="form-group">
  <label class="form-label">Available Days:</label><br>
  <?php
    $selectedDays = isset($edit['days']) ? explode(',', $edit['days']) : [];
    $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    foreach ($allDays as $day) {
        $checked = in_array($day, $selectedDays) ? 'checked' : '';
        echo "<label><input type='checkbox' name='days[]' value='$day' $checked> $day</label><br>";
    }
  ?>
</div>

    <div class="form-group">
  <label class="form-label">Timing:</label>
  <select name="timing[]" class="form-control" multiple required>
    <?php
      $available_timings = [
          "09:00 AM - 11:00 AM",
          "11:00 AM - 01:00 PM",
          "02:00 PM - 04:00 PM",
          "04:00 PM - 06:00 PM"
      ];
      $selectedTimings = isset($edit['timing']) ? explode(",", $edit['timing']) : [];
      foreach ($available_timings as $time) {
          $selected = in_array($time, $selectedTimings) ? 'selected' : '';
          echo "<option value=\"$time\" $selected>$time</option>";
      }
    ?>
  </select>
  <small>Hold Ctrl (Windows) or Cmd (Mac) to select multiple</small>
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
<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="<?= $edit['email'] ?? '' ?>" required>
</div>

<div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control" value="<?= isset($edit) ? $edit['password'] : '' ?>" required />
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
            <button type="submit" class="btn btn-primary px-4">Request Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


    <!-- Footer Start -->
    <?php include("footer.php"); ?>
    <!-- Footer End -->

    <!-- Swiper JS -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>