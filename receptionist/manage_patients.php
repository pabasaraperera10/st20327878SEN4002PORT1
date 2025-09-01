<?php
session_start();
include("../config/NewConnection.php");

// Only for receptionists
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'receptionist'){
    header("Location: ../authentication/login.php");
    exit();
}

// --- ADD Patient & Appointment ---
if(isset($_POST['add_patient'])){
    $name = $_POST['full_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    // Insert patient
    $stmt = $conn->prepare("INSERT INTO patients (full_name, age, gender, phone, email, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $name, $age, $gender, $phone, $email, $address);
    $stmt->execute();
    $patient_id = $stmt->insert_id;
    $stmt->close();

    // Insert appointment (pending, doctor_id NULL)
    $stmt2 = $conn->prepare("INSERT INTO appointments (patient_id, patient_name, patient_age, appointment_date, appointment_time, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
    $stmt2->bind_param("isiss", $patient_id, $name, $age, $appointment_date, $appointment_time);
    $stmt2->execute();
    $stmt2->close();

    header("Location: manage_patients.php?msg=Patient added and appointment created successfully");
    exit();
}

// --- EDIT Patient ---
if(isset($_POST['edit_patient'])){
    $id = $_POST['id'];
    $name = $_POST['full_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE patients SET full_name=?, age=?, gender=?, phone=?, email=?, address=? WHERE id=?");
    $stmt->bind_param("sissssi", $name, $age, $gender, $phone, $email, $address, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_patients.php?msg=Patient updated successfully");
    exit();
}

// --- DELETE Patient ---
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    // Delete related appointments first
    $stmt1 = $conn->prepare("DELETE FROM appointments WHERE patient_id=?");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();
    $stmt1->close();

    // Delete patient
    $stmt2 = $conn->prepare("DELETE FROM patients WHERE id=?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $stmt2->close();

    header("Location: manage_patients.php?msg=Patient and related appointments deleted successfully");
    exit();
}

// --- CONFIRM APPOINTMENT (assign doctor) ---
if(isset($_POST['confirm_appointment'])){
    $appointment_id = $_POST['appointment_id'];
    $doctor_id = $_POST['doctor_id'];

    $stmt = $conn->prepare("UPDATE appointments SET doctor_id=?, status='confirmed' WHERE id=?");
    $stmt->bind_param("ii", $doctor_id, $appointment_id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_patients.php?msg=Appointment confirmed and doctor assigned");
    exit();
}

// Fetch all patients
$patients = $conn->query("SELECT * FROM patients");

// Fetch all appointments
$appointments = $conn->query("
    SELECT a.id, a.patient_name, a.patient_age, a.appointment_date, a.appointment_time, a.status,
           d.id AS doctor_id, d.full_name AS doctor_name, d.specialty
    FROM appointments a
    LEFT JOIN doctors d ON a.doctor_id = d.id
    ORDER BY a.status DESC, a.appointment_date, a.appointment_time
");

// Fetch doctors once for dropdown
$doctors = $conn->query("SELECT id, full_name, specialty FROM doctors");
$doctor_list = [];
while($d = $doctors->fetch_assoc()){
    $doctor_list[] = $d;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Receptionist - Manage Patients & Appointments</title>
    <link rel="stylesheet" href="manage_patients.css">
</head>
<body>
<h2>Receptionist Dashboard</h2>

<?php if(isset($_GET['msg'])): ?>
<p style="color:green;"><?php echo htmlspecialchars($_GET['msg']); ?></p>
<?php endif; ?>

<h3>Add New Patient & Request Appointment</h3>
<form method="post">
    Name: <input type="text" name="full_name" required><br>
    Age: <input type="number" name="age" required><br>
    Gender:
    <select name="gender" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select><br>
    Phone: <input type="text" name="phone" required><br>
    Email: <input type="email" name="email" required><br>
    Address: <input type="text" name="address" required><br>
    Appointment Date: <input type="date" name="appointment_date" required><br>
    Appointment Time: <input type="time" name="appointment_time" required><br>
    <button type="submit" name="add_patient">Add Patient & Create Appointment</button>
</form>

<h3>Existing Patients</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>Name</th><th>Age</th><th>Gender</th><th>Phone</th><th>Email</th><th>Address</th><th>Actions</th>
    </tr>
    <?php while($p = $patients->fetch_assoc()): ?>
    <tr>
        <form method="post">
            <td><input type="text" name="full_name" value="<?= htmlspecialchars($p['full_name']); ?>"></td>
            <td><input type="number" name="age" value="<?= htmlspecialchars($p['age']); ?>"></td>
            <td>
                <select name="gender">
                    <option value="Male" <?= $p['gender']=='Male'?'selected':''; ?>>Male</option>
                    <option value="Female" <?= $p['gender']=='Female'?'selected':''; ?>>Female</option>
                </select>
            </td>
            <td><input type="text" name="phone" value="<?= htmlspecialchars($p['phone']); ?>"></td>
            <td><input type="email" name="email" value="<?= htmlspecialchars($p['email']); ?>"></td>
            <td><input type="text" name="address" value="<?= htmlspecialchars($p['address']); ?>"></td>
            <td>
                <input type="hidden" name="id" value="<?= $p['id']; ?>">
                <button type="submit" name="edit_patient">Save</button>
                <a href="?delete=<?= $p['id']; ?>" onclick="return confirm('Delete this patient and their appointments?')">Delete</a>
            </td>
        </form>
    </tr>
    <?php endwhile; ?>
</table>

<h3>Pending Appointments</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>Patient</th><th>Age</th><th>Doctor</th><th>Specialty</th><th>Date</th><th>Time</th><th>Status</th><th>Action</th>
    </tr>
    <?php while($a = $appointments->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($a['patient_name']); ?></td>
        <td><?= htmlspecialchars($a['patient_age']); ?></td>
        <td><?= htmlspecialchars($a['doctor_name'] ?? 'Not assigned'); ?></td>
        <td><?= htmlspecialchars($a['specialty'] ?? ''); ?></td>
        <td><?= htmlspecialchars($a['appointment_date']); ?></td>
        <td><?= htmlspecialchars($a['appointment_time']); ?></td>
        <td><?= htmlspecialchars($a['status']); ?></td>
        <td>
            <?php if($a['status']=='pending'): ?>
            <form method="post" style="margin:0;">
                <input type="hidden" name="appointment_id" value="<?= $a['id'] ?>">
                <select name="doctor_id" required>
                    <option value="">-- Select Doctor --</option>
                    <?php foreach($doctor_list as $doc): ?>
                        <option value="<?= $doc['id'] ?>"><?= htmlspecialchars($doc['full_name'] . " (" . $doc['specialty'] . ")") ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="confirm_appointment">Confirm</button>
            </form>
            <?php else: ?>
                Confirmed
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<br>
<a href="receptionist_dashboard.php">Back to Dashboard</a>
</body>
</html>


