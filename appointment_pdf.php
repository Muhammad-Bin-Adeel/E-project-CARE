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

    $query = "SELECT a.*, p.name AS patient_name, d.name AS doctor_name
              FROM appointments a
              JOIN patients p ON a.patient_id = p.id
              JOIN doctors d ON a.doctor_id = d.id
              WHERE a.id = '$appointment_id' AND a.patient_id = '$patient_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if ($row['status'] === 'approved') {
            // Build receipt HTML
            $html = '
            <style>
                body { font-family: DejaVu Sans, sans-serif; color: #1D2A4D; }
                .receipt { border: 1px solid #ccc; padding: 20px; border-radius: 10px; }
                h2 { color: #13C5DD; }
                p { line-height: 1.6; }
            </style>
            <div class="receipt">
                <h2>Appointment Receipt</h2>
                <p><strong>Patient Name:</strong> ' . htmlspecialchars($row['patient_name']) . '</p>
                <p><strong>Doctor Name:</strong> ' . htmlspecialchars($row['doctor_name']) . '</p>
                <p><strong>Date:</strong> ' . htmlspecialchars($row['date']) . '</p>
                <p><strong>Time:</strong> ' . htmlspecialchars($row['time']) . '</p>
                <p><strong>Status:</strong> Confirmed</p>
                <p style="margin-top: 30px;">Thank you for choosing our service.</p>
            </div>';

            $dompdf = new Dompdf();
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
    echo "<p style='color:red; text-align:center;'>Appointment ID missing.</p>";
}
?>
