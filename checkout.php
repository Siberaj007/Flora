<?php 
include 'includes/header.php';

require_once 'includes/db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout");
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

// Check if cart is empty
if(count($cartItems) === 0) {
    header("Location: cart.php");
    exit();
}

// Calculate totals
$subtotal = 0;
foreach($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = $subtotal > 100 ? 0 : 15; // Free shipping for orders over $100
$total = $subtotal + $shipping;

// Get user info
$userStmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$userStmt->execute([$userId]);
$user = $userStmt->fetch();
?>

<section class="page-header">
    <div class="container">
        <h1>Checkout</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="cart.php">Cart</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
        </nav>
    </div>
</section>

<section class="checkout-section">
    <div class="container">
        <div class="checkout-container">
            <div class="checkout-form">
                <div class="checkout-steps">
                    <div class="step active" data-step="1">
                        <span>1</span>
                        <p>Shipping</p>
                    </div>
                    <div class="step" data-step="2">
                        <span>2</span>
                        <p>Payment</p>
                    </div>
                    <div class="step" data-step="3">
                        <span>3</span>
                        <p>Confirmation</p>
                    </div>
                </div>
                
                <form id="checkout-form" action="process-checkout.php" method="POST">
                    <div class="step-content active" data-step="1">
                        <h2>Shipping Information</h2>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="first-name">First Name</label>
                                <input type="text" id="first-name" name="first_name" class="form-control" required value="<?php echo htmlspecialchars(explode(' ', $user['full_name'])[0] ?? ''); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="last-name">Last Name</label>
                                <input type="text" id="last-name" name="last_name" class="form-control" required value="<?php echo htmlspecialchars(explode(' ', $user['full_name'])[1] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" class="form-control" required value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" class="form-control" required value="<?php echo htmlspecialchars($user['address']); ?>">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="zip">ZIP Code</label>
                                <input type="text" id="zip" name="zip" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="country">Country</label>
                            <select id="country" name="country" class="form-control" required>
                                <option value="">Select Country</option>
                                <option value="US" selected>United States</option>
                                <option value="CA">Canada</option>
                                <option value="UK">United Kingdom</option>
                                <option value="AU">Australia</option>
                                <option value="IN">India</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="shipping-method">Shipping Method</label>
                            <div class="shipping-methods">
                                <div class="method">
                                    <input type="radio" id="standard" name="shipping_method" value="standard" checked>
                                    <label for="standard">
                                        <h4>Standard Shipping</h4>
                                        <p>3-5 business days</p>
                                        <span>$<?php echo number_format($shipping, 2); ?></span>
                                    </label>
                                </div>
                                <div class="method">
                                    <input type="radio" id="express" name="shipping_method" value="express" <?php echo $shipping === 0 ? 'disabled' : ''; ?>>
                                    <label for="express">
                                        <h4>Express Shipping</h4>
                                        <p>1-2 business days</p>
                                        <span>$<?php echo number_format($shipping + 10, 2); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Order Notes (Optional)</label>
                            <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Notes about your order, e.g. special delivery instructions"></textarea>
                        </div>
                        
                        <div class="step-actions">
                            <button type="button" class="btn btn-outline back-to-cart">
                                <i class="fas fa-chevron-left"></i> Back to Cart
                            </button>
                            <button type="button" class="btn btn-primary next-step" data-next="2">
                                Continue to Payment <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="step-content" data-step="2">
                        <h2>Payment Method</h2>
                        
                        <div class="payment-methods">
                            <div class="payment-tabs">
                                <div class="tab active" data-tab="credit-card">
                                    <i class="far fa-credit-card"></i>
                                    <span>Credit Card</span>
                                </div>
                                <div class="tab" data-tab="paypal">
                                    <i class="fab fa-paypal"></i>
                                    <span>PayPal</span>
                                </div>
                            </div>
                            
                            <div class="tab-content active" data-tab="credit-card">
                                <div class="form-group">
                                    <label for="card-number">Card Number</label>
                                    <input type="text" id="card-number" name="card_number" class="form-control" placeholder="1234 5678 9012 3456">
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="card-name">Name on Card</label>
                                        <input type="text" id="card-name" name="card_name" class="form-control" placeholder="John Doe">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="card-expiry">Expiry</label>
                                        <input type="text" id="card-expiry" name="card_expiry" class="form-control" placeholder="MM/YY">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="card-cvv">CVV</label>
                                        <input type="text" id="card-cvv" name="card_cvv" class="form-control" placeholder="123">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-content" data-tab="paypal">
                                <div class="paypal-info">
                                    <p>You will be redirected to PayPal to complete your payment securely.</p>
                                    <img src="assets/images/paypal-logo.png" alt="PayPal">
                                </div>
                            </div>
                        </div>
                        
                        <div class="step-actions">
                            <button type="button" class="btn btn-outline prev-step" data-prev="1">
                                <i class="fas fa-chevron-left"></i> Back to Shipping
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Place Order <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="order-summary">
                <h2>Your Order</h2>
                <div class="summary-card">
                    <div class="products-list">
                        <?php foreach($cartItems as $item): ?>
                        <div class="product-item">
                            <div class="product-image">
                                <img src="<?php echo $item['image_url'] ?: 'assets/images/product-placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <span class="quantity"><?php echo $item['quantity']; ?></span>
                            </div>
                            <div class="product-info">
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                <span>$<?php echo number_format($item['price'], 2); ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="summary-totals">
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
                    </div>
                </div>
                
                <div class="secure-checkout">
                    <i class="fas fa-lock"></i>
                    <span>Secure Checkout</span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>