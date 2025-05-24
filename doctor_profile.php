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
    <title>Doctor Dashboard - Medinova</title>
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
    --dashboard-bg: #f1f9f2; /* light greenish background */
    --card-border: #28a745; /* green for dashboard highlights */
}

body {
    font-family: Arial, sans-serif;
    background: var(--dashboard-bg);
    padding: 40px;
}

/* Sidebar */
.sidebar {
    height: 100vh;
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
    text-decoration: none;
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

/* Main Content */
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

/* Dashboard Green Styling */
.dashboard-wrapper {
    padding: 30px;
    background-color: var(--dashboard-bg);
}

.dashboard-wrapper h2 {
    font-size: 26px;
    font-weight: 600;
    color: var(--success);
    margin-bottom: 20px;
}

.dashboard-panels {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.dashboard-card {
    background-color: #fff;
    border-left: 5px solid var(--card-border);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.dashboard-card h4 {
    font-size: 18px;
    color: var(--dark);
    margin-bottom: 8px;
}

.dashboard-card p {
    font-size: 16px;
    color: #555;
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

/* Dropdown Animation */
.collapse:not(.show) {
    display: none;
}

.collapsing {
    height: 0;
    overflow: hidden;
    transition: height 0.35s ease;
}

/* Responsive */
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

/* Profile Card */
.header {
    background: var(--primary);
    padding: 20px;
    color: white;
    font-size: 24px;
    font-weight: bold;
}

.profile-card {
    max-width: 700px;
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
}

.profile-card img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ddd;
    margin-right: 20px;
}

.profile-info h2 {
    margin: 0 0 10px;
    font-size: 26px;
}

.profile-info p {
    margin: 5px 0;
    font-size: 16px;
}

.label {
    font-weight: bold;
}

/* Doctor Detail Card */
.card-header.bg-primary {
    background-color: var(--primary) !important;
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
                <a href="doctor_profile.php" class="nav-link active">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <!-- My Panel Section -->
            <div class="sidebar-section">
                <div class="section-title">My Panel</div>
                
                <!-- My Patients -->
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
</body>
</html>
