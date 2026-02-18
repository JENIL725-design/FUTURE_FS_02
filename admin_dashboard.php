<?php
include 'admin_header.php';
require 'db_connect.php';

// 1. Get Stats
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(total_amount) FROM orders")->fetchColumn();
$pending_complaints = $pdo->query("SELECT COUNT(*) FROM complaints WHERE status='pending'")->fetchColumn();

// 2. Get Top Selling Products
$top_products = $pdo->query("
    SELECT product_name, SUM(quantity) as total_sold 
    FROM order_items 
    GROUP BY product_id 
    ORDER BY total_sold DESC 
    LIMIT 5
")->fetchAll();
?>

<div class="container-fluid">
    <h3 class="fw-bold mb-4">Dashboard Overview</h3>
    
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center">
                <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Revenue</div>
                    <h4 class="fw-bold mb-0">$<?php echo number_format($total_revenue, 2); ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center">
                <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                    <i class="fa-solid fa-shopping-cart"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Orders</div>
                    <h4 class="fw-bold mb-0"><?php echo $total_orders; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center">
                <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                    <div class="text-muted small">Pending Complaints</div>
                    <h4 class="fw-bold mb-0"><?php echo $pending_complaints; ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">🏆 Top Selling Products</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Rank</th>
                        <th>Product Name</th>
                        <th>Units Sold</th>
                        <th>Performance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1; foreach ($top_products as $p): ?>
                    <tr>
                        <td><span class="badge bg-dark rounded-circle"><?php echo $rank++; ?></span></td>
                        <td class="fw-bold"><?php echo htmlspecialchars($p['product_name']); ?></td>
                        <td><?php echo $p['total_sold']; ?></td>
                        <td style="width: 30%;">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: <?php echo min($p['total_sold'] * 10, 100); ?>%"></div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>