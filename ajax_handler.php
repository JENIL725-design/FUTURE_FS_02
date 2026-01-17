<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? '';

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

    $total_items = count($_SESSION['cart']);
    
    session_write_close(); 

    echo json_encode([
        'status' => 'success', 
        'message' => 'Product added successfully!',
        'cart_count' => $total_items
    ]);
    exit;
}

if ($action === 'remove') {
    $id = $_POST['id'];
    
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
        
        $total_items = count($_SESSION['cart']);

        session_write_close();

        echo json_encode([
            'status' => 'success', 
            'message' => 'Item removed!',
            'cart_count' => $total_items
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Item not found']);
    }
    exit;
}

if ($action === 'checkout') {
    
    if (empty($_SESSION['cart'])) {
        echo json_encode(['status' => 'error', 'message' => 'Your cart is empty!']);
        exit;
    }

    unset($_SESSION['cart']);
    
    session_write_close();

    echo json_encode([
        'status' => 'success', 
        'message' => 'Order placed successfully!'
    ]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
?>