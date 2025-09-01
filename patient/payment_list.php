<?php
session_start();
include("../config/NewConnection.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../authentication/login.php");
    exit();
}

// Use patient ID from session
$patient_id = $_SESSION['user_id'];

// Fetch unpaid or late paid appointments with amount from payments table
$sql = "
    SELECT a.id, a.appointment_date, a.appointment_time, d.full_name AS doctor_name, 
           COALESCE(p.payment_status, 'unpaid') AS payment_status,
           COALESCE(p.amount, 0.00) AS amount
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    LEFT JOIN payments p ON a.id = p.appointment_id
    WHERE a.patient_id = ?
      AND COALESCE(p.payment_status, 'unpaid') IN ('unpaid', 'late paid')
    ORDER BY a.id ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment List</title>
    <link rel="stylesheet" href="payment_list.css">
</head>
<body>
    <h2>Pending Payments</h2>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['doctor_name']); ?></td>
                    <td><?= htmlspecialchars($row['appointment_date']); ?></td>
                    <td><?= htmlspecialchars($row['appointment_time']); ?></td>
                    <td><?= number_format($row['amount'], 2); ?></td>
                    <td><?= htmlspecialchars(ucwords($row['payment_status'])); ?></td>
                    <td><a href="make_payment.php?id=<?= urlencode($row['id']); ?>">Pay Now</a></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending payments found.</p>
    <?php endif; ?>

    <p>
        <a href="patient_dashboard.php" class="back-btn">Back to Dashboard</a>
    </p>
</body>
</html>
