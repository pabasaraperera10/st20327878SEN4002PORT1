<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Connection</title>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("<p style='color: red;'>❌ Connection failed: " . mysqli_connect_error() . "</p>");
}

//echo "<p style='color: green;'>✅ Connected successfully to <strong>$dbname</strong></p>";
?>

</body>
</html>

