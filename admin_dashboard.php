<?php
include 'admin_connection.php';

// Handle Add Pet
if(isset($_POST['add_pet'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = intval($_POST['age']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $img_name = time().'_'.$_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];

    // Check if this pet already exists
    $check = mysqli_query($conn, "SELECT * FROM pets WHERE name='$name' AND age='$age' AND type='$type'");
    if(mysqli_num_rows($check) == 0){
        move_uploaded_file($tmp_name, "uploads/".$img_name);
        mysqli_query($conn, "INSERT INTO pets (name, age, type, image) VALUES ('$name','$age','$type','$img_name')");
    } else {
        echo "<script>alert('This pet already exists!');</script>";
    }
    header("Location: admin_dashboard.php");
    exit;
}

// Handle Edit Pet
if(isset($_POST['edit_pet'])){
    $id = intval($_POST['pet_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = intval($_POST['age']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);

    // Check if another pet with same details exists
    $check = mysqli_query($conn, "SELECT * FROM pets WHERE name='$name' AND age='$age' AND type='$type' AND id != '$id'");
    if(mysqli_num_rows($check) == 0){
        if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
            $img_name = time().'_'.$_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            move_uploaded_file($tmp_name, "uploads/".$img_name);
            $update_sql = "UPDATE pets SET name='$name', age='$age', type='$type', image='$img_name' WHERE id='$id'";
        } else {
            $update_sql = "UPDATE pets SET name='$name', age='$age', type='$type' WHERE id='$id'";
        }
        mysqli_query($conn, $update_sql);
    } else {
        echo "<script>alert('Another pet with the same details already exists!');</script>";
    }
    header("Location: admin_dashboard.php");
    exit;
}

// Handle Delete Pet
if(isset($_GET['delete_id'])){
    $id = intval($_GET['delete_id']);
    $check = mysqli_query($conn, "SELECT * FROM adoptions WHERE pet_id='$id'");
    if(mysqli_num_rows($check) == 0){
        mysqli_query($conn, "DELETE FROM pets WHERE id='$id'");
    }
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - Pet Adoption</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin_dashboard.css">
</head>
<body class="bg-light">

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="name-logo">Pet <span class="h-spam">Haven</span></h4>
    <a href="#" data-bs-toggle="modal" data-bs-target="#userModal">User Accounts</a>
    <a href="#" data-bs-toggle="modal" data-bs-target="#messageModal">View Messages</a>
    <a href="manage_adoptions.php">Manage Adoptions</a>
    <a href="#" data-bs-toggle="modal" data-bs-target="#addPetModal">+ Add Pet</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Main Content -->
<div class="content">


<h4 class="mt-4 h-petlist">Pet List</h4>
<hr class="hr">
<div class="mb-3">
  <div class="dropdown">
    <button class="btn dropdown-toggle btn-filter" type="button" id="petFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
      Filter Pets
    </button>
    <ul class="dropdown-menu" aria-labelledby="petFilterDropdown">
      <li><a class="dropdown-item" href="admin_dashboard.php">All Pets</a></li>
      <li><a class="dropdown-item" href="admin_dashboard.php?filter=Dog">Dogs</a></li>
      <li><a class="dropdown-item" href="admin_dashboard.php?filter=Cat">Cats</a></li>
      <li><a class="dropdown-item" href="admin_dashboard.php?filter=Adopted">Adopted</a></li>
      <li><a class="dropdown-item" href="admin_dashboard.php?filter=Available">Available</a></li>
    </ul>
  </div>
</div>

<div class="row">
<?php
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

if ($filter == "Dog") {
    $sql = "SELECT * FROM pets WHERE type='Dog' ORDER BY id DESC";
} elseif ($filter == "Cat") {
    $sql = "SELECT * FROM pets WHERE type='Cat' ORDER BY id DESC";
} elseif ($filter == "Adopted") {
    $sql = "SELECT pets.* FROM pets 
            INNER JOIN adoptions ON pets.id = adoptions.pet_id
            WHERE adoptions.status = 'Approved'
            ORDER BY pets.id DESC";
} elseif ($filter == "Available") {
    $sql = "SELECT * FROM pets 
            WHERE id NOT IN (SELECT pet_id FROM adoptions)
            ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM pets ORDER BY id DESC";
}

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        $pet_id = $row['id'];
        $has_adoption = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM adoptions WHERE pet_id='$pet_id'")) > 0;
        $approved_badge = $has_adoption ? "<span class='badge bg-success position-absolute top-0 start-0 m-2'>Adopted</span>" : "";

        echo "<div class='col-md-3 mb-4'>
                <div class='card position-relative shadow-sm'>
                    $approved_badge
                    <img src='uploads/{$row['image']}' class='card-img-top' style='height:200px; object-fit:cover;'>
                    <div class='card-body'>
                        <h5>{$row['name']}</h5>
                        <p>Age: {$row['age']}</p>
                        ";

        if(!$has_adoption){
            echo "<button class='btn  btn-sm m-1 btn-edit' data-bs-toggle='modal' data-bs-target='#editPetModal{$pet_id}'>Edit</button>";
            echo "<a href='admin_dashboard.php?delete_id={$pet_id}' class='btn btn-sm m-1 btn-delete' onclick=\"return confirm('Are you sure?');\">Delete</a>";
        }

        echo "</div></div></div>";

        // Edit Pet Modal
        if(!$has_adoption){
            echo "
            <div class='modal fade' id='editPetModal{$pet_id}' tabindex='-1'>
              <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content'>
                  <form method='POST' enctype='multipart/form-data'>
                    <div class='modal-header'>
                      <h5 class='modal-title'>Edit Pet</h5>
                      <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                    </div>
                    <div class='modal-body'>
                        <input type='hidden' name='pet_id' value='{$pet_id}'>
                        <label>Name:</label>
                        <input type='text' name='name' class='form-control mb-2' value='{$row['name']}' required>
                        <label>Age:</label>
                        <input type='number' name='age' class='form-control mb-2' value='{$row['age']}' required>
                        <label>Type:</label>
                        <select name='type' class='form-select mb-2' required>
                            <option value='Dog' ".($row['type']=='Dog'?'selected':'').">Dog</option>
                            <option value='Cat' ".($row['type']=='Cat'?'selected':'').">Cat</option>
                        </select>
                        <label>Image (leave blank to keep current):</label>
                        <input type='file' name='image' class='form-control'>
                    </div>
                    <div class='modal-footer'>
                      <button type='submit' name='edit_pet' class='btn btn-success'>Save Changes</button>
                      <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            ";
        }
    }
} else {
    echo "<p>No pets found for this filter.</p>";
}
?>
</div>
</div>

<!-- User Accounts Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header user-modal">
        <h5 class="modal-title">👤 Registered User Accounts</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php
        $users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
        if(mysqli_num_rows($users) > 0){
            echo "<table class='table table-bordered'>
                    <thead><tr><th>ID</th><th>Username</th><th>Role</th><th>Actions</th></tr></thead><tbody>";
            while($row = mysqli_fetch_assoc($users)){
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['role']}</td>
                        <td>
                            <a href='edit_user.php?id={$row['id']}' class='btn btn-sm btn-edit'>Edit</a>
                            <a href='delete_user.php?id={$row['id']}' class='btn btn-sm btn-delete' onclick=\"return confirm('Are you sure?');\">Delete</a>
                        </td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No user accounts found.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</div>

<!-- Messages Modal -->
<div class="modal fade" id="messageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header message-modal">
        <h5 class="modal-title">📩 User Messages</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php
        $messages = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY id DESC");
        if(mysqli_num_rows($messages) > 0){
            echo "<table class='table table-bordered'>
                    <thead><tr><th>Name</th><th>Email</th><th>Message</th><th>Date</th></tr></thead><tbody>";
            while($msg = mysqli_fetch_assoc($messages)){
                echo "<tr>
                        <td>{$msg['name']}</td>
                        <td>{$msg['email']}</td>
                        <td>{$msg['message']}</td>
                        <td>{$msg['date_sent']}</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No messages found.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</div>

<!-- Add Pet Modal -->
<div class="modal fade" id="addPetModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data">
        <div class="modal-header add-modal">
          <h5 class="modal-title">Add New Pet</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <label>Name:</label>
            <input type="text" name="name" class="form-control mb-2" required>
            <label>Age:</label>
            <input type="number" name="age" class="form-control mb-2" required>
            <label>Type:</label>
            <select name="type" class="form-select mb-2" required>
                <option value="">Select Type</option>
                <option value="Dog">Dog</option>
                <option value="Cat">Cat</option>
            </select>
            <label>Picture:</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_pet" class="btn btn-success">Add Pet</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
