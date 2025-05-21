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


if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

// Add or update doctor
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $name = $conn->real_escape_string($_POST['name']);
    $hospital = $conn->real_escape_string($_POST['hospital_name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $spec = $conn->real_escape_string($_POST['specialization']);
    $city = $conn->real_escape_string($_POST['city']);
    $days = $conn->real_escape_string($_POST['days']);
    $timing = $conn->real_escape_string($_POST['timing']);
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
        header("Location: manage_doctors.php");
        exit;
    
}

// Approve doctor
if (isset($_GET['approve'])) {
    $conn->query("UPDATE doctors SET status='approved' WHERE id=" . intval($_GET['approve']));
    $_SESSION['message'] = "Your Request Has Been Submitted!";
    header("Location: manage_doctors.php");
    exit;
}

// Delete doctor
if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM doctors WHERE id=" . intval($_GET['delete']));
    $_SESSION['message'] = "Doctor deleted successfully!";
    header("Location: manage_doctors.php");
    exit;
}

// Edit doctor (fetch single doctor)
$edit = null;
if (isset($_GET['edit'])) {
    $res = $conn->query("SELECT * FROM doctors WHERE id=" . intval($_GET['edit']));
    if ($res->num_rows) $edit = $res->fetch_assoc();
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Management - Medinova</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #13C5DD;
            --secondary: #354F8E;
            --light: #EFF5F9;
            --dark: #1D2A4D;
            --success: #28a745;
            --danger: #dc3545;
        }
        
        body {
            background-color: var(--light);
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            height: 100vh;
    overflow-y: auto; /* ✅ Enable vertical scroll */
    background-color: #ffffff;
    border-right: 1px solid #e0e6ed;
    position: fixed;
    width: 250px;
    transition: all 0.3s;
    z-index: 1000;
        }
        
        .brand-title {
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: bold;
            padding: 20px 0;
            text-align: center;
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
        }
        
        .sidebar-menu {
            padding: 0 15px;
        }
        
        .sidebar-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            color: var(--secondary);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 5px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .nav-link {
            color: var(--dark);
            border-radius: 5px;
            margin-bottom: 5px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: var(--primary);
            color: white !important;
        }
        
        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
        }
        
        .topbar {
            background-color: var(--primary);
            padding: 12px 20px;
            border-bottom: 1px solid #e0e6ed;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            color: white;
        }
        
        .page-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }
        
        .user-menu img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Search Bar */
         .search-bar {
            position: relative;
            width: 220px;
            margin: 0 15px;
        }
        
        .search-bar input {
            background-color: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 6px 12px 6px 35px;
            border-radius: 20px;
            width: 100%;
            font-size: 0.9rem;
        }
        
        .search-bar input::placeholder {
            color: rgba(255,255,255,0.7);
        }
        
        .search-bar i {
            position: absolute;
            left: 12px;
            top: 8px;
            font-size: 0.9rem;
        }
        
        /* Notification Bell */
        .notification-bell {
            position: relative;
            margin-right: 15px;
            font-size: 1.1rem;
            cursor: pointer;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger);
            color: white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Content Area */
       
        .card {
  border-radius: 10px;
  border: 1px solid #ddd;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    background-color: var(--light);
  font-family: "Segoe UI", sans-serif;
  color: var(--dark);
  
}

.text-primary {
  color: var(--primary);
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.full-width {
  grid-column: 1 / -1;
}

.form-label {
  font-weight: 600;
  margin-bottom: 5px;
}

.form-control {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 15px;
}

.form-actions {
  text-align: right;
}

.form-actions button {
  background-color: var(--primary);
  border: none;
  padding: 10px 25px;
  border-radius: 6px;
  color: #fff;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.form-actions button:hover {
  background-color: var(--secondary);
}

/* Responsive tweaks */
@media (max-width: 768px) {
  .form-actions {
    text-align: center;
  }
}
    
/* Alert Box */
.alert {
    padding: 20px;
    background-color: var(--primary);
    color: white;
    border-radius: 5px;
    margin: 20px 0;
}

.alert button {
    background: none;
    color: white;
    border: none;
    font-size: 1.5em;
    cursor: pointer;
}
        
        
        
        
        /* Dropdown Animation */
        .collapse:not(.show) {
            display: none;
        }
        
        .collapsing {
            height: 0;
            overflow: hidden;
            transition: height 0.35s ease;
        }
        
        /* Responsive Styles */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .search-bar {
                width: 180px;
            }
        }
        
        @media (max-width: 767.98px) {
            .search-bar {
                display: none;
            }
            .notification-bell {
                margin-right: 10px;
            }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="brand-title">
            <i class="fas fa-clinic-medical me-2"></i>MEDINOVA
        </div>
        
        <div class="sidebar-menu">
            <!-- Dashboard Section -->
            <div class="sidebar-section">
                <div class="section-title">Dashboard</div>
                <a href="admin_dashboard.php" class="nav-link">
                    <i class="fas fa-chart-pie"></i>
                    <span>Overview</span>
                </a>
            </div>
            
            <!-- Management Section -->
            <div class="sidebar-section">
                <div class="section-title">Management</div>
                
                <!-- Cities Management -->
                <div class="nav-link" onclick="toggleCollapse('citiesCollapse')">
                    <i class="fas fa-city"></i>
                    <span>Cities</span>
                    <i class="fas fa-angle-down ms-auto" id="citiesCollapseIcon"></i>
                </div>
                <div class="collapse" id="citiesCollapse">
                    <a href="add_city.php" class="nav-link ps-4">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add City</span>
                    </a>
                    <a href="view_cities.php" class="nav-link ps-4">
                        <i class="fas fa-list"></i>
                        <span>View Cities</span>
                    </a>
                </div>
                
                <!-- Doctors Management -->
                <div class="nav-link" onclick="toggleCollapse('doctorsCollapse')">
                    <i class="fas fa-user-md"></i>
                    <span>Doctors</span>
                    <i class="fas fa-angle-down ms-auto" id="doctorsCollapseIcon"></i>
                </div>
                <div class="collapse show" id="doctorsCollapse">
                    <a href="add_doctor.php" class="nav-link ps-4">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Doctor</span>
                    </a>
                    <a href="view_doctors.php" class="nav-link ps-4">
                        <i class="fas fa-list"></i>
                        <span>View Doctors</span>
                    </a>
                    <a href="manage_doctors.php" class="nav-link ps-4 active">
                        <i class="fas fa-edit"></i>
                        <span>Modify Doctors</span>
                    </a>
                </div>
                
                <!-- Patients Management -->
                <div class="nav-link" onclick="toggleCollapse('patientsCollapse')">
                    <i class="fas fa-procedures"></i>
                    <span>Patients</span>
                    <i class="fas fa-angle-down ms-auto" id="patientsCollapseIcon"></i>
                </div>
                <div class="collapse" id="patientsCollapse">
                    <a href="view_patients.php" class="nav-link ps-4">
                        <i class="fas fa-list"></i>
                        <span>View Patients</span>
                    </a>
                    <a href="manage_patients.php" class="nav-link ps-4">
                        <i class="fas fa-edit"></i>
                        <span>Modify Patients</span>
                    </a>
                </div>
                
                <!-- Appointments -->
                <a href="view_appointments.php" class="nav-link">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>
            </div>
            
            <!-- System Section -->
            <div class="sidebar-section">
                <div class="section-title">System</div>
                <a href="manage_users.php" class="nav-link">
                    <i class="fas fa-users-cog"></i>
                    <span>User Management</span>
                </a>
                <div class="nav-link" onclick="toggleCollapse('contentCollapse')">
                    <i class="fas fa-globe"></i>
                    <span>Website Content</span>
                    <i class="fas fa-angle-down ms-auto" id="contentCollapseIcon"></i>
                </div>
                <div class="collapse" id="contentCollapse">
                    <a href="diseases_info.php" class="nav-link ps-4">
                        <i class="fas fa-disease"></i>
                        <span>Diseases Info</span>
                    </a>
                    <a href="medical_news.php" class="nav-link ps-4">
                        <i class="fas fa-newspaper"></i>
                        <span>Medical News</span>
                    </a>
                </div>
            </div>
            
            <!-- Account Section -->
            <div class="sidebar-section">
                <div class="section-title">Account</div>
                <a href="admin_logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <button class="btn btn-sm btn-outline-light d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h4 class="page-title">Doctor Management</h4>
            
            <div class="d-flex align-items-center">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                
                <div class="notification-bell">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                
                <div class="user-menu dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                        <img src="assets/img/admin-avatar.jpg" alt="Admin">
                        <span class="ms-2 d-none d-sm-inline">Admin</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="admin_logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-wrapper">
  <div class="row">
    <div class="col-md-12">
      <div class="card shadow p-4">
        <h4 class="mb-4 text-primary">
          <i class="fas fa-user-md me-2"></i> <?= isset($edit) ? 'Edit Doctor' : 'Add New Doctor' ?>
        </h4>

        <form method="POST" enctype="multipart/form-data">
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

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="<?= $edit['email'] ?? '' ?>" required>
</div>

<div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control" value="<?= isset($edit) ? $edit['password'] : '' ?>" required/>
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
            <button type="submit" class="btn btn-primary px-4">Save Doctor</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle sidebar on mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        
        if (window.innerWidth < 992 && 
            !sidebar.contains(event.target) && 
            !toggleBtn.contains(event.target) && 
            sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });
    
    // Custom dropdown toggle function
    function toggleCollapse(id) {
        const element = document.getElementById(id);
        const icon = document.getElementById(id + 'Icon');
        
        if (element.classList.contains('show')) {
            element.classList.remove('show');
            icon.classList.remove('fa-angle-up');
            icon.classList.add('fa-angle-down');
        } else {
            // Close all other dropdowns first
            document.querySelectorAll('.collapse.show').forEach(collapse => {
                if (collapse.id !== id) {
                    collapse.classList.remove('show');
                    const otherIcon = document.getElementById(collapse.id + 'Icon');
                    if (otherIcon) {
                        otherIcon.classList.remove('fa-angle-up');
                        otherIcon.classList.add('fa-angle-down');
                    }
                }
            });
            
            element.classList.add('show');
            icon.classList.remove('fa-angle-down');
            icon.classList.add('fa-angle-up');
        }
    }
    
    // Initialize dropdowns based on current page
    document.addEventListener('DOMContentLoaded', function() {
        // Get current page URL
        const currentUrl = window.location.pathname.split('/').pop();
        
        // Remove active class from all links first
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        
        // Map of pages to their parent dropdown IDs
        const pageToDropdownMap = {
            'dashboard.php': null,
            'add_city.php': 'citiesCollapse',
            'view_cities.php': 'citiesCollapse',
            'add_doctor.php': 'doctorsCollapse',
            'view_doctors.php': 'doctorsCollapse',
            'manage_doctors.php': 'doctorsCollapse',
            'view_patients.php': 'patientsCollapse',
            'manage_patients.php': 'patientsCollapse',
            'view_appointments.php': null,
            'manage_users.php': null,
            'diseases_info.php': 'contentCollapse',
            'medical_news.php': 'contentCollapse',
            'profile.php': null,
            'settings.php': null
        };
        
        // Check if current page is in our map
        if (pageToDropdownMap[currentUrl]) {
            const dropdownId = pageToDropdownMap[currentUrl];
            const element = document.getElementById(dropdownId);
            const icon = document.getElementById(dropdownId + 'Icon');
            
            if (element && !element.classList.contains('show')) {
                element.classList.add('show');
                if (icon) {
                    icon.classList.remove('fa-angle-down');
                    icon.classList.add('fa-angle-up');
                }
            }
        }
        
        // Highlight active link
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.getAttribute('href') === currentUrl) {
                link.classList.add('active');
                
                // If this is a child link, also highlight its parent dropdown
                const parentDropdown = link.closest('.collapse');
                if (parentDropdown) {
                    const parentLink = document.querySelector(`[onclick*="${parentDropdown.id}"]`);
                    if (parentLink) {
                        parentLink.classList.add('active');
                    }
                }
            }
        });
        
        // Special case for dashboard (default page)
        if (currentUrl === '' || currentUrl === 'index.php') {
            document.querySelector('a[href="admin_dashboard.php"]').classList.add('active');
        }
    });
    function initAutocomplete() {
        const input = document.getElementById('location-input');
        new google.maps.places.Autocomplete(input);
    }
    google.maps.event.addDomListener(window, 'load', initAutocomplete);
    
</script>
<script>
function toggleOtherDegree(select) {
    const otherField = document.getElementById('other-degree-group');
    if (select.value === 'Other') {
        otherField.style.display = 'block';
        document.getElementById('other_degree').setAttribute('required', 'required');
    } else {
        otherField.style.display = 'none';
        document.getElementById('other_degree').removeAttribute('required');
    }
}

// Initialize on page load in case "Other" is already selected
window.onload = function () {
    const degreeSelect = document.getElementById('degree');
    toggleOtherDegree(degreeSelect);
};
</script>
</body>
</html>