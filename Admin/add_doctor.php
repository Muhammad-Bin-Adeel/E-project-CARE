<?php
session_start();
include("db.php");

// Create doctors table if not exists
$table = "CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    hospital_name VARCHAR(150),
    phone VARCHAR(20),
    specialization VARCHAR(100),
    city VARCHAR(100),
    days VARCHAR(100),
    timing VARCHAR(100),
    experience VARCHAR(100),
    description TEXT,
    status ENUM('pending', 'approved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($table);

// Redirect if not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Add doctor form handler
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_doctor'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $hospital_name = $conn->real_escape_string($_POST['hospital_name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $specialization = $conn->real_escape_string($_POST['specialization']);
    $city = $conn->real_escape_string($_POST['city']);
    $days = $conn->real_escape_string($_POST['days']);
    $timing = $conn->real_escape_string($_POST['timing']);
    $experience = $conn->real_escape_string($_POST['experience']);
    $description = $conn->real_escape_string($_POST['description']);

    $conn->query("INSERT INTO doctors (name, hospital_name, phone, specialization, city, days, timing, experience, description)
                  VALUES ('$name', '$hospital_name', '$phone', '$specialization', '$city', '$days', '$timing', '$experience', '$description')");
}

// Approve or delete
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $conn->query("UPDATE doctors SET status='approved' WHERE id=$id");
}
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM doctors WHERE id=$id");
}

// Fetch all doctors
$result = $conn->query("SELECT * FROM doctors ORDER BY status DESC, id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Doctors - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        :root {
            --primary: #13C5DD;
            --secondary: #354F8E;
            --light: #EFF5F9;
            --dark: #1D2A4D;
        }

        body {
            background-color: var(--light);
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #e0e6ed;
        }

        .sidebar a {
            display: block;
            padding: 15px 20px;
            color: var(--dark);
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 600;
        }

        .topbar {
            background-color: var(--primary);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #ffffff;
        }

        .dashboard-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .main-content {
            padding: 30px;
        }

        .table th {
            background-color: var(--primary);
            color: #fff;
        }

        .badge-approved {
            background-color: #28a745;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-sm {
            padding: 4px 8px;
        }

        .brand-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--primary);
            text-align: center;
            padding: 20px 0;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.06);
            padding: 20px;
        }

        .card {
            background-color: #fff;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="position-sticky">
                <h2 class="brand-title"><i class="fa fa-clinic-medical me-2"></i>MEDINOVA</h2>
                <a href="dashboard.php"><i class="fas fa-chart-line me-2"></i>Dashboard</a>
                <a href="add_city.php"><i class="fas fa-city me-2"></i>Add City</a>
                <a href="view_cities.php"><i class="fas fa-eye me-2"></i>View Cities</a>
                <a href="add_doctor.php"><i class="fas fa-user-md me-2"></i>Add Doctor</a>
                <a href="view_doctors.php"><i class="fas fa-users me-2"></i>View Doctors</a>
                <a href="view_doctor_requests.php" class="active"><i class="fas fa-user-check me-2"></i>Manage Doctors</a>
                <a href="view_appointments.php"><i class="fas fa-calendar-check me-2"></i>Appointments</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-md-4">
            <div class="topbar">
                <div class="dashboard-title">Manage Doctor Requests</div>
                <div><img src="img/user.png" width="40" class="rounded-circle" alt="Admin"></div>
            </div>

            <div class="main-content">
                <!-- Add Doctor Form -->
                <div class="card mb-4 p-4 shadow-sm rounded">
                    <h5 class="mb-3 text-secondary">Add New Doctor</h5>
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="name" class="form-control" placeholder="Doctor's Name" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="hospital_name" class="form-control" placeholder="Hospital Name" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="phone" class="form-control" placeholder="Phone" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="specialization" class="form-control" placeholder="Specialization" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="city" class="form-control" placeholder="City" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="days" class="form-control" placeholder="Available Days (e.g., Mon-Fri)" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="timing" class="form-control" placeholder="Timing (e.g., 10AM - 2PM)" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="experience" class="form-control" placeholder="Experience (e.g., 5 Years)" required>
                            </div>
                            <div class="col-md-12">
                                <textarea name="description" class="form-control" rows="3" placeholder="Brief Description" required></textarea>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="add_doctor" class="btn btn-primary w-100">
                                    <i class="fas fa-plus-circle"></i> Add Doctor
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Doctor Table -->
                <div class="table-container">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Name</th>
                                <th>Hospital</th>
                                <th>Specialization</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Days</th>
                                <th>Timing</th>
                                <th>Experience</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['hospital_name']) ?></td>
                                        <td><?= htmlspecialchars($row['specialization']) ?></td>
                                        <td><?= htmlspecialchars($row['phone']) ?></td>
                                        <td><?= htmlspecialchars($row['city']) ?></td>
                                        <td><?= htmlspecialchars($row['days']) ?></td>
                                        <td><?= htmlspecialchars($row['timing']) ?></td>
                                        <td><?= htmlspecialchars($row['experience']) ?></td>
                                        <td><?= htmlspecialchars($row['description']) ?></td>
                                        <td>
                                            <span class="badge <?= $row['status'] === 'approved' ? 'badge-approved' : 'badge-pending' ?>">
                                                <?= ucfirst($row['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($row['status'] !== 'approved'): ?>
                                                <a href="?approve=<?= $row['id'] ?>" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Approve
                                                </a>
                                            <?php endif; ?>
                                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this doctor?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="12" class="text-center text-muted">No doctor records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>


