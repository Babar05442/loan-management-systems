<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM loans WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Loan Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .container {
            max-width: 1000px;
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
    </style>
</head>
<body>

<div class="container">
    <h3 class="mb-4">📋 My Loan Applications</h3>

    <?php if ($result->num_rows > 0): ?>
        <div class="card p-4">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount (PKR)</th>
                        <th>Purpose</th>
                        <th>Duration (months)</th>
                        <th>Status</th>
                        <th>Applied On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><?= number_format($row['loan_amount']) ?></td>
                            <td><?= htmlspecialchars($row['purpose']) ?></td>
                            <td><?= $row['duration'] ?></td>
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
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">You have not applied for any loans yet.</div>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary mt-4">← Back to Dashboard</a>
</div>

</body>
</html>
