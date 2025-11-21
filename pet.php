<?php include 'user_connection.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="css/pet.css">
    <title>Available Pets</title>


</head>

<body>
 <!-- navbar -->
       <div><?php include 'navbar.php'; ?></div> 

<section class="container mt-5">
    <h1 class="text-center mb-4 h-pet">Available Pets for <span>Adoption</span></h1>

    <!-- Filter Buttons -->
    <div class="text-center mb-4">
        <button class="btn btn-dark filter-btn me-2" data-filter="all">Show All</button>
        <button class="btn btn-primary filter-btn me-2" data-filter="dog">🐶 Dogs</button>
        <button class="btn btn-warning filter-btn" data-filter="cat">🐱 Cats</button>
    </div>

    <!-- Pets Display -->
    <div class="row" id="petContainer">

        <?php
        $pets = mysqli_query($conn,
            "SELECT * FROM pets
             WHERE id NOT IN (SELECT pet_id FROM adoptions WHERE status='Approved')
             ORDER BY type ASC"
        );

        if (mysqli_num_rows($pets) > 0) {
            while ($row = mysqli_fetch_assoc($pets)) {

                $pet_id = $row['id'];
                $type = strtolower($row['type']); // ✅ convert DB value to lowercase

                echo "
                <div class='col-md-3 col-sm-6 mb-4 pet-card' data-type='{$type}'>
                    <div class='card shadow'>
                        <img src='uploads/{$row['image']}' class='card-img-top' height='200' style='object-fit:cover;'>
                        <div class='card-body text-center'>
                            <h5 class='fw-bold'>{$row['name']}</h5>
                            <p class='mb-1'>Type: {$row['type']}</p>
                            <p>Age: {$row['age']}</p>

                            <button class='btn btn-success btn-sm'
                                data-bs-toggle='modal'
                                data-bs-target='#applyModal{$pet_id}'>Apply</button>
                        </div>
                    </div>
                </div>
                ";

                // Adoption Modal + Upload ID
                echo "
                <div class='modal fade' id='applyModal{$pet_id}' tabindex='-1'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header bg-success text-white'>
                                <h5 class='modal-title'>Adopt {$row['name']}</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                            </div>

                            <form action='user_connection.php' method='POST' enctype='multipart/form-data'>
                                <div class='modal-body'>

                                    <input type='hidden' name='pet_id' value='{$pet_id}'>

                                    <label class='fw-bold'>Full Name</label>
                                    <input type='text' name='full_name' class='form-control mb-2' required>

                                    <label class='fw-bold'>Address</label>
                                    <input type='text' name='address' class='form-control mb-2' required>

                                    <label class='fw-bold'>Contact Number</label>
                                    <input type='text' name='contact' class='form-control mb-2' required>

                                    <label class='fw-bold'>Reason for Adoption</label>
                                    <textarea name='reason' class='form-control mb-2' required></textarea>

                                    <label class='fw-bold'>Upload Valid ID</label>
                                    <input type='file' name='valid_id' class='form-control mb-2' required>

                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                    <button type='submit' name='apply_adoption' class='btn btn-success'>Submit Application</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "<h4 class='text-center text-muted'>No pets available for adoption.</h4>";
        }
        ?>
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
</section>

         <!--  footer -->
  <div><?php include 'footer.php'; ?></div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ✅ FILTER FUNCTION FIX
document.querySelectorAll(".filter-btn").forEach(button => {
    button.addEventListener("click", () => {
        const filter = button.getAttribute("data-filter").toLowerCase();

        document.querySelectorAll(".pet-card").forEach(card => {
            const type = card.getAttribute("data-type").toLowerCase();

            if (filter === "all" || type === filter) {
                card.style.display = "";  // restore default display
            } else {
                card.style.display = "none";
            }
        });
    });
});
</script>

</body>
</html>
