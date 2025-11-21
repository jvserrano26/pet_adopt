<?php
include 'admin_connection.php';

if(isset($_GET['id'])){
    $pet_id = intval($_GET['id']);

    // Check if pet has any adoption record
    $check = mysqli_query($conn, "SELECT * FROM adoptions WHERE pet_id='$pet_id'");
    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('Cannot delete this pet. It has adoption records!'); window.location='admin_dashboard.php';</script>";
        exit;
    }

    // Delete pet
    $del = mysqli_query($conn, "DELETE FROM pets WHERE id='$pet_id'");
    if($del){
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error deleting pet: " . mysqli_error($conn);
    }
} else {
    header("Location: admin_dashboard.php");
    exit;
}
?>
