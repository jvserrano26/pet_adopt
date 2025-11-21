<?php
include 'user_connection.php'
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact Us | Pet Haven</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">

<!-- Navbar -->
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="card shadow p-4 col-md-6 mx-auto">
        <h3 class="text-center mb-3">📩 Contact Us</h3>

        <form method="POST">
            <label><strong>Your Name</strong></label>
            <input type="text" name="name" class="form-control mb-3" required>

            <label><strong>Email</strong></label>
            <input type="email" name="email" class="form-control mb-3" required>

            <label><strong>Subject</strong></label>
            <input type="text" name="subject" class="form-control mb-3" required>

            <label><strong>Message</strong></label>
            <textarea name="message" class="form-control mb-3" rows="5" required></textarea>

            <button type="submit" name="send_contact" class="btn btn-primary w-100">Send Message</button>
        </form>
    </div>
</div>

      <!-- ✅ Notification Modal -->
    <div class="modal fade" id="notifModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Adoption Status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">

            <?php
            $notif_result = mysqli_query($conn, "
                SELECT a.*, p.name AS pet_name 
                FROM adoptions a 
                JOIN pets p ON a.pet_id = p.id 
                WHERE a.user_id='$user_id' 
                ORDER BY a.id DESC
            ");

            if (mysqli_num_rows($notif_result) > 0) {
                while ($notif = mysqli_fetch_assoc($notif_result)) {

                    $status_color = ($notif['status'] == 'Approved') ? 'text-success' :
                                   (($notif['status'] == 'Rejected') ? 'text-danger' : 'text-secondary');

                    echo "
                        <div class='border rounded p-2 mb-2'>
                            <strong>{$notif['pet_name']}</strong><br>
                            Status: <span class='{$status_color}'>{$notif['status']}</span><br>
                            Submitted Reason: " . htmlspecialchars($notif['reason']) . "<br>
                            <a href='uploads/ids/{$notif['valid_id']}' target='_blank'>View Uploaded ID</a><br><br>
                    ";

                    // ✅ Approval / Rejection Messages
                    if ($notif['status'] == 'Approved') {
                        echo "<p class='text-success'><strong>✅ Please present this approval screenshot at the Pet Haven Adoption Center to claim your pet.</strong></p>";
                    } elseif ($notif['status'] == 'Rejected') {
                        echo "<p class='text-danger'><strong>❗ We’re Sorry
Your adoption application didn’t meet our requirements at this time.
Thank you for caring about our animals, we hope you will consider applying again..</strong></p>";
                    }

                    echo "</div>";
                }
            } else {
                echo "<p class='text-center'>No notifications yet.</p>";
            }
            ?>

          </div>
        </div>
      </div>
    </div>

<!--  footer -->
  <div><?php include 'footer.php'; ?></div>
</body>
</html>
