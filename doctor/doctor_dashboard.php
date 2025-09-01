<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'doctor') {
    header("Location: ../authentication/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; text-align:center; }
        h2 { color: #004080; margin-top: 20px; }
        .card-container { display: flex; justify-content:center; gap:40px; margin-top:40px; flex-wrap: wrap; }
        .card { background: white; width:250px; padding:15px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.2); text-align:center; }
        .card h3 { background:#0b1d35; color:white; padding:10px; border-radius:6px 6px 0 0; margin:-15px -15px 15px -15px; }
        .card a button { background:#0b1d35; color:white; padding:8px 15px; border:none; border-radius:5px; cursor:pointer; margin-top:10px; }
        .card a button:hover { background:#004080; }
        .logout { margin-top:40px; }
        .logout a button { background:red; color:white; padding:8px 15px; border:none; border-radius:5px; cursor:pointer; }
        .logout a button:hover { background:#ff6b81; }
    </style>
</head>
<body>

<h2>Welcome Doctor</h2>
<p>You are logged in as <strong>Doctor</strong>.</p>

<div class="card-container">
    <div class="card">
        <h3>My Appointments</h3>
        <img src="../assets/images/images (2).jpeg" alt="Appointments" style="width:100px;height:100px;border-radius:50%;object-fit:cover;">
        <br>
        <a href="view_appointment.php"><button>View Appointments</button></a>
    </div>
</div>

<div class="logout">
    <a href="../authentication/logout.php"><button>Logout</button></a>
</div>

</body>
</html>


