<?php
include 'db.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'user')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registration successful'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
     <?php include 'link.php'; ?>
     <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 img-login">
                 <img src="src/index/loginpic.png" alt=""  class="img-fluid">
            </div>

            <div class="col-md-6  hero">
                 <h1 class=" h-login">Sign up</h1>
    <form method="POST">
        <div class="mb-3">
            <input type="text" name="username" class="form-control input-sign" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control input-sign" placeholder="Password" required>
        </div>
        <button name="register" class="mt-3">Register</button>
        <div class="p-account">
        <p class="mt-2"><a href="index.php" class="register">Back to Login</a></p>
        </div>
    </form>
            </div>
        </div>
    </div>

   

</body>
</html>
