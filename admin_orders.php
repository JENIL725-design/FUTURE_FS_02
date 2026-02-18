<?php
include 'admin_header.php';
require 'db_connect.php';

// ✅ FIXED: Check for 'status' (which is the name of the buttons)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $order_id]);
        
        echo "<script>Swal.fire('Updated!', 'Order marked as " . ucfirst($new_status) . "', 'success');</script>";
    } catch (PDOException $e) {
        echo "<script>Swal.fire('Error', 'Database update failed.', 'error');</script>";
    }
}

// Fetch All Orders
$orders = $pdo->query("SELECT * FROM orders ORDER BY id DESC")->fetchAll();
?>

<div class="container-fluid">
    <h3 class="fw-bold mb-4 text-dark">Order Management</h3>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Current Status</th>
                            <th>Action (Update Status)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="ps-4 fw-bold">#<?php echo htmlspecialchars($order['order_number']); ?></td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($order['customer_email']); ?></div>
                            </td>
                            <td class="fw-bold text-success">$<?php echo $order['total_amount']; ?></td>
                            <td>
                                <?php 
                                    $statusColor = 'secondary';
                                    if($order['status'] == 'pending') $statusColor = 'warning';
                                    if($order['status'] == 'dispatched') $statusColor = 'info';
                                    if($order['status'] == 'out_for_delivery') $statusColor = 'primary';
                                    if($order['status'] == 'delivered') $statusColor = 'success';
                                    if($order['status'] == 'cancelled') $statusColor = 'danger';
                                ?>
                                <span class="badge bg-<?php echo $statusColor; ?> text-uppercase">
                                    <?php echo str_replace('_', ' ', $order['status']); ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    
                                    <button type="submit" name="status" value="dispatched" class="btn btn-outline-info btn-sm" title="Mark Dispatched">
                                        <i class="fa-solid fa-box"></i>
                                    </button>
                                    <button type="submit" name="status" value="out_for_delivery" class="btn btn-outline-primary btn-sm" title="Out for Delivery">
                                        <i class="fa-solid fa-motorcycle"></i>
                                    </button>
                                    <button type="submit" name="status" value="delivered" class="btn btn-outline-success btn-sm" title="Mark Delivered">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button type="submit" name="status" value="cancelled" class="btn btn-outline-danger btn-sm" title="Cancel">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>