<?php include 'user_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - Pet Adoption</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/user_dashboard.css">
</head>
<body >
        <!-- navbar -->
       <div><?php include 'navbar.php'; ?></div> 

    <section id="hero">
        <div class="container">
            <div class="row">
                <div class="col-md-6 hero" style="margin:100px 0 0 0;">
                    <h1 class="h1-hero">Find Your <span class="span-h1">Perfect</span> Friend</h1>
                    <p class="p-hero">At Pet Haven, every animal deserves a loving home. Adopt a friend today and change a life, including your own. Your compassion can give them the fresh start they’ve been waiting for.</p>
                    <button class="btn-hero"><a href="#pet" style="text-decoration:none;color:white">Adopt now</a></button>
                </div>
               
                 <div class="col-md-6">
                    <img src="src/user_dashboard/hero-img.png" alt="" class="img-fluid">
                 </div>
                  
            </div>
        </div>
    </section>

    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="src/user_dashboard/about-img.png" alt="" class="img-fluid">
                </div>
                 <div class="col-md-6 about-text"  style="margin:80px 0 0 0;">
                    <h1 class="about-h1">About <span>us </span></h1>
                    <p class="about-p">At Pet Haven Adoption Center, we are dedicated to giving abandoned and homeless animals a fresh start. We care for each pet with love and compassion until they find a safe and permanent home, where they can feel cherished and valued. Here, every adoption is more than a match,it’s a new beginning.</p>
                    <button class="btn-about"><a href="about.php" style="text-decoration:none;color:white">Learn more</a></button>
                 </div>
            </div>
        </div>
    </section>

<div class="container-fluid mt-4 ">

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

    <section id="pet">
    <!-- 🐶 Available Pets -->
    <h1 class="pet-h1">Available Pets for <span>Adoption</span></h1>
    <div class="row">

        <?php
        // ✅ Show only pets NOT yet approved
        $result = mysqli_query($conn, "
    SELECT * FROM pets 
    WHERE id NOT IN (SELECT pet_id FROM adoptions WHERE status='Approved')
    ORDER BY id DESC
    LIMIT 4
");


        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $pet_id = $row['id'];

                echo "
                <div class='col-md-3 mb-3 pet'>
                    <div class='card shadow-sm pet-card' data-bs-toggle='modal' data-bs-target='#applyModal{$pet_id}' style='cursor:pointer;'>
                        <img src='uploads/{$row['image']}' class='card-img-top' height='200'>
                        <div class='card-body text-center'>
                            <h5 class='card-title'>{$row['name']}</h5>
                            <p>Age: {$row['age']}</p>
                            <p>Type: {$row['type']}</p>
                        </div>
                    </div>
                </div>";

                // ✅ Modal Form (Apply Adoption)
                echo "
                <div class='modal fade' id='applyModal{$pet_id}' tabindex='-1'>
                  <div class='modal-dialog modal-dialog-centered'>
                    <div class='modal-content'>
                      <form method='POST' enctype='multipart/form-data'>
                        <div class='modal-header bg-primary text-white'>
                          <h5 class='modal-title'>Apply for Adoption</h5>
                          <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                        </div>

                        <div class='modal-body'>
                            <div class='text-center mb-3'>
                                <img src='uploads/{$row['image']}' width='200' height='200' class='rounded'>
                                <h5 class='mt-2'>{$row['name']}</h5>
                                <p>Type: {$row['type']} | Age: {$row['age']}</p>
                            </div>

                            <input type='hidden' name='pet_id' value='{$pet_id}'>

                            <div class='mb-3'>
                                <label>Full Name:</label>
                                <input type='text' name='full_name' class='form-control' required>
                            </div>

                            <div class='mb-3'>
                                <label>Address:</label>
                                <textarea name='address' class='form-control' required></textarea>
                            </div>

                            <div class='mb-3'>
                                <label>Contact Number:</label>
                                <input type='text' name='contact' class='form-control' required>
                            </div>

                            <div class='mb-3'>
                                <label>Reason for Adopting:</label>
                                <textarea name='reason' class='form-control' required></textarea>
                            </div>

                            <div class='mb-3'>
                                <label>Upload Valid Government ID:</label>
                                <input type='file' name='valid_id' class='form-control' accept='image/*,application/pdf' required>
                            </div>
                        </div>

                        <div class='modal-footer'>
                          <button type='submit' name='apply_adoption' class='btn btn-success'>Submit Application</button>
                          <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>";
            }
        } else {
            echo "<p class='text-center'>No pets available for adoption right now.</p>";
        }
        ?>

    </div>
    <div class="text-center mt-3 mb-2 ">
    <button class="btn-pet"><a href="pet.php"  style="text-decoration:none;color:white">Show more</a></button>
    </div>
    </section>
</div>

  <!--  footer -->
  <div><?php include 'footer.php'; ?></div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
