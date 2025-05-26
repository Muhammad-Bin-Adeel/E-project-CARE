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
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">My Appointments</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Patient Name</th>
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
                    <td><?= htmlspecialchars($row['patient_name']) ?></td>
                    <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($row['specialization']) ?></td>
                    <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                    <td>
                        <?php
                        if (isset($row['status'])) {
                            if ($row['status'] === 'approved') {
                                echo '<span class="badge badge-success">Approved</span>';
                            } elseif ($row['status'] === 'declined') {
                                echo '<span class="badge badge-danger">Declined</span>';
                            } else {
                                echo '<span class="badge badge-secondary">Pending</span>';
                            }
                        } else {
                            echo '<span class="badge badge-secondary">No Status</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($row['status'] === 'approved'): ?>
    <a href="appointments_pdf.php?appointment_id=<?= $row['id'] ?>" class="btn btn-sm btn-success" target="_blank">Download Receipt</a>
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
