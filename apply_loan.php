<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $loan_amount = $_POST['loan_amount'];
    $loan_purpose = $_POST['loan_purpose'];
    $duration = $_POST['duration'];
    $status = "Pending";

    if (!empty($loan_amount) && !empty($loan_purpose) && !empty($duration)) {
        $stmt = $conn->prepare("INSERT INTO loans (user_id, loan_amount, purpose, duration, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("idsss", $user_id, $loan_amount, $loan_purpose, $duration, $status);

        if ($stmt->execute()) {
            $success = "Loan application submitted successfully!";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply for Loan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-box {
            max-width: 600px;
            margin: auto;
            margin-top: 60px;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="form-box">
    <h3 class="mb-4">Apply for a Loan</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="loan_amount" class="form-label">Loan Amount (PKR)</label>
            <input type="number" class="form-control" name="loan_amount" required>
        </div>
        <div class="mb-3">
            <label for="loan_purpose" class="form-label">Purpose</label>
            <input type="text" class="form-control" name="loan_purpose" required>
        </div>
        <div class="mb-3">
            <label for="duration" class="form-label">Duration (months)</label>
            <input type="number" class="form-control" name="duration" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Application</button>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>
</div>

</body>
</html>
