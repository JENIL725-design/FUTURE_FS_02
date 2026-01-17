<?php
session_start();
include 'header.php';

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

    body {
        background-color: #f3f4f6;
    }

    .checkout-wrapper {
        padding-top: 30px;
        padding-bottom: 80px;
    }

    .steps-container {
        display: flex;
        justify-content: center;
        margin-bottom: 40px;
    }

    .step {
        display: flex;
        align-items: center;
        color: #94a3b8;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .step.active {
        color: #6366f1;
        font-weight: 700;
    }

    .step-icon {
        width: 30px; 
        height: 30px;
        border-radius: 50%;
        background: #e2e8f0;
        display: flex; 
        align-items: center; 
        justify-content: center;
        margin-right: 10px;
        font-size: 0.8rem;
    }

    .step.active .step-icon {
        background: #6366f1;
        color: white;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
    }

    .step-line {
        width: 50px; 
        height: 2px; 
        background: #e2e8f0;
        margin: 0 15px;
    }

    .form-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 25px;
        display: flex; 
        align-items: center;
    }

    .section-title i { 
        margin-right: 10px; 
        color: #6366f1; 
    }

    .input-group-custom {
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border: 2px solid #f1f5f9;
        border-radius: 12px;
        padding: 12px 15px;
        font-size: 0.95rem;
        transition: all 0.3s;
        background: #f8fafc;
    }

    .form-control:focus {
        background: white;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .payment-option {
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .payment-option.active {
        border-color: #6366f1;
        background-color: #f5f3ff;
    }

    .radio-custom {
        width: 20px; 
        height: 20px;
        border: 2px solid #cbd5e1;
        border-radius: 50%;
        display: flex; 
        align-items: center; 
        justify-content: center;
    }

    .payment-option.active .radio-custom::after {
        content: ''; 
        width: 10px; 
        height: 10px;
        background: #6366f1; 
        border-radius: 50%;
    }

    .summary-sidebar {
        position: sticky;
        top: 100px;
    }

    .summary-card {
        background: #1e293b;
        color: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 20px 40px rgba(30, 41, 59, 0.2);
    }
    
    .cart-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .item-icon {
        width: 40px; height: 40px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        display: flex; 
        align-items: center; 
        justify-content: center;
        margin-right: 15px;
        font-size: 1.2rem;
    }

    .item-info { 
        flex-grow: 1; 
    }

    .item-name { 
        font-weight: 500; 
        font-size: 0.9rem; 
    }

    .item-qty { 
        font-size: 0.8rem; 
        color: #94a3b8; 
    }

    .item-price { 
        font-weight: 600; 
    }

    .summary-row {
        display: flex; 
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 0.9rem;
        color: #cbd5e1;
    }

    .total-row {
        display: flex; 
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.2);
        font-size: 1.2rem;
        font-weight: 700;
        color: white;
    }

    .btn-place-order {
        background: #6366f1;
        color: white;
        width: 100%;
        padding: 15px;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        margin-top: 25px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    }

    .btn-place-order:hover {
        background: #4f46e5;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
    }
    
    @media (max-width: 768px) {
    .form-card { 
        padding: 25px 20px; 
    }

    .steps-container { 
        font-size: 0.8rem; 
    }

    .step-line { 
        width: 20px; 
        margin: 0 5px; 
    } 

    .step-icon { 
        width: 24px; 
        height: 24px; 
        font-size: 0.7rem; 
        margin-right: 6px; 
    }

    .summary-sidebar { 
        margin-top: 30px; 
    }
    
}
</style>

<div class="container checkout-wrapper">
    
    <div class="steps-container">
        <div class="step">
            <div class="step-icon"><i class="fa-solid fa-check"></i></div>
            Cart
        </div>
        <div class="step-line"></div>
        <div class="step active">
            <div class="step-icon">2</div>
            Shipping
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-icon">3</div>
            Done
        </div>
    </div>

    <div class="row g-5">
        
        <div class="col-lg-7">
            <form id="checkout-form">
                
                <div class="form-card mb-4">
                    <div class="section-title">
                        <i class="fa-solid fa-truck-fast"></i> Shipping Details
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 input-group-custom">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="fullname" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6 input-group-custom">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" placeholder="123 456 7890" maxlength="10" required>
                        </div>
                    </div>

                    <div class="input-group-custom">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                    </div>

                    <div class="row">
                        <div class="col-md-8 input-group-custom">
                            <label class="form-label">Street Address</label>
                            <input type="text" name="address" class="form-control" placeholder="123 Main St, Apt 4B" required>
                        </div>
                        <div class="col-md-4 input-group-custom">
                            <label class="form-label">Pincode</label>
                            <input type="text" name="pincode" class="form-control" placeholder="10001" maxlength="6" required>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="section-title">
                        <i class="fa-regular fa-credit-card"></i> Payment Method
                    </div>
                    
                    <div class="payment-option active">
                        <div class="d-flex align-items-center">
                            <div class="radio-custom me-3"></div>
                            <div>
                                <h6 class="mb-0 fw-bold">Cash on Delivery</h6>
                                <small class="text-muted">Pay when you receive</small>
                            </div>
                        </div>
                        <i class="fa-solid fa-money-bill-wave text-success fs-4"></i>
                        <input type="radio" name="payment" value="cod" checked hidden>
                    </div>
                </div>

            </form>
        </div>

        <div class="col-lg-5">
            <div class="summary-sidebar">
                <div class="summary-card">
                    <h4 class="fw-bold mb-4">Your Order</h4>
                    
                    <div style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <div class="cart-item">
                                <div class="item-icon">📦</div>
                                <div class="item-info">
                                    <div class="item-name"><?php echo $item['name']; ?></div>
                                    <div class="item-qty">Qty: <?php echo $item['qty']; ?></div>
                                </div>
                                <div class="item-price">$<?php echo number_format($item['price'] * $item['qty'], 2); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-4">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>$<?php echo number_format($total, 2); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span class="text-success">Free</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax (Estimated)</span>
                            <span>$0.00</span>
                        </div>
                        
                        <div class="total-row">
                            <span>Total to Pay</span>
                            <span>$<?php echo number_format($total, 2); ?></span>
                        </div>
                    </div>

                    <button type="button" class="btn-place-order" id="place-order-btn">
                        Confirm Order <i class="fa-solid fa-arrow-right ms-2"></i>
                    </button>
                    
                    <div class="text-center mt-3">
                        <a href="cart_view.php" class="text-white-50 text-decoration-none small">
                            <i class="fa-solid fa-arrow-left me-1"></i> Return to Cart
                        </a>
                    </div>
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
            const form = document.getElementById('checkout-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            const formData = new FormData(form);
            formData.append('action', 'checkout');
            Swal.fire({
                title: 'Processing Order',
                text: 'Please wait a moment...',
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
                    if (typeof CartStore !== 'undefined') {
                        CartStore.setCount(0);
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Confirmed!',
                        text: 'Thank you for your purchase.',
                        confirmButtonColor: '#6366f1',
                        confirmButtonText: 'Back to Shop'
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