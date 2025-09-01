<?php
session_start();
include("../config/NewConnection.php");

// Check login and role
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../authentication/login.php");
    exit();
}

// Check valid appointment ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid appointment ID.");
}

$appointment_id = intval($_GET['id']);
$patient_id = $_SESSION['user_id'];

// Fetch appointment details & check ownership
$stmt = $conn->prepare("
    SELECT a.*, d.full_name AS doctor_name 
    FROM appointments a 
    JOIN doctors d ON a.doctor_id = d.id 
    WHERE a.id = ? AND a.patient_id = ?
");
$stmt->bind_param("ii", $appointment_id, $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Appointment not found or access denied.");
}

$appointment = $result->fetch_assoc();

$error = "";
$success = "";

// Check if a payment record already exists
$payment_stmt = $conn->prepare("SELECT * FROM payments WHERE appointment_id = ?");
$payment_stmt->bind_param("i", $appointment_id);
$payment_stmt->execute();
$payment_result = $payment_stmt->get_result();
$payment_exists = $payment_result->num_rows > 0;

// Get current payment status
$current_status = $payment_exists ? $payment_result->fetch_assoc()['payment_status'] : 'unpaid';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'] ?? '';
    $card_number = $_POST['card_number'] ?? '';
    $expiry = $_POST['expiry'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    // Validation
    if (!is_numeric($amount) || floatval($amount) <= 0) {
        $error = "Please enter a valid amount.";
    } elseif (!preg_match('/^\d{16}$/', $card_number)) {
        $error = "Invalid card number. Must be 16 digits.";
    } elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry)) {
        $error = "Invalid expiry date. Format MM/YY.";
    } elseif (!preg_match('/^\d{3}$/', $cvv)) {
        $error = "Invalid CVV. Must be 3 digits.";
    } else {
        $paid_at = date("Y-m-d H:i:s");

        if ($payment_exists) {
            // Update existing payment
            $update_stmt = $conn->prepare("UPDATE payments SET amount=?, payment_status='paid', paid_at=? WHERE appointment_id=?");
            $update_stmt->bind_param("dsi", $amount, $paid_at, $appointment_id);
            $success_msg = "Payment updated successfully!";
            $execute_success = $update_stmt->execute();
            $update_stmt->close();
        } else {
            // Insert new payment
            $insert_stmt = $conn->prepare("INSERT INTO payments (appointment_id, amount, payment_status, paid_at) VALUES (?, ?, 'paid', ?)");
            $insert_stmt->bind_param("ids", $appointment_id, $amount, $paid_at);
            $success_msg = "Payment successful!";
            $execute_success = $insert_stmt->execute();
            $insert_stmt->close();
        }

        $success = $execute_success ? $success_msg : "Payment failed. Please try again.";
    }
}

$payment_stmt->close();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Make Payment</title>
    <link rel="stylesheet" href="make_payment.css">
</head>
<body>
    <h2>Make Payment for Appointment #<?= htmlspecialchars($appointment['id']); ?></h2>
    <p><strong>Doctor:</strong> <?= htmlspecialchars($appointment['doctor_name']); ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($appointment['appointment_date']); ?></p>
    <p><strong>Time:</strong> <?= htmlspecialchars($appointment['appointment_time']); ?></p>
    <p><strong>Current Payment Status:</strong> <?= htmlspecialchars(ucwords($current_status)); ?></p>

    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
        <a href="payment_list.php">Back to Payment List</a><br>
        <a href="patient_dashboard.php">Back to Dashboard</a>
    <?php else: ?>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="post" novalidate>
            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" step="0.01" min="0" required value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>">

            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" maxlength="16" pattern="\d{16}" required value="<?= htmlspecialchars($_POST['card_number'] ?? '') ?>">

            <label for="expiry">Expiry Date (MM/YY):</label>
            <input type="text" id="expiry" name="expiry" pattern="(0[1-9]|1[0-2])\/\d{2}" required value="<?= htmlspecialchars($_POST['expiry'] ?? '') ?>">

            <label for="cvv">CVV:</label>
            <input type="number" id="cvv" name="cvv" min="100" max="999" required value="<?= htmlspecialchars($_POST['cvv'] ?? '') ?>">

            <button type="submit">Pay Now</button>
        </form>
        <a href="payment_list.php" class="btn cancel">Cancel and go back</a>
    <?php endif; ?>
</body>
</html>


