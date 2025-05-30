<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';

// Handle approve/reject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loan_id = $_POST['loan_id'];
    $action = $_POST['action'];
    $status = $action === 'approve' ? 'Approved' : 'Rejected';

    $stmt = $conn->prepare("UPDATE loans SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $loan_id);
    $stmt->execute();
}

// Get all loan applications with user info
$query = "SELECT loans.*, users.username FROM loans JOIN users ON loans.user_id = users.id ORDER BY loans.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | Loan Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .container {
            max-width: 1100px;
            margin-top: 60px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
        }

        .status-pill {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            color: white;
        }

        .status-pending { background-color: #ffc107; }
        .status-approved { background-color: #28a745; }
        .status-rejected { background-color: #dc3545; }

        .action-btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h3 class="mb-4">🛠 Admin Panel – Loan Requests</h3>

    <?php if ($result->num_rows > 0): ?>
        <div class="card p-4">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Purpose</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Applied On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= number_format($row['loan_amount']) ?></td>
                            <td><?= htmlspecialchars($row['purpose']) ?></td>
                            <td><?= $row['duration'] ?> mo</td>
                            <td>
                                <?php
                                    $status = strtolower($row['status']);
                                    $class = match ($status) {
                                        'pending' => 'status-pending',
                                        'approved' => 'status-approved',
                                        'rejected' => 'status-rejected',
                                        default => 'badge-secondary',
                                    };
                                ?>
                                <span class="status-pill <?= $class ?>"><?= ucfirst($status) ?></span>
                            </td>
                            <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                            <td>
                                <?php if ($status === 'pending'): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="loan_id" value="<?= $row['id'] ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success action-btn">Approve</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">No Action</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No loan applications found.</div>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary mt-4">← Back to Dashboard</a>
</div>

</body>
</html>
