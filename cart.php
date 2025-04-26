<?php 
include 'includes/header.php';

require_once 'includes/db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=cart");
    exit();
}

$userId = $_SESSION['user_id'];

// Get cart items
$stmt = $pdo->prepare("
    SELECT c.*, p.name, p.price, p.image_url, p.stock_quantity 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll();

// Calculate totals
$subtotal = 0;
foreach($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = $subtotal > 100 ? 0 : 15; // Free shipping for orders over $100
$total = $subtotal + $shipping;
?>

<section class="page-header">
    <div class="container">
        <h1>Your Shopping Cart</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cart</li>
            </ol>
        </nav>
    </div>
</section>

<section class="cart-section">
    <div class="container">
        <?php if(count($cartItems) > 0): ?>
        <div class="cart-container">
            <div class="cart-items">
                <div class="cart-header">
                    <div class="header-item product">Product</div>
                    <div class="header-item price">Price</div>
                    <div class="header-item quantity">Quantity</div>
                    <div class="header-item total">Total</div>
                    <div class="header-item action">Action</div>
                </div>
                
                <?php foreach($cartItems as $item): ?>
                <div class="cart-item" data-id="<?php echo $item['product_id']; ?>">
                    <div class="cart-col product">
                        <div class="product-image">
                            <img src="<?php echo $item['image_url'] ?: 'assets/images/product-placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <?php if($item['stock_quantity'] < $item['quantity']): ?>
                                <div class="stock-warning"><i class="fas fa-exclamation-circle"></i> Only <?php echo $item['stock_quantity']; ?> available</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="cart-col price">
                        $<?php echo number_format($item['price'], 2); ?>
                    </div>
                    <div class="cart-col quantity">
                        <div class="quantity-control">
                            <button class="qty-minus"><i class="fas fa-minus"></i></button>
                            <input type="number" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock_quantity']; ?>" class="qty-input">
                            <button class="qty-plus"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="cart-col total">
                        $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                    </div>
                    <div class="cart-col action">
                        <button class="remove-item"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="cart-actions">
                    <a href="products.php" class="btn btn-outline">
                        <i class="fas fa-chevron-left"></i> Continue Shopping
                    </a>
                    <button class="btn btn-outline update-cart">
                        <i class="fas fa-sync-alt"></i> Update Cart
                    </button>
                </div>
            </div>
            
            <div class="cart-summary">
                <div class="summary-card">
                    <h3>Cart Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>$<?php echo number_format($shipping, 2); ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    
                    <a href="checkout.php" class="btn btn-primary btn-block">Proceed to Checkout</a>
                    
                    <div class="payment-methods">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-amex"></i>
                        <i class="fab fa-cc-paypal"></i>
                    </div>
                </div>
                
                <div class="coupon-card">
                    <h3>Have a Coupon?</h3>
                    <form class="coupon-form">
                        <input type="text" placeholder="Enter coupon code">
                        <button type="submit" class="btn btn-outline">Apply</button>
                    </form>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="empty-cart">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2>Your cart is empty</h2>
            <p>Looks like you haven't added anything to your cart yet</p>
            <a href="products.php" class="btn btn-primary">Browse Products</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>