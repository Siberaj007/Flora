<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout");
    exit();
}

$userId = $_SESSION['user_id'];

// Get cart items
$stmt = $pdo->prepare("
    SELECT c.*, p.price, p.stock_quantity 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll();

// Validate stock
foreach ($cartItems as $item) {
    if ($item['quantity'] > $item['stock_quantity']) {
        $_SESSION['error'] = "Sorry, '{$item['name']}' only has {$item['stock_quantity']} items in stock.";
        header("Location: cart.php");
        exit();
    }
}

// Calculate totals
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = $subtotal > 100 ? 0 : 15;
$total = $subtotal + $shipping;

// Create order
$pdo->beginTransaction();

try {
    // Insert order
    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, total_amount, payment_method, shipping_address, status)
        VALUES (?, ?, ?, ?, 'processing')
    ");
    
    $shippingAddress = implode(', ', [
        $_POST['address'],
        $_POST['city'],
        $_POST['zip'],
        $_POST['country']
    ]);
    
    $stmt->execute([
        $userId,
        $total,
        $_POST['payment_method'] ?? 'credit_card',
        $shippingAddress
    ]);
    
    $orderId = $pdo->lastInsertId();
    
    // Insert order items and update product quantities
    foreach ($cartItems as $item) {
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
        
        // Update product stock
        $stmt = $pdo->prepare("
            UPDATE products 
            SET stock_quantity = stock_quantity - ? 
            WHERE id = ?
        ");
        $stmt->execute([$item['quantity'], $item['product_id']]);
    }
    
    // Clear cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    $pdo->commit();
    
    $_SESSION['order_id'] = $orderId;
    header("Location: confirmation.php");
    exit();
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = "Order processing failed. Please try again.";
    header("Location: checkout.php");
    exit();
}
?>