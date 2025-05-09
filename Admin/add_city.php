<?php
session_start();
include("db.php");

// Create city table if it doesn't exist
$table = "CREATE TABLE IF NOT EXISTS city (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city_name VARCHAR(100) NOT NULL,
    province VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($table);

// Redirect if not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Add city form handler
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_city'])) {
    $city_name = $conn->real_escape_string($_POST['city_name']);
    $province = $conn->real_escape_string($_POST['province']);

    $conn->query("INSERT INTO city (city_name, province) VALUES ('$city_name', '$province')");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Medinova</title>
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
        .content-wrapper {
            padding: 20px;
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
                <a href="dashboard.php" class="nav-link active">
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
                <div class="collapse" id="doctorsCollapse">
                    <a href="add_doctor.php" class="nav-link ps-4">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Doctor</span>
                    </a>
                    <a href="view_doctors.php" class="nav-link ps-4">
                        <i class="fas fa-list"></i>
                        <span>View Doctors</span>
                    </a>
                    <a href="manage_doctors.php" class="nav-link ps-4">
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
            <h4 class="page-title">Admin Dashboard</h4>
            
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
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Content Area - Empty now --> <div class="main-content">
                <!-- Add city Form -->
                <form method="POST" action="">
    <div class="mb-3">
        <label for="city_name" class="form-label">City Name</label>
        <input type="text" class="form-control" id="city_name" name="city_name" required>
    </div>
    <div class="mb-3">
        <label for="province" class="form-label">Province (optional)</label>
        <input type="text" class="form-control" id="province" name="province">
    </div>
    <button type="submit" name="add_city" class="btn btn-primary">Add City</button>
</form>
            <!-- Content will be added here as needed -->
        </div>
            <!-- Content will be added here as needed -->
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
            document.querySelector('a[href="dashboard.php"]').classList.add('active');
        }
    });
</script>
</body>
</html>