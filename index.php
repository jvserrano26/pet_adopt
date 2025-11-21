<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit;
        } else {
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pet Adoption Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
     <?php include 'link.php'; ?>
     <link rel="stylesheet" href="css/index.css">
</head>
<style>
     
</style>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 img-login">
                <img src="src/index/loginpic.png" alt=""  class="img-fluid">
            </div>

            <div class="col-md-6 hero">
                 <h1 class="h-login">Log in</h1>
               <form method="POST">
                 <div class="mb-3">
                    <input type="text" name="username" class="form-control input-sign" placeholder="Username" required>
                 </div>
                 <div class="mb-3">
                    <input type="password" name="password" class="form-control  input-sign" placeholder="Password" required>
                 </div>
                 <div class="remember-me-container">
                    <input type="checkbox" id="rememberMe" name="rememberMe">
                    <label for="rememberMe">Remember me</label>
                 </div>
                  <button name="login">Login</button>
                  <div class="p-account">
                    <p class=" mt-2 "> <spam class="opacity-75">Dont't have account?</spam> <a href="register.php" class="register">Click here</a></p>
                  </div>
              </form>
            </div>
        </div>
    </div>

</body>
</html>
