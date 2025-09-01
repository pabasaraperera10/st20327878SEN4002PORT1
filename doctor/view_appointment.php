<?php
session_start();
include("../config/NewConnection.php");

// Only allow doctors
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'doctor'){
    header("Location: ../authentication/login.php");
    exit();
}

$doctor_id = $_SESSION['user_id']; // Doctor ID stored at login

// Fetch appointments assigned to this doctor (confirmed only)
$sql = "SELECT a.id, a.patient_name, a.patient_age, a.appointment_date, a.appointment_time, a.status
        FROM appointments a
        WHERE a.doctor_id = '$doctor_id' AND a.status='confirmed'
        ORDER BY a.appointment_date, a.appointment_time";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor - My Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #04204bff;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-confirmed {
            color: green;
            font-weight: bold;
        }

        .no-appointments {
            text-align: center;
            color: #777;
            font-style: italic;
            margin: 20px 0;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #27085fff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .back-btn:hover {
            background-color: #5e71dfff;
        }
    </style>
</head>
<body>
<h2>Doctor Dashboard - My Appointments</h2>

<?php 
if($result && $result->num_rows > 0): ?>
<table>
    <tr>
        <th>Patient Name</th>
        <th>Age</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
    </tr>
    <?php while($a = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($a['patient_name']); ?></td>
        <td><?= htmlspecialchars($a['patient_age']); ?></td>
        <td><?= htmlspecialchars($a['appointment_date']); ?></td>
        <td><?= date("h:i A", strtotime($a['appointment_time'])); ?></td>
        <td class="status-<?= strtolower($a['status']); ?>"><?= htmlspecialchars($a['status']); ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
<p class="no-appointments">No appointments assigned yet. Pending appointments may not be assigned to you by the receptionist.</p>
<?php endif; ?>

<br>
<a href="doctor_dashboard.php" class="back-btn">Back to Dashboard</a>
</body>
</html>
