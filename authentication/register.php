<?php
session_start();
include("../config/NewConnection.php");

$role = 'patient';

$msg = "";

// Redirect if no role selected
if (!$role) {
    header("Location: select_role.php");
    exit();
}

// Handle register
if (isset($_POST['submit'])) {
    $UserName = mysqli_real_escape_string($conn, $_POST["UserName"]);
    $pass = mysqli_real_escape_string($conn, $_POST["password"]);
    $confirmPass = mysqli_real_escape_string($conn, $_POST["cpassword"]);

    $role = mysqli_real_escape_string($conn, $_POST["role"]);

    if ($pass != $confirmPass){
        $msg = "Password does not match";
    }else {
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $UserName, $pass, $role);
        
        
        if($stmt->execute()){
            header("Location: login.php?role=patient");
            exit();
        }else {
            $msg = "Unable to create a user";
        }    
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register user</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Register New User</h2>

            <form method="post" action="">
                <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">

                <label>Username:</label>
                <input type="text" name="UserName" required>

                <label>Password:</label>
                <input type="password" name="password" required>

                <label>Confirm password:</label>
                <input type="password" name="cpassword" required>

                <button type="submit" name="submit">Submit</button>

                <button type="reset">Clear</button>

                <?php if ($msg != "") echo "<p class='error'>$msg</p>"; ?>

                <div class="back-link">
                    <button type="button" id="backBtn">Back to Login</button>
                </div>
            </form>

            <script>
               document.getElementById('backBtn').addEventListener('click', function() {
                   window.location.href = 'login.php?role=patient';
               });
            </script>
        </div>
    </div>
</body>
</html>
