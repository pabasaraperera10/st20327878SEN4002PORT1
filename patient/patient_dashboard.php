<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'patient') {
    header("Location: ../authentication/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="patient_dashboard.css">
</head>
<body>

<h1>Welcome Patient</h1>

<div class="dashboard">

    <div class="card">
        <h3>Make Appointment</h3>
        <img src="../assets/images/book.png" alt="Make Appointment"><br><br>
        <button onclick="location.href='book_appointment.php'">Book Appointment</button>
    </div>

    <div class="card">
        <h3>View Appointments</h3>
        <img src="../assets/images/view.png" alt="View Appointments"><br><br>
        <button onclick="location.href='view_appointments.php'">View /Cancel</button>
    </div>

    <div class="card">
        <h3>Make Payment</h3>
        <img src="../assets/images/payment.png" alt="Make Payment"><br><br>
        <button onclick="location.href='payment_list.php'">Pay Now</button>

    </div>

    <div class="card">
        <h3>View Doctor Availability</h3>
        <img src="../assets/images/doctor.png" alt="Doctor Availability"><br><br>
        <button onclick="location.href='doctor_availability.php'">Check Availability</button>
    </div>

</div>

<div class="logout">
    <a href="../authentication/logout.php" class="logout-button">Logout</a>

</div>

</body>
</html>



