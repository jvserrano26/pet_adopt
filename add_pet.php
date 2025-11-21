<?php
session_start();
include 'db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_POST['add_pet'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $type = $_POST['type'];
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO pets (name, age, type, image) VALUES ('$name', '$age', '$type', '$image')";
        mysqli_query($conn, $sql);
        echo "<script>alert('Pet added successfully!'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to upload image.');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Pet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-4 col-md-5">
    <h3>Add New Pet</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Age:</label>
            <input type="number" name="age" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Type:</label>
            <select name="type" class="form-select" required>
                <option value="">--Select--</option>
                <option value="cat">Cat</option>
                <option value="dog">Dog</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Picture:</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
        <button name="add_pet" class="btn btn-success w-100">Add Pet</button>
    </form>
</div>
</body>
</html>
