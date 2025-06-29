<?php
session_start();
include("db.php");

if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Doctor info fetch karna
$stmtDoctor = $conn->prepare("SELECT name FROM doctors WHERE id = ?");
$stmtDoctor->bind_param("i", $doctor_id);
$stmtDoctor->execute();
$doctor = $stmtDoctor->get_result()->fetch_assoc();

// Accept/Decline action handle karna
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $appointment_id = intval($_GET['id']);
    $status = $action === 'accept' ? 'Accepted' : 'Declined';

    if ($status === 'Accepted') {
        $approved_date = date('Y-m-d');
        $approved_time = date('H:i:s');

        $stmtUpdate = $conn->prepare("UPDATE appointments SET status = ?, approved_date = ?, approved_time = ? WHERE id = ? AND doctor_id = ?");
        $stmtUpdate->bind_param("sssii", $status, $approved_date, $approved_time, $appointment_id, $doctor_id);
    } else {
        $stmtUpdate = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = ?");
        $stmtUpdate->bind_param("sii", $status, $appointment_id, $doctor_id);
    }

    $stmtUpdate->execute();

    // Redirect to avoid resubmission on refresh
    header("Location: doctor_appointment.php");
    exit();
}

// Doctor ke appointments fetch karo
$stmt = $conn->prepare("SELECT id, patient_name, email, appointment_date, appointment_time, status FROM appointments WHERE doctor_id = ? ORDER BY created_at DESC");

$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$appointments = $stmt->get_result();

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
    /* Reset & Base */
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f4f4;
    color: #333;
}

/* Sidebar */
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    background: #1f1f1f;
    color: #fff;
    padding: 20px 15px;
    overflow-y: auto;
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
    margin-bottom: 20px;
}

.section-title {
    font-size: 14px;
    font-weight: bold;
    color: #aaa;
    margin-bottom: 10px;
    text-transform: uppercase;
}

/* Nav Links */
.nav-link {
    display: flex;
    align-items: center;
    padding: 10px 12px;
    color: #ccc;
    text-decoration: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.nav-link:hover,
.nav-link.active {
    background-color: #333;
    color: #fff;
}

.nav-link i {
    margin-right: 10px;
}

.nav-link .ms-auto {
    margin-left: auto;
}

/* Collapse menu */
.collapse {
    display: none;
    flex-direction: column;
}

.collapse.show {
    display: flex;
}

.ps-4 {
    padding-left: 2rem !important;
}

/* Content */
.container {
    margin-left: 270px;
    padding: 20px;
}

/* Card */
.card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.card-header {
    font-weight: bold;
    background-color: #2e3b4e;
    color: #fff;
    padding: 15px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.card-body {
    padding: 20px;
}

/* Table */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 10px 12px;
    border: 1px solid #ddd;
}

.table th {
    background-color: #3a3a3a;
    color: #fff;
}

.table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Badges */
.badge {
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 13px;
}

.bg-warning {
    background-color: #ffc107 !important;
    color: #000;
}

.bg-success {
    background-color: #28a745 !important;
}

.bg-danger {
    background-color: #dc3545 !important;
}

/* Buttons */
.btn {
    padding: 5px 10px;
    font-size: 13px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
}

.btn-sm {
    padding: 4px 8px;
}

.btn-success {
    background-color: #28a745;
    color: #fff;
}

.btn-danger {
    background-color: #dc3545;
    color: #fff;
}

/* Scrollbar for sidebar */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-thumb {
    background-color: #555;
    border-radius: 10px;
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
                <a href="doctor_appointment.php" class="nav-link active">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>
            </div>
           
            
            <!-- Account Section -->
            <div class="sidebar-section">
                <div class="section-title">Account</div>
                <a href="modefied_doctor.php" class="nav-link">
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
                                <td><?= date('h:i A', strtotime($row['appointment_time'])) ?></td>
                                <td>
                                    <?php
                                        $status = strtolower($row['status']);
                                        if ($status === 'pending'):
                                    ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif ($status === 'accepted' || $status === 'approved'): ?>
                                        <span class="badge bg-success">Accepted</span>
                                    <?php elseif ($status === 'declined' || $status === 'rejected'): ?>
                                        <span class="badge bg-danger">Declined</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Unknown</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($status === 'pending'): ?>
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