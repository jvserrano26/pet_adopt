<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

// Handle Approve or Reject actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'approve') {
        mysqli_query($conn, "UPDATE adoptions SET status='Approved' WHERE id=$id");
    } elseif ($action === 'reject') {
        mysqli_query($conn, "UPDATE adoptions SET status='Rejected' WHERE id=$id");
    }

    echo "<script>window.location='manage_adoptions.php';</script>";
    exit;
}

/* -------------------------
      PAGINATION + FILTER
-------------------------- */

$limit = 5; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

// Filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$where = "";
if ($filter == "approved") $where = "WHERE a.status='Approved'";
elseif ($filter == "rejected") $where = "WHERE a.status='Rejected'";
elseif ($filter == "pending") $where = "WHERE a.status='Pending'";

// Count records for pagination
$countQuery = "
    SELECT COUNT(*) AS total
    FROM adoptions a
    $where
";
$countResult = mysqli_query($conn, $countQuery);
$totalRecords = mysqli_fetch_assoc($countResult)['total'];

$totalPages = ceil($totalRecords / $limit);

// Fetch adoption records with LIMIT
$query = "
    SELECT a.*, p.name AS pet_name, p.type, u.username
    FROM adoptions a
    JOIN pets p ON a.pet_id = p.id
    JOIN users u ON a.user_id = u.id
    $where
    ORDER BY a.created_at DESC
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Adoptions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manage Adoption Applications</h3>
        <div>
            <a href="admin_dashboard.php" class="btn btn-secondary btn-sm">Back to Dashboard</a>
        </div>
    </div>

    <!-- FILTER BUTTONS -->
    <div class="mb-3">
        <a href="?filter=all" class="btn btn-outline-primary btn-sm <?= ($filter=='all')?'active':'' ?>">All</a>
        <a href="?filter=pending" class="btn btn-outline-warning btn-sm <?= ($filter=='pending')?'active':'' ?>">Pending</a>
        <a href="?filter=approved" class="btn btn-outline-success btn-sm <?= ($filter=='approved')?'active':'' ?>">Approved</a>
        <a href="?filter=rejected" class="btn btn-outline-danger btn-sm <?= ($filter=='rejected')?'active':'' ?>">Rejected</a>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white shadow-sm">
                <thead class="table-primary">
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Pet</th>
                        <th>Type</th>
                        <th>Applicant</th>
                        <th>Full Name</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Reason</th>
                        <th>Valid ID</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="text-center align-middle">
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['pet_name']) ?></td>
                        <td><?= ucfirst($row['type']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= htmlspecialchars($row['contact']) ?></td>
                        <td><?= htmlspecialchars($row['reason']) ?></td>

                        <!-- VIEW VALID ID BUTTON -->
                        <td>
                            <?php if (!empty($row['valid_id'])): ?>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewIDModal<?= $row['id'] ?>">
                                    View ID
                                </button>

                                <!-- MODAL -->
                                <div class="modal fade" id="viewIDModal<?= $row['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Uploaded Valid ID</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="uploads/ids/<?= $row['valid_id'] ?>" class="img-fluid rounded border" style="max-height:600px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">No ID</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($row['status'] == 'Pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php elseif ($row['status'] == 'Approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Rejected</span>
                            <?php endif; ?>
                        </td>

                        <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>

                        <td>
                            <?php if ($row['status'] == 'Pending'): ?>
                                <a href="?action=approve&id=<?= $row['id'] ?>" class="btn btn-success btn-sm mb-1">Approve</a>
                                <a href="?action=reject&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
                            <?php else: ?>
                                <em>No Action</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>

                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <nav>
            <ul class="pagination justify-content-center">

                <!-- Prev Page -->
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page-1 ?>&filter=<?= $filter ?>">Previous</a>
                </li>

                <!-- Page Numbers -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&filter=<?= $filter ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next Page -->
                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page+1 ?>&filter=<?= $filter ?>">Next</a>
                </li>

            </ul>
        </nav>

    <?php else: ?>
        <p class="text-center">No adoption applications found.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
