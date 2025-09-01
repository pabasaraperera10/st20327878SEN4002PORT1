<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../authentication/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }

        h2 {
            color: #004080;
            margin-top: 20px;
        }

        p {
            font-size: 16px;
            color: #333;
        }

        .card-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            width: 250px;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
            text-align: center;
        }

        .card h3 {
            background-color: #0b1d35;
            color: white;
            padding: 10px;
            border-radius: 6px 6px 0 0;
            margin: -15px -15px 15px -15px;
        }

        .card img {
            width: 100px;
            height: 100px;
            margin: 10px 0;
        }

        .card a button {
            background-color: #0b1d35;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .card a button:hover {
            background-color: #004080;
        }

        .logout {
            margin-top: 40px;
        }

        .logout a button {
            background-color: red;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout a button:hover {
            background-color: #ff6b81;
        }
    </style>
</head>
<body>

<h2>Welcome Admin</h2>

<p>You are logged in as <strong>Admin</strong>.</p>

<div class="card-container">
    <div class="card">
        <h3>Manage Doctors</h3>
        <img src="../assets/images/images (2).jpeg" alt="Manage Doctors">
        <br>
        <a href="manage_doctors.php"><button>Manage</button></a>
    </div>

    <div class="card">
        <h3>Doctor Availability</h3>
        <img src="../assets/images/images (3).jpeg" alt="Doctor Availability">
        <br>
        <a href="add_availability.php"><button>View / Add</button></a>
    </div>

    <div class="card">
        <h3>Reports</h3>
        <img src="../assets/images/report1.jpeg" alt="Reports">
        <br>
        <a href="admin_report.php"><button>View</button></a>
    </div>
</div>

<div class="logout">
    <a href="../authentication/logout.php"><button>Logout</button></a>
</div>

</body>
</html>


