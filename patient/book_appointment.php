<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../authentication/login.php");
    exit();
}

include("../config/NewConnection.php");

// Fetch doctors list
$doctor_list = [];
$sql = "SELECT id, full_name, specialty FROM doctors ORDER BY full_name ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $doctor_list[] = $row;
}
$stmt->close();

$message = '';
$message_class = '';

// Get logged-in patient ID from session
$patient_id = $_SESSION['user_id']; // Make sure user_id is stored in session at login

if (isset($_POST['BtnSubmit'])) {
    $patient_name = trim($_POST['patient_name']);
    $patient_age = (int)$_POST['patient_age'];
    $doctor_id = (int)$_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    // Validate input
    if ($patient_name == '' || $patient_age <= 0 || $doctor_id <= 0 || $appointment_date == '' || $appointment_time == '') {
        $message = " Please fill in all required fields correctly.";
        $message_class = "error";
    } else {
        // Insert appointment
        $insert_sql = "INSERT INTO appointments 
            (patient_id, patient_name, patient_age, doctor_id, appointment_date, appointment_time, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("isisss", $patient_id, $patient_name, $patient_age, $doctor_id, $appointment_date, $appointment_time);

        if ($stmt->execute()) {
            $appointment_id = $stmt->insert_id;

            // Optional: create placeholder payment
            $payment_sql = "INSERT INTO payments (appointment_id, amount, payment_status, created_at) 
                            VALUES (?, 0, 'Unpaid', NOW())";
            $payment_stmt = $conn->prepare($payment_sql);
            $payment_stmt->bind_param("i", $appointment_id);
            $payment_stmt->execute();
            $payment_stmt->close();

            $message = " Appointment booked successfully! Payment is pending.";
            $message_class = "success";
        } else {
            $message = " Error: " . htmlspecialchars($stmt->error);
            $message_class = "error";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Book Appointment</title>
    <link rel="stylesheet" href="book_appointment.css">
</head>
<body>
    <form method="post" action="">
        <?php if ($message): ?>
            <h3 class="<?php echo $message_class; ?>"><?php echo $message; ?></h3>
        <?php else: ?>
            <h4>Please fill the form to book your <center>appointment</center></h4>
        <?php endif; ?>

        Patient Name: <input type="text" name="patient_name" required><br>
        Patient Age: <input type="number" name="patient_age" required><br>

        Doctor:
        <select name="doctor_id" required>
            <option value="">-- Select Doctor --</option>
            <?php foreach ($doctor_list as $doctor): ?>
                <option value="<?php echo $doctor['id']; ?>">
                    <?php echo htmlspecialchars($doctor['full_name'] . " (" . $doctor['specialty'] . ")"); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        Date: <input type="date" name="appointment_date" required><br>
        Time: <input type="time" name="appointment_time" required><br>

        <input type="submit" name="BtnSubmit" value="Book Appointment"><br>
        <a href="patient_dashboard.php">Back to Dashboard</a>
    </form>
</body>
</html>



