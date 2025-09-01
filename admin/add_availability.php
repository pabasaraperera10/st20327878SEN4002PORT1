<?php
session_start();
include("../config/NewConnection.php");

// Check if user is doctor/admin
if (!isset($_SESSION['user']) || !in_array($_SESSION['role'], ['doctor', 'admin'])) {
    header("Location: ../authentication/login.php");
    exit();
}

// If doctor, use their ID; if admin, admin selects doctor
$doctor_id = ($_SESSION['role'] == 'doctor') ? $_SESSION['user_id'] : 0;

$error = $success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['doctor_id'] ?? $doctor_id;
    $available_date = $_POST['available_date'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';

    if (!$doctor_id || !$available_date || !$start_time || !$end_time) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO doctor_availability (doctor_id, available_date, start_time, end_time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $doctor_id, $available_date, $start_time, $end_time);

        if ($stmt->execute()) {
            $success = "Availability added successfully.";
        } else {
            $error = "Error adding availability: " . $conn->error;
        }
    }
}

// Fetch doctors for admin select box in ascending order
$doctors = [];
if ($_SESSION['role'] == 'admin') {
    $res = $conn->query("
        SELECT u.id AS user_id, d.full_name 
        FROM doctors d
        INNER JOIN users u ON d.user_id = u.id
        ORDER BY u.id ASC
    ");
    while ($row = $res->fetch_assoc()) {
        $doctors[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Doctor Availability</title>
    <link rel="stylesheet" href="add_availability.css">
</head>
<body>
<h2>Add Doctor Availability</h2>

<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
<?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>

<form method="post">
    <?php if ($_SESSION['role'] == 'admin'): ?>
        <label for="doctor_id">Doctor:</label>
        <select name="doctor_id" id="doctor_id" required>
            <option value="">Select Doctor</option>
            <?php foreach ($doctors as $doc): ?>
                <option value="<?= $doc['user_id'] ?>" <?= ($doctor_id == $doc['user_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($doc['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
    <?php endif; ?>

    <label for="available_date">Date:</label>
    <input type="date" name="available_date" id="available_date" required><br><br>

    <label for="start_time">Start Time:</label>
    <input type="time" name="start_time" id="start_time" required><br><br>

    <label for="end_time">End Time:</label>
    <input type="time" name="end_time" id="end_time" required><br><br>

    <button type="submit">Add Availability</button>
</form>

<a href="admin_dashboard.php">Back to Dashboard</a>

</body>
</html>

