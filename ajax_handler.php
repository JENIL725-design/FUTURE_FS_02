<?php
session_start();
header('Content-Type: application/json');

// Initialize cart if needed
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? '';

// --- ADD TO CART ---
if ($action === 'add') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] += 1;
    } else {
        $_SESSION['cart'][$id] = [
            'name' => $name,
            'price' => $price,
            'qty' => 1
        ];
    }

    // STATE MANAGEMENT: Calculate the total items
    $total_items = count($_SESSION['cart']);

    echo json_encode([
        'status' => 'success', 
        'message' => 'Product added successfully!',
        'cart_count' => $total_items // <--- SENDING THE REAL STATE
    ]);
    exit;
}

// --- REMOVE FROM CART ---
if ($action === 'remove') {
    $id = $_POST['id'];
    
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
        
        $total_items = count($_SESSION['cart']); // Recount after removing

        echo json_encode([
            'status' => 'success', 
            'message' => 'Item removed!',
            'cart_count' => $total_items // <--- SENDING THE REAL STATE
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Item not found']);
    }
    exit;
}

if ($action === 'checkout') {
    
    // Optional: Check if cart is empty first
    if (empty($_SESSION['cart'])) {
        echo json_encode(['status' => 'error', 'message' => 'Your cart is empty!']);
        exit;
    }

    // 1. In a real app, you would Save the Order to Database here.
    // 2. Process Payment via Stripe/PayPal here.

    // 3. Clear the cart to finish the "purchase"
    unset($_SESSION['cart']);

    echo json_encode([
        'status' => 'success', 
        'message' => 'Order placed successfully! Thank you for shopping.'
    ]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
?>