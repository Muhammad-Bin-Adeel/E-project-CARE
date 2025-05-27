<?php
session_start();
include("db.php");

if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit();
}

$patient_id = $_SESSION['patient_id'];

$sql = "SELECT a.*, d.name AS doctor_name 
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.id
        WHERE a.patient_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Appointments</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #EFF5F9;
            font-family: Arial, sans-serif;
        }
        h2 {
            color: #354F8E;
        }
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
        .badge-secondary { background-color: #6c757d; }
        .badge-warning { background-color: #ffc107; color: #000; }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">My Appointments</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered bg-white">
            <thead class="thead-dark">
                <tr>
                    <th>P_Name</th>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($row['specialization']) ?></td>
                    <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                    <td>
                        <?php
                        $status = strtolower($row['status']);
                        if ($status === 'approved' || $status === 'accepted') {
                            echo '<span class="badge badge-success">Approved</span>';
                        } elseif ($status === 'declined' || $status === 'rejected') {
                            echo '<span class="badge badge-danger">Declined</span>';
                        } elseif ($status === 'pending') {
                            echo '<span class="badge badge-warning">Pending</span>';
                        } else {
                            echo '<span class="badge badge-secondary">Unknown</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($status === 'approved' || $status === 'accepted'): ?>
                            <a href="appointments_pdf.php?appointment_id=<?= $row['id'] ?>" class="btn btn-sm btn-success" target="_blank">Download PDF</a>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No appointments found.</div>
    <?php endif; ?>
</div>
</body>
</html>
