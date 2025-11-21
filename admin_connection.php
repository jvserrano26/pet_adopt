<?php
include 'db.php';
session_start();


// Only allow admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

/* ✅ ADD PET FUNCTIONALITY */
if (isset($_POST['add_pet'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $type = $_POST['type'];

    // Upload image
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $sql = "INSERT INTO pets (name, age, type, image) VALUES ('$name', '$age', '$type', '$image')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('✅ Pet added successfully!'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('❌ Error adding pet.');</script>";
    }
}
?>