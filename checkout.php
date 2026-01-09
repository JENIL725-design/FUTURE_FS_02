<?php
session_start();
include 'header.php';

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit();
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['qty'];
}
?>

<style>
    .checkout-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .form-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .form-control {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
    }

    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        background-color: white;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .summary-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        position: sticky;
        top: 100px; /* Sticks when scrolling */
    }

    .payment-box {
        border: 2px solid #6366f1; /* Indigo border for active state */
        border-radius: 10px;
        padding: 15px;
        background: #fdfcff;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .payment-note {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 5px;
    }
</style>

<div class="container checkout-container">
    <div class="row">
        
        <div class="col-lg-7">
            <h2 class="mb-4 fw-bold">Shipping Details</h2>
            
            <form id="checkout-form">
                <div class="form-section">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="fullname" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" maxlength="10" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pincode</label>
                        <input type="text" name="pincode" class="form-control" maxlength="6" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Address</label>
                        <textarea name="address" class="form-control" rows="3" required></textarea>
                    </div>
                </div>

                <h4 class="mb-3 fw-bold">Payment Method</h4>
                <div class="form-section">
                    <div class="payment-box">
                        <input class="form-check-input" type="radio" name="payment" id="cod" checked style="transform: scale(1.3);">
                        <label class="form-check-label fw-bold w-100" for="cod">
                            Cash on Delivery (COD)
                        </label>
                    </div>
                    <div class="payment-note ms-1">Only Cash on Delivery is available for now.</div>
                </div>
            </form>
        </div>

        <div class="col-lg-5">
            <div class="summary-card">
                <h4 class="fw-bold mb-4">Order Summary</h4>
                
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span><?php echo $item['name']; ?> <small class="text-muted">x<?php echo $item['qty']; ?></small></span>
                        <span>$<?php echo number_format($item['price'] * $item['qty'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
                
                <hr>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span class="fw-bold">$<?php echo number_format($total, 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span>Shipping</span>
                    <span class="text-success">Free</span>
                </div>
                
                <div class="d-flex justify-content-between mb-4">
                    <span class="h5 fw-bold">Total</span>
                    <span class="h4 fw-bold text-primary">$<?php echo number_format($total, 2); ?></span>
                </div>

                <button type="button" class="btn btn-primary w-100 py-3 fw-bold rounded-pill" id="place-order-btn">
                    Place Order <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
                
                <div class="text-center mt-3">
                    <a href="cart_view.php" class="text-muted text-decoration-none small">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="store.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const placeOrderBtn = document.getElementById('place-order-btn');
        
        placeOrderBtn.addEventListener('click', function() {
            // 1. Validate Form
            const form = document.getElementById('checkout-form');
            if (!form.checkValidity()) {
                form.reportValidity(); // Shows native browser errors
                return;
            }

            // 2. Prepare Data
            const formData = new FormData(form);
            formData.append('action', 'checkout');

            // 3. Send to Server
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we confirm your order.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            fetch('ajax_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update the Cart Store count to 0
                    if (typeof CartStore !== 'undefined') {
                        CartStore.setCount(0);
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Placed!',
                        text: 'Your order has been confirmed successfully.',
                        confirmButtonText: 'Continue Shopping'
                    }).then(() => {
                        window.location.href = 'products.php';
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire('Error', 'Something went wrong!', 'error');
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>