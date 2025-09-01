<?php
session_start();
include("../config/NewConnection.php");

// Only allow logged-in patients
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'patient') {
    header("Location: ../authentication/login.php");
    exit();
}

$doctor = null;

// If a specific doctor is selected
if (isset($_GET['doctor_id']) && is_numeric($_GET['doctor_id'])) {
    $doctor_id = intval($_GET['doctor_id']);
    
    // Fetch doctor info
    $stmt = $conn->prepare("SELECT full_name FROM doctors WHERE user_id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows != 1) {
        die("Doctor not found.");
    }
    $doctor = $result->fetch_assoc();

    // Fetch availability for this doctor
    $stmt = $conn->prepare("
        SELECT available_date, start_time, end_time 
        FROM doctor_availability 
        WHERE doctor_id = ? 
          AND status = 'available' 
          AND available_date >= CURDATE()
        ORDER BY available_date, start_time
    ");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $availability = $stmt->get_result();
} else {
    // Fetch availability for all doctors
    $stmt = $conn->prepare("
        SELECT da.available_date, da.start_time, da.end_time, 
               COALESCE(d.full_name, 'Unknown Doctor') AS full_name
        FROM doctor_availability da 
        LEFT JOIN doctors d ON da.doctor_id = d.user_id 
        WHERE da.status = 'available' 
          AND da.available_date >= CURDATE()
        ORDER BY da.available_date, da.start_time
    ");
    $stmt->execute();
    $availability = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>
        <?= $doctor ? "Availability for Dr. " . htmlspecialchars($doctor['full_name']) : "All Doctors Availability" ?>
    </title>
    <link rel="stylesheet" href="doctor_availability.css">
</head>
<body>
    <h2><?= $doctor ? "Availability for Dr. " . htmlspecialchars($doctor['full_name']) : "All Doctors Availability" ?></h2>

    <?php if ($availability->num_rows == 0): ?>
        <p>No available slots at the moment.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <?php if (!$doctor): ?>
                        <th>Doctor</th>
                    <?php endif; ?>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($slot = $availability->fetch_assoc()): ?>
                <tr>
                    <?php if (!$doctor): ?>
                        <td><?= htmlspecialchars($slot['full_name']) ?></td>
                    <?php endif; ?>
                    <td><?= htmlspecialchars($slot['available_date']) ?></td>
                    <td><?= htmlspecialchars($slot['start_time']) ?></td>
                    <td><?= htmlspecialchars($slot['end_time']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="patient_dashboard.php" class="back-button">Back to Dashboard</a></p>
</body>
</html>


