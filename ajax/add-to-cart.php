<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;
    $user_id = $_SESSION['user_id'];
    
    try {
        // Check if product exists in cart
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Update quantity
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$quantity, $user_id, $product_id]);
        } else {
            // Add new item
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $product_id, $quantity]);
        }
        
        // Get total cart items
        $stmt = $pdo->prepare("SELECT SUM(quantity) as cart_count FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch();
        $_SESSION['cart_count'] = $result['cart_count'];
        
        echo json_encode([
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $_SESSION['cart_count']
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error adding to cart']);
    }
}
