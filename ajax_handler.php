<?php
session_start();
header('Content-Type: application/json');
require 'db_connect.php'; 

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? '';

// --- HELPER: Generate Random 7-Char Order ID ---
function generateOrderNumber($pdo) {
    do {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $order_number = substr(str_shuffle($chars), 0, 7);
        $stmt = $pdo->prepare("SELECT id FROM orders WHERE order_number = ?");
        $stmt->execute([$order_number]);
    } while ($stmt->fetch());
    return $order_number;
}

// --- ACTION: ADD ITEM ---
if ($action === 'add') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id'];

    // Check if item already exists for this user in DB
    $stmt = $pdo->prepare("SELECT id FROM user_cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $id]);
    $exists = $stmt->fetch();

    if ($exists) {
        $pdo->prepare("UPDATE user_cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?")
            ->execute([$user_id, $id]);
    } else {
        $pdo->prepare("INSERT INTO user_cart (user_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, 1)")
            ->execute([$user_id, $id, $name, $price]);
    }

    // Update Session Count for the badge
    $count = $pdo->prepare("SELECT SUM(quantity) FROM user_cart WHERE user_id = ?");
    $count->execute([$user_id]);
    
    echo json_encode(['status' => 'success', 'cart_count' => (int)$count->fetchColumn()]);
    exit;
}

// --- ACTION: UPDATE QUANTITY ---
if ($action === 'update_qty') {
    $id = $_POST['id'];
    $new_qty = (int)$_POST['qty'];
    $user_id = $_SESSION['user_id'];

    if ($new_qty > 0) {
        $pdo->prepare("UPDATE user_cart SET quantity = ? WHERE user_id = ? AND product_id = ?")
            ->execute([$new_qty, $user_id, $id]);
    } else {
        $pdo->prepare("DELETE FROM user_cart WHERE user_id = ? AND product_id = ?")
            ->execute([$user_id, $id]);
    }
    exit;
}

// --- ACTION: REMOVE ITEM ---
if ($action === 'remove') {
    $id = $_POST['id'];
    unset($_SESSION['cart'][$id]);
    echo json_encode(['status' => 'success', 'cart_count' => count($_SESSION['cart'])]);
    exit;
}

// --- ACTION: CHECKOUT ---
if ($action === 'checkout') {
    if (empty($_SESSION['cart'])) {
        echo json_encode(['status' => 'error', 'message' => 'Your cart is empty!']);
        exit;
    }

    $name = $_POST['fullname'] ?? 'Guest';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] . ', ' . $_POST['pincode'];
    $payment = $_POST['payment'] ?? 'cod';
    
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += ($item['price'] * $item['qty']);
    }

    try {
        $pdo->beginTransaction();
        $public_order_id = generateOrderNumber($pdo);

        $sql = "INSERT INTO orders (order_number, customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method) 
                VALUES (:ordernum, :name, :email, :phone, :address, :total, :payment)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':ordernum' => $public_order_id,
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':address' => $address,
            ':total' => $total_amount,
            ':payment' => $payment
        ]);

        $internal_id = $pdo->lastInsertId();
        $sql_item = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price) 
                     VALUES (:oid, :pid, :pname, :qty, :price)";
        $stmt_item = $pdo->prepare($sql_item);

        foreach ($_SESSION['cart'] as $pid => $item) {
            $stmt_item->execute([
                ':oid' => $internal_id,
                ':pid' => $pid,
                ':pname' => $item['name'],
                ':qty' => $item['qty'],
                ':price' => $item['price']
            ]);
        }

        $pdo->commit();
        unset($_SESSION['cart']);
        echo json_encode(['status' => 'success', 'message' => 'Order #' . $public_order_id . ' placed successfully!']);
    } catch (Exception $e) {
        $pdo->rollBack(); 
        echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);