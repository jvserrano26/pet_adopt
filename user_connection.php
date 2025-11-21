<?php
include 'db.php';
session_start();


// ✅ Only allow users (not admin)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit;
}

// Fetch current user info
$username = $_SESSION['username'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($user_query);
$user_id = $user['id'];

// ✅ Handle Adoption Application WITH ID UPLOAD
if (isset($_POST['apply_adoption'])) {
    $pet_id = $_POST['pet_id'];
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $reason = $_POST['reason'];

    // ✅ Valid ID Upload
    $valid_id = $_FILES['valid_id']['name'];
    $valid_id_tmp = $_FILES['valid_id']['tmp_name'];
    $valid_id_path = "uploads/ids/" . $valid_id;
    move_uploaded_file($valid_id_tmp, $valid_id_path);

    $sql = "INSERT INTO adoptions (pet_id, user_id, full_name, address, contact, reason, valid_id, status)
            VALUES ('$pet_id', '$user_id', '$full_name', '$address', '$contact', '$reason', '$valid_id', 'Pending')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Application submitted successfully!'); window.location='user_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error submitting application.');</script>";
    }
}


// ✅ CONTACT FORM SUBMISSION HANDLER
if (isset($_POST['sendMessage'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $query = "INSERT INTO contact_messages (name, email, subject, message)
              VALUES ('$name', '$email', '$subject', '$message')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('✅ Message Sent! Thank you for reaching out.');
                window.location.href='contact.php';
            </script>";
    } else {
        echo "<script>
                alert('❌ Error sending message.');
                window.location.href='contact.php';
            </script>";
    }
}
// ✅ Handle form submission
if (isset($_POST['send_contact'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $sql = "INSERT INTO contact_messages (name, email, subject, message, date_sent)
            VALUES ('$name', '$email', '$subject', '$message', NOW())";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('✅ Message sent successfully!'); window.location='contact.php';</script>";
    } else {
        echo "<script>alert('❌ Error sending message!');</script>";
    }
}

?>