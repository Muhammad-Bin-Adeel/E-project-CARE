<?php
session_start();
include("db.php");

if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit;
}

$doctor_id = $_SESSION['doctor_id'];

// Doctor info
$stmtDoctor = $conn->prepare("SELECT name FROM doctors WHERE id = ?");
$stmtDoctor->bind_param("i", $doctor_id);
$stmtDoctor->execute();
$doctor = $stmtDoctor->get_result()->fetch_assoc();

// Accept/Decline handler
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $appointment_id = intval($_GET['id']);
    $status = $action === 'accept' ? 'Accepted' : 'Declined';

    $stmtUpdate = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = ?");
    $stmtUpdate->bind_param("sii", $status, $appointment_id, $doctor_id);
    $stmtUpdate->execute();

    header("Location: doctor_appointment.php");
    exit;
}

// Fetch appointments
$stmt = $conn->prepare("SELECT * FROM appointments WHERE doctor_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$appointments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
         body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background: #f4f4f4; }
        .pending { color: orange; font-weight: bold; }
        .accepted { color: green; font-weight: bold; }
        .declined { color: red; font-weight: bold; }
        .btn { padding: 5px 10px; border: none; border-radius: 3px; text-decoration: none; }
        .accept { background-color: green; color: white; }
        .decline { background-color: red; color: white; }
    </style>
</head>
<body>
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
                <a href="doctor_appointments.php" class="nav-link">
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
     <!-- Content Area  -->
        

<div class="container mt-5">
    <h2>Welcome, Dr. <?= htmlspecialchars($doctor['name']) ?></h2>
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">Your Appointments</div>
        <div class="card-body">
            <?php if ($appointments->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Patient Name</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; while ($row = $appointments->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= $row['appointment_date'] ?></td>
                                <td><?= $row['appointment_time'] ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif ($row['status'] == 'Accepted'): ?>
                                        <span class="badge bg-success">Accepted</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Declined</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] == 'Pending'): ?>
                                        <a href="?action=accept&id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Accept</a>
                                        <a href="?action=decline&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Decline</a>
                                    <?php else: ?>
                                        <span class="text-muted">No action</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No appointments found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>