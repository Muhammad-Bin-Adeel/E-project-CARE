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

    $query = "SELECT a.*, d.name AS doctor_name, p.name AS name
              FROM appointments a
              JOIN doctors d ON a.doctor_id = d.id
              JOIN patients p ON a.patient_id = p.id
              WHERE a.id = ? AND a.patient_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $appointment_id, $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['status'] === 'Accepted') {

            // Print date
            $printDate = date('d M Y, h:i A');

            // Watermark CSS
            $watermark = "CARE MEDICAL";

            // Receipt HTML
            $html = '
            <style>
                body { font-family: DejaVu Sans, sans-serif; color: #1D2A4D; position: relative; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { color: #13C5DD; margin: 0; font-size: 28px; }
                .receipt { border: 1px solid #ccc; padding: 20px; border-radius: 10px; }
                h2 { color: #354F8E; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
                p { line-height: 1.6; font-size: 14px; }
                .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #888; }
                .watermark {
                    position: absolute;
                    top: 35%;
                    left: 20%;
                    font-size: 60px;
                    color: rgba(200, 200, 200, 0.2);
                    transform: rotate(-30deg);
                    z-index: 0;
                }
            </style>

            <div class="watermark">' . $watermark . '</div>

            <div class="header">
                <h1>CARE MEDICAL</h1>
            </div>

            <div class="receipt">
                <h2>Appointment Receipt</h2>
                <p><strong>Patient Name:</strong> ' . htmlspecialchars($row['name']) . '</p>
                <p><strong>Doctor Name:</strong> ' . htmlspecialchars($row['doctor_name']) . '</p>
                <p><strong>Specialization:</strong> ' . htmlspecialchars($row['specialization']) . '</p>
                <p><strong>Date:</strong> ' . htmlspecialchars($row['appointment_date']) . '</p>
                <p><strong>Time:</strong> ' . date('h:i A', strtotime($row['appointment_time'])) . '</p>
                <p><strong>Status:</strong> Confirmed</p>
            </div>

            <div class="footer">
                Printed on: ' . $printDate . '
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
