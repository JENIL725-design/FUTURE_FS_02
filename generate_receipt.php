<?php
session_start();
require 'db_connect.php'; 

if (!isset($_SESSION['user_id']) || !isset($_GET['order'])) {
    header("Location: login.php");
    exit();
}

$order_num = $_GET['order'];
$email = $_SESSION['user_email'];

// 1. FETCH ORDER DATA
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ? AND customer_email = ?");
$stmt->execute([$order_num, $email]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found or access denied.");
}

// 2. FETCH ITEMS
$stmt_items = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt_items->execute([$order['id']]);
$items = $stmt_items->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $order_num; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f1f5f9; padding: 50px 0; }
        .invoice-card { background: white; max-width: 800px; margin: auto; padding: 50px; border-radius: 0; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .invoice-header { border-bottom: 2px solid #6366f1; margin-bottom: 30px; padding-bottom: 20px; }
        @media print { .no-print { display: none; } body { background: white; padding: 0; } .invoice-card { box-shadow: none; } }
    </style>
</head>
<body>

<div class="text-center no-print mb-4">
    <button onclick="window.print()" class="btn btn-primary rounded-pill px-4">
        <i class="fa-solid fa-print"></i> Print or Save as PDF
    </button>
</div>

<div class="invoice-card">
    <div class="invoice-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-primary mb-0">NexGenStore.</h2>
            <p class="text-muted small">Premium Gaming Gear</p>
        </div>
        <div class="text-end">
            <h4 class="fw-bold mb-0">INVOICE</h4>
            <p class="text-muted mb-0">#<?php echo $order_num; ?></p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <h6 class="text-muted text-uppercase small">Billed To:</h6>
            <p class="fw-bold mb-0"><?php echo htmlspecialchars($order['customer_name']); ?></p>
            <p class="small text-muted mb-0"><?php echo htmlspecialchars($order['customer_address']); ?></p>
            <p class="small text-muted"><?php echo htmlspecialchars($order['customer_phone']); ?></p>
        </div>
        <div class="col-6 text-end">
            <h6 class="text-muted text-uppercase small">Order Date:</h6>
            <p class="fw-bold"><?php echo date('F d, Y', strtotime($order['created_at'])); ?></p>
        </div>
    </div>

    <table class="table table-borderless align-middle">
        <thead class="bg-light">
            <tr>
                <th>Item Description</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Price</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                    <td class="text-end">$<?php echo number_format($item['price'], 2); ?></td>
                    <td class="text-end fw-bold">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="row justify-content-end mt-4">
        <div class="col-md-5">
            <div class="d-flex justify-content-between mb-2">
                <span>Subtotal:</span>
                <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
            </div>
            <div class="d-flex justify-content-between border-top pt-2">
                <h5 class="fw-bold">Grand Total:</h5>
                <h5 class="fw-bold text-primary">$<?php echo number_format($order['total_amount'], 2); ?></h5>
            </div>
        </div>
    </div>
    
    <div class="mt-5 text-center">
        <p class="text-muted small">Thank you for shopping with NexGenStore! Keep this receipt for your records.</p>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>