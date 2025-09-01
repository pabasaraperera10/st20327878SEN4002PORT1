<?php
session_start();
include("../config/NewConnection.php");

// Check if admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../authentication/login.php");
    exit();
}

// Fetch total patients
$patientResult = mysqli_query($conn, "SELECT COUNT(*) AS total_patients FROM patients");
$patientRow = mysqli_fetch_assoc($patientResult);
$totalPatients = $patientRow['total_patients'];

// Fetch total doctors
$doctorResult = mysqli_query($conn, "SELECT COUNT(*) AS total_doctors FROM doctors");
$doctorRow = mysqli_fetch_assoc($doctorResult);
$totalDoctors = $doctorRow['total_doctors'];

// Fetch total appointments
$appointmentResult = mysqli_query($conn, "SELECT COUNT(*) AS total_appointments FROM appointments");
$appointmentRow = mysqli_fetch_assoc($appointmentResult);
$totalAppointments = $appointmentRow['total_appointments'];

// Fetch appointments per doctor
$appointmentsPerDoctorResult = mysqli_query($conn, "
    SELECT d.full_name, COUNT(a.id) AS appointments_count
    FROM doctors d
    LEFT JOIN appointments a ON d.id = a.doctor_id
    GROUP BY d.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Report</title>
    

    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        h1, h2 { color: #004080; text-align: center; }
        table { border-collapse: collapse; width: 80%; margin: 20px auto; background: #fff; }
        table, th, td { border: 1px solid #0b1d35; }
        th, td { padding: 10px; text-align: center; }
        th { background-color: #0b1d35; color: white; }
        ul { list-style: none; padding: 0; width: 50%; margin: 0 auto; }
        ul li { background: #fff; margin: 5px auto; padding: 10px; border: 1px solid #0b1d35; border-radius: 5px; text-align: center; }
        button { padding:10px 20px; background-color:#0b1d35; color:white; border:none; border-radius:5px; cursor:pointer; }
        button:hover { background-color:#004080; }
        .center { text-align:center; margin-top: 30px; }
    </style>
</head>
<body>

<h1>Admin Reports</h1>

<h2>Summary</h2>
<ul>
    <li>Total Patients: <?php echo $totalPatients; ?></li>
    <li>Total Doctors: <?php echo $totalDoctors; ?></li>
    <li>Total Appointments: <?php echo $totalAppointments; ?></li>
</ul>

<h2>Appointments per Doctor</h2>
<table>
    <tr>
        <th>Doctor Name</th>
        <th>Appointments Count</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($appointmentsPerDoctorResult)) { ?>
    <tr>
        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
        <td><?php echo $row['appointments_count']; ?></td>
    </tr>
    <?php } ?>
</table>

<div class="center">
    <a href="admin_dashboard.php"><button>Back to Dashboard</button></a>
</div>

</body>
</html>
