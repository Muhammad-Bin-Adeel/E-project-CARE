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
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            padding: 40px;
        }

        .profile-container {
            max-width: 800px;
            margin: auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            color: #333;
            width: 200px;
        }

        td img {
            border-radius: 8px;
        }

        .edit-btn {
            display: block;
            text-align: center;
            margin-top: 25px;
        }

        .edit-btn a {
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .edit-btn a:hover {
            background-color: #2980b9;
        }

        .alert {
            background: #ffe5e5;
            color: #b30000;
            padding: 15px;
            border-radius: 5px;
            margin: 20px;
            text-align: center;
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

</body>
</html>
