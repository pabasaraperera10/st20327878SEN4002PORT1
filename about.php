<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>About - Hospital Appointment Management System</title>
    <style>
        /* General */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('assets/images/hospital_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }
        
      body::before {
      content: "";
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: rgba(255, 255, 255, 0.49);
      z-index: -1;
}

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: flex-end;
             background-color: rgba(0, 123, 127, 0.85);
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 30px;
            margin: 0;
            padding: 0;
        }

        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        
        

        /* Content */
        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.75);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        .container h1 {
            color: #004085;
            text-align: left;
            margin-bottom: 15px;
        }

        .container p {
            font-size: 16px;
            line-height: 1.6;
        }

        .container h2 {
            color: #004085;
            margin-top: 25px;
        }

        ul {
            padding-left: 25px;
            line-height: 1.8;
        }

        /* Footer */
        footer.footer {
             background-color: rgba(0, 123, 127, 0.85);
            color: white;
            text-align: center;
            padding: 40px 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="authentication/select_role.php">Login</a></li>
            <li><a href="about.php" class="active">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h1><center>About</center><br>Hospital Appointment Management System</h1>
        <p>
            The Hospital Appointment Management System is designed to simplify the process of scheduling, managing, and tracking patient appointments in hospitals or clinics. It helps patients, doctors, and receptionists handle appointments efficiently and maintain accurate records.
        </p>

        <h2>Key Features</h2>
        <ul>
            <li>Role-based login for Admin, Doctor, Receptionist, and Patient.</li>
            <li>Appointment booking, viewing, and cancellation for patients.</li>
            <li>Doctor schedule management and daily appointment view.</li>
            <li>managing patients and doctors.</li>
            <li>Secure session management and user authentication.</li>
        </ul>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025 Hospital Appointment Management System. All rights reserved.</p>
    </footer>
</body>
</html>


