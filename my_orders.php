<?php
session_start();
include 'header.php';
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$email = $_SESSION['user_email'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_email = ? ORDER BY id DESC");
$stmt->execute([$email]);
$orders = $stmt->fetchAll();

function getProgress($status) {
    if ($status == 'pending') return 15;
    if ($status == 'dispatched') return 45;
    if ($status == 'out_for_delivery') return 75;
    if ($status == 'delivered') return 100;
    return 0;
}
?>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<style>
    @keyframes fillUp { from { width: 0; } to { width: var(--target-width); } }
    @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
    @keyframes statusPulse { 0% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.05); opacity: 0.8; } 100% { transform: scale(1); opacity: 1; } }

    .custom-progress {
        height: 10px !important;
        background-color: #e2e8f0 !important;
        border-radius: 20px !important;
        overflow: hidden;
    }

    .animated-bar {
        height: 100%;
        border-radius: 20px;
        background: linear-gradient(90deg, #6366f1, #a855f7, #6366f1);
        background-size: 200% 100%;
        animation: fillUp 1.5s cubic-bezier(0.4, 0, 0.2, 1) forwards, shimmer 3s linear infinite;
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
    }

    .status-text-active {
        color: #6366f1 !important;
        font-weight: 800 !important;
        animation: statusPulse 2s ease-in-out infinite;
    }

    /* Delivery Location Styling */
    .delivery-info {
        border-left: 3px solid #6366f1;
        padding-left: 15px;
        margin-top: 20px;
    }
</style>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="fa-solid fa-box-open me-2 text-primary"></i>My Order History</h2>

    <?php if (count($orders) > 0): ?>
        <div class="row g-4">
            <?php foreach ($orders as $order): 
                $progress = getProgress($order['status']);
                $status_clean = str_replace('_', ' ', $order['status']);
            ?>
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden order-card">
                        <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between">
                            <div>
                                <span class="badge bg-primary rounded-pill me-2">#<?php echo htmlspecialchars($order['order_number']); ?></span>
                                <small class="text-muted"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="generate_receipt.php?order=<?php echo $order['order_number']; ?>" 
                                class="btn btn-outline-dark btn-sm rounded-pill" target="_blank">
                                    <i class="fa-solid fa-file-invoice-dollar me-1"></i> Invoice
                                </a>
                                <span class="badge bg-light text-dark border">
                                    <i class="fa-solid fa-truck-ramp-box me-1"></i> <?php echo ucfirst(str_replace('_', ' ', $order['status'])); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body p-4">
                            <?php if ($order['status'] != 'cancelled'): ?>
                                <div class="progress custom-progress mb-4">
                                    <div class="animated-bar" style="--target-width: <?php echo $progress; ?>%; width: 0%;"></div>
                                </div>
                                <div class="d-flex justify-content-between text-muted small fw-bold mb-4">
                                    <span class="<?php echo ($order['status'] == 'pending') ? 'status-text-active' : ''; ?>">Ordered</span>
                                    <span class="<?php echo ($order['status'] == 'dispatched') ? 'status-text-active' : ''; ?>">Dispatched</span>
                                    <span class="<?php echo ($order['status'] == 'out_for_delivery') ? 'status-text-active' : ''; ?>">In Transit</span>
                                    <span class="<?php echo ($order['status'] == 'delivered') ? 'status-text-active' : ''; ?>">Delivered</span>
                                </div>
                            <?php endif; ?>

                            <div class="row mt-4">
                                <div class="col-md-7">
                                    <h6 class="small fw-bold text-uppercase text-muted mb-3">Order Items</h6>
                                    <?php 
                                        $stmt_items = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
                                        $stmt_items->execute([$order['id']]);
                                        $items = $stmt_items->fetchAll();
                                        foreach ($items as $item): 
                                    ?>
                                        <div class="d-flex justify-content-between small mb-1">
                                            <span><?php echo htmlspecialchars($item['product_name']); ?> x<?php echo $item['quantity']; ?></span>
                                            <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="col-md-5 border-start">
                                    <h6 class="small fw-bold text-uppercase text-muted mb-2">Delivery Location</h6>
                                    <div class="delivery-info">
                                        <p class="mb-0 small fw-bold"><?php echo htmlspecialchars($order['customer_name']); ?></p>
                                        <p class="mb-0 small text-muted"><?php echo htmlspecialchars($order['customer_address']); ?></p>
                                        <p class="mb-0 small text-muted">Phone: <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                                    </div>
                                    <div class="mt-3 pt-2 border-top">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold">Total Amount</span>
                                            <span class="fw-bold text-primary">$<?php echo number_format($order['total_amount'], 2); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($order['status'] == 'delivered'): ?>
                <script>
                    window.addEventListener('load', () => {
                        var duration = 1 * 1000;
                        var end = Date.now() + duration;

                        (function frame() {
                            confetti({
                                particleCount: 3,
                                angle: 60,
                                spread: 55,
                                origin: { x: 0 },
                                colors: ['#6366f1', '#a855f7']
                            });
                            confetti({
                                particleCount: 3,
                                angle: 120,
                                spread: 55,
                                origin: { x: 1 },
                                colors: ['#6366f1', '#a855f7']
                            });

                            if (Date.now() < end) {
                                requestAnimationFrame(frame);
                            }
                        }());
                    });
                </script>
                <?php endif; ?>

            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <h4 class="text-muted">No orders found.</h4>
        </div>
    <?php endif; ?>
</div>
</body>
</html>