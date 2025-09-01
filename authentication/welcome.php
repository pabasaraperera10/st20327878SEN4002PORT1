<?php
session_start();

if (!isset($_SESSION['user'])) {
   header("Location: ../authentication/login.php");

    exit();
}

echo "<h2>Welcome, " . htmlspecialchars($_SESSION['user']) . "!</h2>";
echo "<p>Your role: " . htmlspecialchars($_SESSION['role']) . "</p>";
?>

<a href="../authentication/logout.php">Logout</a>



