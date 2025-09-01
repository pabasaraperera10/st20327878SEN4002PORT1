<?php
// select_role.php
$selectedRole = isset($_GET['role']) ? $_GET['role'] : '';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Select Role - Hospital Appointment Management System</title>
  <link rel="stylesheet" href="select_role.css">
</head>
<body>

<h1>Select Your Role</h1>

<div class="role-container">
  <div class="role-card <?php if ($selectedRole === 'admin') echo 'selected'; ?>" onclick="selectRole('admin')">
    <img src="../assets/images/admin.png" alt="Admin">
    <h3>Admin</h3>
  </div>
  <div class="role-card <?php if ($selectedRole === 'receptionist') echo 'selected'; ?>" onclick="selectRole('receptionist')">
    <img src="../assets/images/receptionist.png" alt="Receptionist">
    <h3>Receptionist</h3>
  </div>
  <div class="role-card <?php if ($selectedRole === 'doctor') echo 'selected'; ?>" onclick="selectRole('doctor')">
    <img src="../assets/images/doctor.png" alt="Doctor">
    <h3>Doctor</h3>
  </div>
  <div class="role-card <?php if ($selectedRole === 'patient') echo 'selected'; ?>" onclick="selectRole('patient')">
    <img src="../assets/images/patient.png" alt="Patient">
    <h3>Patient</h3>
  </div>
</div>

<div>
  <button onclick="goHome()" class="btn-back-home">Back to Home</button>
</div>

<script>
function selectRole(role) {
  window.location.href = "login.php?role=" + role;
}

function goHome() {
  window.location.href = "../index.php";
}
</script>


</body>
</html>
