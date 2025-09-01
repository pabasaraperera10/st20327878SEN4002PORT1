<?php
session_start();
include("../config/NewConnection.php");

// Only for receptionists
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'receptionist'){
    header("Location: ../authentication/login.php");
    exit();
}

// Fetch all appointments with doctor info (use LEFT JOIN in case doctor not assigned)
$sql = "
    SELECT a.id, a.patient_name, a.patient_age, a.appointment_date, a.appointment_time, a.status,
           d.full_name AS doctor_name, d.specialty
    FROM appointments a
    LEFT JOIN doctors d ON a.doctor_id = d.id
    ORDER BY a.appointment_date, a.appointment_time
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Receptionist - View Appointments</title>
    <link rel="stylesheet" href="receptionist_appointments.css">
</head>
<body>
<h2>Appointments</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Patient Name</th>
        <th>Age</th>
        <th>Doctor</th>
        <th>Specialty</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
    </tr>

    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
        <td><?= htmlspecialchars($row['patient_name']); ?></td>
        <td><?= htmlspecialchars($row['patient_age']); ?></td>
        <td><?= htmlspecialchars($row['doctor_name'] ?? 'Not assigned'); ?></td>
        <td><?= htmlspecialchars($row['specialty'] ?? ''); ?></td>
        <td><?= htmlspecialchars($row['appointment_date']); ?></td>
        <td><?= htmlspecialchars($row['appointment_time']); ?></td>
        <td><?= htmlspecialchars($row['status']); ?></td>
    </tr>
    <?php } ?>
</table>

<br>
<a href="receptionist_dashboard.php">Back to Dashboard</a>
</body>
</html>
