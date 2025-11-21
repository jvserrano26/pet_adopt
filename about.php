<?php 
include 'user_connection.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/about.css">
    <title>Document</title>
</head>
<body>
    <!-- navbar -->
    <div><?php include 'navbar.php'; ?></div> 

   <section>
    <div class="container-fluid" style="margin:0;padding:0;">
        <div class="row">
            <div class="col-md-6 about-hero">
                <h1 class="about-hero-h1">What we <span>do</sapn></h1>
                <p class="about-hero-p">At Pet Haven, we rescue and care for animals in need. We provide a safe, loving environment until they find their forever home. When you adopt, you change a life and gain a loyal companion in return.</p>
            </div>
            <div class="col-md-6">
                 <img src="src/user_dashboard/shelterpage.png" class="img-fluid" alt="">
            </div>
        </div>
    </div>
   </section>
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

   <section id="mission-and-vision" class="text-center py-0">
    <h1 class="mission-h1">Mission & Vision</h1>

    <div class="btn-group mt-3">
        <button class="btn btn-outline-success active-tab" id="missionBtn">Mission</button>
        <button class="btn btn-outline-success" id="visionBtn">Vision</button>
    </div>

    <div class="container mt-4">
        <p id="missionText" class="mission-content show mis-vis-p">
            Our mission at Pet Haven is to rescue animals in need. We provide them with safety, proper care, and love. We help them heal and prepare for their forever home. We connect pets with families who will cherish them. Every rescue brings a new beginning and a second chance at life.
            
        </p>

        <p id="visionText" class="vision-content mis-vis-p">
            Our vision is to create a world where every animal has a loving home. We aim to inspire communities to choose adoption and compassion. No pet should be abandoned or forgotten. Through awareness, we guide people to make humane choices. Every rescue deserves a happy ending.
            
        </p>
    </div>
</section>

 <!-- footer -->
<div><?php include 'footer.php'; ?></div> 

<script>
document.addEventListener("DOMContentLoaded", function () {
    const missionBtn = document.getElementById("missionBtn");
    const visionBtn = document.getElementById("visionBtn");
    const missionText = document.getElementById("missionText");
    const visionText = document.getElementById("visionText");
    const missionVisionImage = document.getElementById("missionVisionImage");

    missionBtn.addEventListener("click", function () {
        missionBtn.classList.add("active-tab");
        visionBtn.classList.remove("active-tab");

        missionText.classList.add("show");
        visionText.classList.remove("show");

        missionVisionImage.classList.add("fade");
        setTimeout(() => {
            missionVisionImage.src = "src/user_dashboard/mission.png"; // change image here
            missionVisionImage.classList.remove("fade");
        }, 300);
    });

    visionBtn.addEventListener("click", function () {
        visionBtn.classList.add("active-tab");
        missionBtn.classList.remove("active-tab");

        visionText.classList.add("show");
        missionText.classList.remove("show");

        missionVisionImage.classList.add("fade");
        setTimeout(() => {
            missionVisionImage.src = "src/user_dashboard/vision.png"; // change image here
            missionVisionImage.classList.remove("fade");
        }, 300);
    });
});

</script>


</body>

</html>