<?php
session_start();
include("../config/NewConnection.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'patient') {
    header("Location: ../authentication/login.php");
    exit();
}

// Get list of doctors
$stmt = $conn->prepare("SELECT id, full_name FROM doctors");
$stmt->execute();
$doctors = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard</title>
</head>
<body>
<h1>Welcome Patient</h1>

<h2>Check Doctor Availability</h2>
<ul>
<?php while($doctor = $doctors->fetch_assoc()): ?>
    <li>
        <a href="doctor_availability.php?doctor_id=<?= $doctor['id'] ?>">
            <?= htmlspecialchars($doctor['full_name']) ?>
        </a>
    </li>
<?php endwhile; ?>
</ul>

<!-- Rest of your dashboard cards -->

</body>
</html>
