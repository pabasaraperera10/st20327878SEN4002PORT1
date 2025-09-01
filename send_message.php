<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Save messages to a text file
    $file = 'messages.txt';
    $data = "Name: $name\nEmail: $email\nMessage: $message\n----------------------\n";
    if(file_put_contents($file, $data, FILE_APPEND)) {
        echo "<script>alert('Your message has been sent successfully!'); window.location.href='contact.php';</script>";
    } else {
        echo "<script>alert('Failed to send your message. Please try again.'); window.location.href='contact.php';</script>";
    }
} else {
    // Redirect if someone tries to access directly
    header("Location: contact.php");
    exit();
}
?>
