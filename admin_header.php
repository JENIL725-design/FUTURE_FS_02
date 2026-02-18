<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexGen Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f1f5f9; }
        .sidebar { min-height: 100vh; background: #1e293b; color: white; }
        .sidebar a { color: #cbd5e1; text-decoration: none; padding: 15px 20px; display: block; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #6366f1; color: white; }
        .stat-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar d-none d-md-block" style="width: 250px;">
        <div class="p-4 text-center">
            <h4 class="fw-bold">NexGen<span class="text-primary">Admin</span></h4>
        </div>
        <a href="admin_dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='admin_dashboard.php'?'active':''; ?>">
            <i class="fa-solid fa-chart-line me-2"></i> Dashboard
        </a>
        <a href="admin_products.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='admin_products.php'?'active':''; ?>">
            <i class="fa-solid fa-box me-2"></i> Products
        </a>
        <a href="admin_orders.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='admin_orders.php'?'active':''; ?>">
            <i class="fa-solid fa-truck-fast me-2"></i> Orders
        </a>
        <a href="admin_complaints.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='admin_complaints.php'?'active':''; ?>">
            <i class="fa-solid fa-message me-2"></i> Complaints
        </a>
        <hr class="mx-3 text-secondary">
        <a href="products.php" target="_blank"><i class="fa-solid fa-eye me-2"></i> View Live Site</a>
        <a href="logout.php" class="text-danger mt-3"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
    </div>

    <div class="flex-grow-1 p-4">
        <button class="btn btn-dark d-md-none mb-3" onclick="document.querySelector('.sidebar').classList.toggle('d-none')">
            <i class="fa-solid fa-bars"></i> Menu
        </button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>