<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Loan Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            background-color: #343a40;
            padding-top: 30px;
            position: fixed;
            width: 250px;
        }

        .sidebar a {
            color: white;
            padding: 15px 25px;
            display: block;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .main-content {
            margin-left: 250px;
            padding: 40px;
        }

        .welcome-card {
            border-radius: 12px;
            background: linear-gradient(135deg, #007bff, #6c63ff);
            color: white;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .logout-btn {
            position: absolute;
            bottom: 30px;
            left: 25px;
            right: 25px;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center text-white mb-4">Loan System</h4>
    <a href="dashboard.php">🏠 Dashboard</a>
    <?php if ($role !== 'admin'): ?>
        <a href="apply_loan.php">📝 Apply for Loan</a>
    <?php endif; ?>
    <a href="my_loans.php">📋 My Applications</a>
    <?php if ($role === 'admin'): ?>
        <a href="admin_panel.php">🛠️ Admin Panel</a>
        
    <?php endif; ?>
    <a href="logout.php" class="btn btn-danger logout-btn">Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="welcome-card">
        <h3>Welcome, <?= htmlspecialchars($username) ?> 👋</h3>
        <p>You are logged in as <strong><?= ucfirst($role) ?></strong>.</p>
    </div>

    <div class="row g-4">
        <?php if ($role !== 'admin'): ?>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Apply for Loan</h5>
                        <p class="card-text">Start a new loan application with ease.</p>
                        <a href="apply_loan.php" class="btn btn-primary">Apply Now</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">My Applications</h5>
                    <p class="card-text">Track your previous loan requests and view their status.</p>
                    <a href="my_loans.php" class="btn btn-info text-white">View Status</a>
                </div>
            </div>
        </div>

        <?php if ($role === 'admin'): ?>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Admin Panel</h5>
                        <p class="card-text">Manage and approve user loan requests.</p>
                        <a href="admin_panel.php" class="btn btn-success">Go to Panel</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
