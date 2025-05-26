<?php
session_start();
include("db.php");

// Check login
if (!isset($_SESSION['doctor_id'])) {
    echo "<div class='alert'>Please log in to view your profile.</div>";
    exit;
}

$doctor_id = intval($_SESSION['doctor_id']);

// Fetch approved doctor
$query = "SELECT * FROM doctors WHERE id = $doctor_id AND status = 'approved'";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    echo "<div class='alert'>Your profile is either not approved or not found.</div>";
    exit;
}

$doctor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Profile</title>
    <style>
      /* Reset & Base Styles */
body, html {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f6f8;
    color: #333;
}

a {
    text-decoration: none;
}

img {
    max-width: 100%;
    height: auto;
}

/* Sidebar */
.sidebar {
    width: 250px;
    min-height: 100vh;
    background-color: #1e1e2f;
    padding: 20px 15px;
    color: #fff;
    position: fixed;
    top: 0;
    left: 0;
}

.brand-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    color: #fff;
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

/* Collapse Panel */
.collapse {
    display: none;
    flex-direction: column;
}

.collapse.show {
    display: flex;
}
/* Profile Container */
.profile-container {
    margin-left: 270px;
    padding: 30px;
    max-width: 900px;
}

/* Heading */
.profile-container h2 {
    margin-bottom: 20px;
    font-size: 26px;
    color: #2c3e50;
}

/* Table Wrapper */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background-color: #f8f9fa; /* Light grey */
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    border-radius: 8px;
    overflow: hidden;
    transition: box-shadow 0.3s ease;
}

/* Table Hover Animation */
table:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

/* Table Rows */
th, td {
    padding: 14px 20px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
    transition: background-color 0.3s ease;
}

th {
    background-color: #e9ecef;
    font-weight: 600;
    color: #444;
}

td {
    color: #333;
}

/* Row hover effect */
tr:hover td {
    background-color: #f1f1f1;
}

/* Edit Button */
.edit-btn {
    margin-top: 20px;
}

.edit-btn a {
    background-color: #28a745;
    color: #fff;
    padding: 10px 18px;
    border-radius: 5px;
    display: inline-block;
    transition: background-color 0.3s, transform 0.2s;
}

.edit-btn a:hover {
    background-color: #218838;
    transform: translateY(-2px);
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

    .profile-container {
        margin-left: 0;
        padding: 20px;
    }
}
.profile-container {
    animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

    </style>
</head>
<body>
 <nav class="sidebar">
        <div class="brand-title">
            <i class="fas fa-clinic-medical me-2"></i>CARE
        </div>
        
        <div class="sidebar-menu">
            <!-- Dashboard Section -->
            <div class="sidebar-section">
                <div class="section-title">Dashboard</div>
                <a href="doctor_profile.php" class="nav-link">
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
                    <a href="modefied_doctor.php" class="nav-link ps-4">
                        <i class="fas fa-edit"></i>
                        <span>Modify Patients</span>
                    </a>
                </div>
                
                <!-- My Appointments -->
                <a href="doctor_appointment.php" class="nav-link ">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>
            </div>
           
            
            <!-- Account Section -->
            <div class="sidebar-section">
                <div class="section-title">Account</div>
                <a href="modefied_doctor.php" class="nav-link active">
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
<div class="profile-container">
    <h2>Doctor Profile</h2>
    <table>
         <tr><th>Profile Image</th>
            <td>
                <?php if (!empty($doctor['image'])): ?>
                    <img src="<?= htmlspecialchars($doctor['image']) ?>" width="120" alt="Doctor Image">
                <?php else: ?>
                    No image uploaded.
                <?php endif; ?>
            </td>
        </tr>
        <tr><th>Name</th><td><?= htmlspecialchars($doctor['name']) ?></td></tr>
        <tr><th>Hospital</th><td><?= htmlspecialchars($doctor['hospital_name']) ?></td></tr>
        <tr><th>Phone</th><td><?= htmlspecialchars($doctor['phone']) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($doctor['email']) ?></td></tr>
        <tr><th>City</th><td><?= htmlspecialchars($doctor['city']) ?></td></tr>
        <tr><th>Specialization</th><td><?= htmlspecialchars($doctor['specialization']) ?></td></tr>
        <tr><th>Experience</th><td><?= htmlspecialchars($doctor['experience']) ?></td></tr>
        <tr><th>Degree</th><td><?= htmlspecialchars($doctor['degree']) ?></td></tr>
        <tr><th>Days Available</th><td><?= htmlspecialchars($doctor['days']) ?></td></tr>
        <tr><th>Timing</th><td><?= htmlspecialchars($doctor['timing']) ?></td></tr>
        <tr><th>Location</th><td><?= htmlspecialchars($doctor['location']) ?></td></tr>
        <tr><th>Address</th><td><?= nl2br(htmlspecialchars($doctor['address'])) ?></td></tr>
        <tr><th>Description</th><td><?= nl2br(htmlspecialchars($doctor['description'])) ?></td></tr>
       
    </table>

    <div class="edit-btn">
        <a href="edit_profile.php?id=<?= $doctor['id'] ?>">Edit Profile</a>
    </div>
</div>
<script>
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
