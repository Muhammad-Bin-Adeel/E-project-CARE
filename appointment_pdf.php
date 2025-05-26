<?php
session_start();
require 'vendor/autoload.php'; 
use Dompdf\Dompdf;
use Dompdf\Options;
include("db.php");

if (!isset($_SESSION['patient_id'])) {
    die("Access denied. Please log in first.");
}

$patient_id = $_SESSION['patient_id'];

if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

    $query = "SELECT a.*, d.name AS doctor_name, p.name AS patient_name 
              FROM appointments a
              JOIN doctors d ON a.doctor_id = d.id
              JOIN patients p ON a.patient_id = p.id
              WHERE a.id = ? AND a.patient_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $appointment_id, $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['status'] === 'approved') {
            // Generate receipt HTML
            $html = '
            <style>
                body { font-family: DejaVu Sans, sans-serif; color: #1D2A4D; }
                .receipt { border: 1px solid #ccc; padding: 20px; border-radius: 10px; }
                h2 { color: #13C5DD; }
                p { line-height: 1.6; font-size: 14px; }
            </style>
            <div class="receipt">
                <h2>Appointment Receipt</h2>
                <p><strong>Patient Name:</strong> ' . htmlspecialchars($row['patient_name']) . '</p>
                <p><strong>Doctor Name:</strong> ' . htmlspecialchars($row['doctor_name']) . '</p>
                <p><strong>Specialization:</strong> ' . htmlspecialchars($row['specialization']) . '</p>
                <p><strong>Date:</strong> ' . htmlspecialchars($row['appointment_date']) . '</p>
                <p><strong>Time:</strong> ' . date('h:i A', strtotime($row['appointment_time'])) . '</p>
                <p><strong>Status:</strong> Confirmed</p>
                <p style="margin-top: 30px;">Thank you for choosing our service.</p>
            </div>';

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream("Appointment_Receipt.pdf", ["Attachment" => 1]);
        } else {
            echo "<p style='color:red; text-align:center;'>Appointment is not approved yet.</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>Invalid or unauthorized appointment.</p>";
    }
} else {
    echo "<p style='color:red; text-align:center;'>Appointment ID is missing.</p>";
}
?>