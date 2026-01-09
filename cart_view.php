<?php
session_start();
include 'header.php';
$total = 0;
?>

<style>
    .table-responsive {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .summary-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        background: white;
    }
    .summary-card .card-header {
        background: white;
        font-weight: bold;
        border-bottom: 2px solid #f1f5f9;
        padding: 15px;
    }
    .summary-card .card-body {
        padding: 20px;
    }
</style>

<div class="row">
    <div class="col-12 mb-4">
        <h2>Your Shopping Cart</h2>
    </div>

    <div class="col-lg-8">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <?php foreach ($_SESSION['cart'] as $id => $item): 
                            $itemTotal = $item['price'] * $item['qty'];
                            $total += $itemTotal;
                        ?>
                        <tr id="row-<?php echo $id; ?>">
                            <td class="fw-bold"><?php echo $item['name']; ?></td>
                            <td>$<?php echo $item['price']; ?></td>
                            <td><span class="badge bg-secondary"><?php echo $item['qty']; ?></span></td>
                            <td>$<?php echo number_format($itemTotal, 2); ?></td>
                            <td>
                              <button class="btn btn-danger btn-sm remove-btn" data-id="<?php echo $id; ?>">
                                    <i class="fa-solid fa-trash"></i> Remove
                              </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4">Cart is empty</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card summary-card">
            <div class="card-header">Order Summary</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="h5">Total</span>
                    <span class="h5 text-primary">$<?php echo number_format($total, 2); ?></span>
                </div>
                <a href="checkout.php" class="btn btn-success w-100 py-2">
    <i class="fa-solid fa-credit-card me-2"></i> Proceed to Checkout
</a>
            </div>
        </div>
    </div>
</div>

</div>
<script src="main.js"></script>
<script src="store.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>