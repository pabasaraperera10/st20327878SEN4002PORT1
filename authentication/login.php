<?php
session_start();
include("../config/NewConnection.php");

$role = isset($_GET['role']) ? $_GET['role'] : '';

$msg = "";

// Redirect if no role selected
if (!$role) {
    header("Location: select_role.php");
    exit();
}

// Handle login
if (isset($_POST['submit'])) {
    $UserName = mysqli_real_escape_string($conn, $_POST["UserName"]);
    $pass = mysqli_real_escape_string($conn, $_POST["password"]);
    $role = mysqli_real_escape_string($conn, $_POST["role"]);

    $sql = "SELECT * FROM users WHERE username=? AND password=? AND role=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $UserName, $pass, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Set session variables
        $_SESSION['user'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['user_id'] = $row['id']; // <-- Add this line

        // Redirect based on role
        switch ($row['role']) {
            case 'admin':
                header("Location: ../admin/admin_dashboard.php");
                break;
            case 'doctor':
                header("Location: ../doctor/doctor_dashboard.php");
                break;
            case 'patient':
                header("Location: ../patient/patient_dashboard.php");
                break;
            case 'receptionist':
                header("Location: ../receptionist/receptionist_dashboard.php");
                break;
            default:
                $msg = "Role not recognized.";
        }
        exit();
    } else {
        $msg = "Invalid username, password, or role.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - <?php echo ucfirst(htmlspecialchars($role)); ?></title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login as <?php echo ucfirst(htmlspecialchars($role)); ?></h2>

            <form method="post" action="">
                <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">

                <label>Username:</label>
                <input type="text" name="UserName" required>

                <label>Password:</label>
                <input type="password" name="password" required>

                <button type="submit" name="submit">Login</button>

                <?php if ($role != 'patient'): ?>
                    <button type="reset">Clear</button>
                <?php endif; ?>

                <?php if ($role == 'patient'): ?>
                    <button type="button" id="register" style="background-color: black;">Register</button>
                <?php endif; ?>

                <?php if ($msg != "") echo "<p class='error'>$msg</p>"; ?>

                <div class="back-link">
                    <button type="button" id="backBtn">Back to Role Selection</button>
                </div>
            </form>

            <script>
               document.getElementById('backBtn').addEventListener('click', function() {
                   window.location.href = 'select_role.php';
               });

                 document.getElementById('register').addEventListener('click', function() {
                   window.location.href = 'register.php';
               });
            </script>
        </div>
    </div>
</body>
</html>
