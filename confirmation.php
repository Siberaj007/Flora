<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['order_id'])) {
    header("Location: index.php");
    exit();
}

$orderId = $_SESSION['order_id'];
$userId = $_SESSION['user_id'];

// Get order details
$stmt = $pdo->prepare("
    SELECT o.*, 
           COUNT(oi.id) as item_count,
           GROUP_CONCAT(p.name SEPARATOR ', ') as product_names
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.id = ? AND o.user_id = ?
    GROUP BY o.id
");
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: index.php");
    exit();
}

// Clear the order ID from session so it can't be refreshed
unset($_SESSION['order_id']);

include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Order Confirmation</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order Confirmation</li>
            </ol>
        </nav>
    </div>
</section>

<section class="confirmation-section">
    <div class="container">
        <div class="confirmation-card">
            <div class="confirmation-header">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Thank You for Your Order!</h2>
                <p>Your order has been placed successfully. We'll process it shortly.</p>
            </div>
            
            <div class="confirmation-details">
                <div class="detail-row">
                    <div class="detail-col">
                        <h3>Order Number</h3>
                        <p>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></p>
                    </div>
                    <div class="detail-col">
                        <h3>Date</h3>
                        <p><?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                    </div>
                    <div class="detail-col">
                        <h3>Total</h3>
                        <p>$<?php echo number_format($order['total_amount'], 2); ?></p>
                    </div>
                    <div class="detail-col">
                        <h3>Payment Method</h3>
                        <p><?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="order-summary">
                <h3>Order Summary</h3>
                <p>You ordered <?php echo $order['item_count']; ?> item(s): <?php echo $order['product_names']; ?></p>
                <p>We'll send a confirmation email with tracking information once your order ships.</p>
            </div>
            
            <div class="confirmation-actions">
                <a href="products.php" class="btn btn-outline">Continue Shopping</a>
                <a href="account.php?tab=orders" class="btn btn-primary">View Order Details</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>