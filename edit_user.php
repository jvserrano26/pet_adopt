<?php
include 'admin_connection.php';

if(!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$id = intval($_GET['id']);
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($user_query);

if(isset($_POST['update_user'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username=?, role=? WHERE id=?");
    $stmt->bind_param("ssi", $username, $role, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
<div class="container">
    <h3>Edit User</h3>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" class="form-control mb-2" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label>Role:</label>
        <select name="role" class="form-select mb-2" required>
            <option value="user" <?php if($user['role']=='user') echo 'selected'; ?>>User</option>
            <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
        </select>

        <button type="submit" name="update_user" class="btn btn-success">Update</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
