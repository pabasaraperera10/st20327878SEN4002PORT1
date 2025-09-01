<?php
session_start();
include("../config/NewConnection.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../authentication/login.php");
    exit();
}

$sql = "SELECT d.full_name, da.available_date, da.start_time, da.end_time 
        FROM doctor_availability da
        JOIN doctors d ON da.doctor_id = d.id
        ORDER BY da.available_date, da.start_time";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Availability</title>
    <link rel="stylesheet" href="view_availability.css"> 
</head>
<body>
<h2>Doctors' Availability</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['available_date']) ?></td>
                <td><?= htmlspecialchars($row['start_time']) ?></td>
                <td><?= htmlspecialchars($row['end_time']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No availability found.</p>
<?php endif; ?>

<p><a href="patient_dashboard.php">← Back to Dashboard</a></p>
</body>
</html>

