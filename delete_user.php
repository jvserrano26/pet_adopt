<?php
include 'admin_connection.php';

if(!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$id = intval($_GET['id']);

// Delete user's adoption records first
mysqli_query($conn, "DELETE FROM adoptions WHERE user_id='$id'");

// Then delete the user
mysqli_query($conn, "DELETE FROM users WHERE id='$id'");

header("Location: admin_dashboard.php");
exit;
