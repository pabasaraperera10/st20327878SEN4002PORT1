<?php
session_start();
include("../config/NewConnection.php");

// Check login and role
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'patient') {
    echo "Please log in as a patient to view your appointments.";
    exit();
}

// Use patient ID from session
$patient_id = $_SESSION['user_id'];

// Fetch patient appointments with latest payment status
$sql = "
    SELECT a.id, a.patient_name, a.patient_age, a.doctor_id, 
           a.appointment_date, a.appointment_time, a.status,
           d.full_name AS doctor_name,
           COALESCE(
               (SELECT p.payment_status 
                FROM payments p 
                WHERE p.appointment_id = a.id 
                ORDER BY p.id DESC LIMIT 1), 'Unpaid'
           ) AS payment_status
    FROM appointments a
    LEFT JOIN doctors d ON a.doctor_id = d.id
    WHERE a.patient_id = ?
    ORDER BY a.id ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Appointments</title>
    <link rel="stylesheet" href="view_appointments.css">
</head>
<body>
    <h2>Your Booked Appointments</h2>

    <?php
    if (isset($_GET['msg'])) {
        echo "<p>" . htmlspecialchars($_GET['msg']) . "</p>";
    }

    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Age</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Payment Status</th>
                <th>Action</th>
              </tr>";

        while ($row = $result->fetch_assoc()) {
            $status = strtolower(trim($row['payment_status']));

            if ($status === 'paid') {
                $paymentDisplay = "Paid";
            } elseif ($status === 'late paid') {
                $paymentDisplay = "Late Paid";
            } else {
                $paymentDisplay = "Unpaid";
            }

            echo "<tr>
                    <td>" . htmlspecialchars($row['id']) . "</td>
                    <td>" . htmlspecialchars($row['patient_name']) . "</td>
                    <td>" . htmlspecialchars($row['patient_age']) . "</td>
                    <td>" . htmlspecialchars($row['doctor_name'] ?? 'Not assigned') . "</td>
                    <td>" . htmlspecialchars($row['appointment_date']) . "</td>
                    <td>" . htmlspecialchars($row['appointment_time']) . "</td>
                    <td>" . $paymentDisplay . "</td>
                    <td>
                        <a href='cancel_appointment.php?id=" . $row['id'] . "' 
                           onclick=\"return confirm('Are you sure you want to cancel this appointment?');\">
                           Cancel
                        </a>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>You have no appointments booked yet.</p>";
    }

    $stmt->close();
    ?>
    <br>
    <div class="back-link-container">
        <a href="patient_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
