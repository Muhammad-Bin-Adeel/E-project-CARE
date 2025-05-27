<?php
session_start();
include("db.php");

if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit;
}

$doctor_id = $_SESSION['doctor_id'];

$stmt = $conn->prepare("SELECT * FROM doctors WHERE id = ? AND status = 'approved'");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Doctor not found.";
    exit;
}

$doctor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - CARE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
   <style>
/* Base Reset */
body, html {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: #f0f2f5;
    color: #333;
}

.wrapper {
    display: flex;
}

/* Sidebar */
.sidebar {
    width: 250px;
    min-height: 100vh;
    background-color: #1e1e2f;
    padding: 20px 15px;
    color: #fff;
    position: fixed;
    left: 0;
    top: 0;
    overflow-y: auto;
    z-index: 1000;
}

.brand-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 30px;
    color: #fff;
    display: flex;
    align-items: center;
}

.sidebar-menu {
    display: flex;
    flex-direction: column;
}

.sidebar-section {
    margin-bottom: 25px;
}

.section-title {
    font-size: 13px;
    text-transform: uppercase;
    font-weight: bold;
    color: #aaa;
    margin-bottom: 10px;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 10px 12px;
    border-radius: 6px;
    color: #ccc;
    text-decoration: none;
    transition: background-color 0.3s ease;
    cursor: pointer;
}

.nav-link:hover,
.nav-link.active {
    background-color: #333;
    color: #fff;
}

.nav-link i {
    margin-right: 10px;
}

.ms-auto {
    margin-left: auto;
}

.ps-4 {
    padding-left: 1.5rem !important;
}

/* Collapse */
.collapse {
    display: none;
    flex-direction: column;
}

.collapse.show {
    display: flex;
}

/* Main Content */
.main-content {
    margin-left: 250px;
    width: 100%;
    padding: 0;
    background-color: #f0f2f5;
    min-height: 100vh;
}

/* Topbar */
.topbar {
    background-color: #343a40;
    color: #fff;
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    position: sticky;
    top: 0;
    z-index: 999;
    border-bottom: 1px solid #444;
}

.page-title {
    margin: 0;
    font-size: 20px;
}

.search-bar {
    position: relative;
    margin-right: 20px;
}

.search-bar input {
    padding: 5px 10px 5px 30px;
    border-radius: 4px;
    border: none;
    outline: none;
}

.search-bar i {
    position: absolute;
    top: 50%;
    left: 8px;
    transform: translateY(-50%);
    color: #555;
}

.notification-bell {
    position: relative;
    margin-right: 20px;
    color: #fff;
    cursor: pointer;
}

.notification-bell .notification-badge {
    position: absolute;
    top: -5px;
    right: -8px;
    background-color: red;
    color: #fff;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 50%;
}

/* Dropdown menu */
.user-menu img {
    width: 35px;
    height: 35px;
    border-radius: 50%;
}

.dropdown-menu {
    background-color: #fff;
    color: #333;
}

.dropdown-item:hover {
    background-color: #f1f1f1;
}

/* Container */
.container {
    padding: 30px;
}

/* Card */
.card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.card-header {
    background-color: #2e3b4e;
    color: #fff;
    padding: 15px 20px;
    font-weight: bold;
}

.card-body {
    padding: 20px;
}

.img-fluid {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    margin-bottom: 15px;
}

/* Buttons */
.btn {
    padding: 8px 14px;
    border-radius: 5px;
    font-size: 14px;
    text-decoration: none;
    cursor: pointer;
    display: inline-block;
}

.btn-secondary {
    background-color: #6c757d;
    color: #fff;
    border: none;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

/* Responsive */
@media (max-width: 991px) {
    .sidebar {
        position: absolute;
        left: -250px;
        transition: all 0.3s ease;
    }

    .sidebar.show {
        left: 0;
    }

    .main-content {
        margin-left: 0;
    }
}

</style>

</head>
<body>
    <div class="wrapper">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="brand-title">
            <i class="fas fa-clinic-medical me-2"></i>CARE
        </div>
        
        <div class="sidebar-menu">
            <!-- Dashboard Section -->
            <div class="sidebar-section">
                <div class="section-title">Dashboard</div>
                <a href="doctor_profile.php" class="nav-link active">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <!-- My Panel Section -->
            <div class="sidebar-section">
                <div class="section-title">My Panel</div>
                
                
                
                <!-- My Appointments -->
                <a href="doctor_appointment.php" class="nav-link">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>
            </div>
            
            <!-- Account Section -->
            <div class="sidebar-section">
                <div class="section-title">Account</div>
                <a href="profile.php" class="nav-link">
                    <i class="fas fa-user-cog"></i>
                    <span>Profile Settings</span>
                </a>
                <a href="logout.php" class="nav-link">
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
            <h4 class="page-title">Doctor Dashboard</h4>
            
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
                        <img src="assets/img/doctor-avatar.jpg" alt="Doctor">
                        <span class="ms-2 d-none d-sm-inline">Doctor</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="doctor_profile_view.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
<div class="container mt-5">
    <h2>Doctor Detail</h2>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <?= htmlspecialchars($doctor['name']) ?>
        </div>
        <div class="card-body">
        <?php if ($doctor['image']): ?>
                <img src="<?= htmlspecialchars($doctor['image']) ?>" class="img-fluid" alt="Doctor Image" style="max-width: 200px;">
            <?php endif; ?>
            <p><strong>Specialization:</strong> <?= htmlspecialchars($doctor['specialization']) ?></p>
            <p><strong>Degree:</strong> <?= htmlspecialchars($doctor['degree']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($doctor['phone']) ?></p>
            <p><strong>City:</strong> <?= htmlspecialchars($doctor['city']) ?></p>
            <p><strong>Experience:</strong> <?= htmlspecialchars($doctor['experience']) ?></p>
            <p><strong>Schedule:</strong> <?= htmlspecialchars($doctor['days']) ?> - <?= htmlspecialchars($doctor['timing']) ?></p>
           
        </div>
    </div>
    <a href="view_doctor.php" class="btn btn-secondary mt-3">Back</a>
</div>
<script>
document.getElementById("sidebarToggle").addEventListener("click", function () {
    document.querySelector(".sidebar").classList.toggle("show");
});

// Collapse toggle
function toggleCollapse(id) {
    const collapse = document.getElementById(id);
    const icon = document.getElementById(id + 'Icon');
    if (collapse.classList.contains('show')) {
        collapse.classList.remove('show');
        icon.classList.remove('fa-angle-up');
        icon.classList.add('fa-angle-down');
    } else {
        collapse.classList.add('show');
        icon.classList.remove('fa-angle-down');
        icon.classList.add('fa-angle-up');
    }
}
</script>
</body>
</html>
