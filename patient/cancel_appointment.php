<?php
session_start();

// Only logged-in patients can cancel
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../authentication/login.php");
    exit();
}

include("../config/NewConnection.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $appointment_id = intval($_GET['id']);
    $patient_id = $_SESSION['user_id']; // Use patient_id from session

    // Use prepared statement to safely delete appointment
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ? AND patient_id = ?");
    $stmt->bind_param("ii", $appointment_id, $patient_id);

    if ($stmt->execute()) {
        header("Location: view_appointments.php?msg=Appointment cancelled successfully");
        exit();
    } else {
        echo "Error cancelling appointment: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
$conn->close();
?>
