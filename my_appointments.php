<?php
session_start();
include("db.php");

// Check if patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

// Fetch patient appointments
$query = "SELECT a.*, d.name AS doctor_name FROM appointments a
          JOIN doctors d ON a.doctor_id = d.id
          WHERE a.patient_id = '$patient_id'
          ORDER BY a.appointment_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body style="background-color: #EFF5F9;">
<div class="container mt-5">
    <h2 class="mb-4 text-primary">My Appointments</h2>
    <table class="table table-bordered table-striped bg-white shadow">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Receipt</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $i = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                        <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                        <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                        <td>
                            <?php if ($row['status'] === 'approved') : ?>
                                <span class="badge bg-success">Approved</span>
                            <?php elseif ($row['status'] === 'pending') : ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['status'] === 'approved') : ?>
                                <a href="appointment_pdf.php?appointment_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Download PDF</a>
                            <?php else : ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr><td colspan="6" class="text-center">No appointments found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
