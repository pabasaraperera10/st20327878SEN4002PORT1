<?php
session_start();
include("../config/NewConnection.php");

// Admin-only access
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../authentication/login.php");
    exit();
}

// Initialize message
$msg = "";

// Add Doctor
if (isset($_POST['add'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $full_name = trim($_POST['full_name']);
    $specialty = trim($_POST['specialty']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Check if username exists
    $check_user = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_user->bind_param("s", $username);
    $check_user->execute();
    $check_user->store_result();

    if ($check_user->num_rows > 0) {
        $msg = " Username already exists.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt1 = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'doctor')");
        $stmt1->bind_param("ss", $username, $hashed_password);

        if ($stmt1->execute()) {
            $user_id = $conn->insert_id;

            $stmt2 = $conn->prepare("INSERT INTO doctors (user_id, full_name, specialty, phone, email) VALUES (?, ?, ?, ?, ?)");
            $stmt2->bind_param("issss", $user_id, $full_name, $specialty, $phone, $email);

            if ($stmt2->execute()) {
                $msg = "Doctor added successfully!";
            } else {
                $conn->query("DELETE FROM users WHERE id = $user_id");
                $msg = "Error inserting doctor details.";
            }
        } else {
            $msg = "Error inserting user.";
        }
    }
}

// Edit Doctor
if (isset($_POST['edit'])) {
    $user_id = intval($_POST['user_id']);
    $full_name = trim($_POST['full_name']);
    $specialty = trim($_POST['specialty']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); 

    $check_user = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $check_user->bind_param("si", $username, $user_id);
    $check_user->execute();
    $check_user->store_result();

    if ($check_user->num_rows > 0) {
        $msg = "Username already exists for another user.";
    } else {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt1 = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
            $stmt1->bind_param("ssi", $username, $hashed_password, $user_id);
        } else {
            $stmt1 = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt1->bind_param("si", $username, $user_id);
        }

        if ($stmt1->execute()) {
            $stmt2 = $conn->prepare("UPDATE doctors SET full_name = ?, specialty = ?, phone = ?, email = ? WHERE user_id = ?");
            $stmt2->bind_param("ssssi", $full_name, $specialty, $phone, $email, $user_id);

            if ($stmt2->execute()) {
                $msg = "Doctor updated successfully!";
            } else {
                $msg = "Error updating doctor details.";
            }
        } else {
            $msg = "Error updating user.";
        }
    }
}

// Delete Doctor
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM doctors WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $stmt2 = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();

    $msg = "🗑️ Doctor deleted successfully!";
}

// Edit Form population
$edit_doctor = null;
if (isset($_GET['edit'])) {
    $user_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT d.*, u.username FROM doctors d INNER JOIN users u ON d.user_id = u.id WHERE d.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_doctor = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Doctors</title>
    <link rel="stylesheet" href="manage_doctors.css">
</head>
<body>
    <h2>Manage Doctors (Admin)</h2>
    <a href="admin_dashboard.php">Back to Dashboard</a> |
    <a href="../authentication/logout.php">Logout</a>
    <hr>

    <?php 
    if (!empty($msg)) {
        $class = strpos($msg, "❌") === 0 ? "error" : "success";
        echo "<div class='msg $class'>$msg</div>";
    }
    ?>

    <?php if ($edit_doctor): ?>
        <h3>Edit Doctor</h3>
        <form method="post">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($edit_doctor['user_id']) ?>">
            <label>Full Name:</label><br>
            <input type="text" name="full_name" value="<?= htmlspecialchars($edit_doctor['full_name']) ?>" required><br><br>
            <label>Specialty:</label><br>
            <input type="text" name="specialty" value="<?= htmlspecialchars($edit_doctor['specialty']) ?>" required><br><br>
            <label>Phone:</label><br>
            <input type="text" name="phone" value="<?= htmlspecialchars($edit_doctor['phone']) ?>" required><br><br>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($edit_doctor['email']) ?>" required><br><br>
            <label>Username:</label><br>
            <input type="text" name="username" value="<?= htmlspecialchars($edit_doctor['username']) ?>" required><br><br>
            <label>Password (leave blank to keep current):</label><br>
            <input type="password" name="password"><br><br>
            <input type="submit" name="edit" value="Update Doctor">
            <a href="manage_doctors.php">Cancel</a>
        </form>
    <?php else: ?>
        <h3>Add New Doctor</h3>
        <form method="post">
            <label>Full Name:</label><br>
            <input type="text" name="full_name" required><br><br>
            <label>Specialty:</label><br>
            <input type="text" name="specialty" required><br><br>
            <label>Phone:</label><br>
            <input type="text" name="phone" required><br><br>
            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>
            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>
            <input type="submit" name="add" value="Add Doctor">
        </form>
    <?php endif; ?>

    <hr>

    <h3>All Doctors</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Specialty</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Username</th>
            <th>Action</th>
        </tr>

        <?php
        // Fetch all doctors in ascending order by ID
        $sql = "SELECT d.*, u.username, u.id AS user_id FROM doctors d
                INNER JOIN users u ON d.user_id = u.id
                ORDER BY d.id ASC";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>".htmlspecialchars($row['full_name'])."</td>
                <td>".htmlspecialchars($row['specialty'])."</td>
                <td>".htmlspecialchars($row['phone'])."</td>
                <td>".htmlspecialchars($row['email'])."</td>
                <td>".htmlspecialchars($row['username'])."</td>
                <td>
                    <a href='manage_doctors.php?edit={$row['user_id']}'>Edit</a> | 
                    <a href='manage_doctors.php?delete={$row['user_id']}' onclick=\"return confirm('Delete this doctor?');\">Delete</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</body>
</html>


