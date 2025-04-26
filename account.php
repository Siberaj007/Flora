<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=account");
    exit();
}

$userId = $_SESSION['user_id'];
$currentTab = $_GET['tab'] ?? 'profile';

// Get user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Get user orders
$orders = [];
if ($currentTab === 'orders') {
    $stmt = $pdo->prepare("
        SELECT o.*, 
               COUNT(oi.id) as item_count,
               SUM(oi.quantity) as total_quantity
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        WHERE o.user_id = ?
        GROUP BY o.id
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$userId]);
    $orders = $stmt->fetchAll();
}

// Get user bookings
$bookings = [];
if ($currentTab === 'bookings') {
    $stmt = $pdo->prepare("
        SELECT * FROM bookings 
        WHERE user_id = ?
        ORDER BY event_date DESC
    ");
    $stmt->execute([$userId]);
    $bookings = $stmt->fetchAll();
}

// Get wishlist items
$wishlist = [];
if ($currentTab === 'wishlist') {
    $stmt = $pdo->prepare("
        SELECT p.* 
        FROM wishlist w
        JOIN products p ON w.product_id = p.id
        WHERE w.user_id = ?
    ");
    $stmt->execute([$userId]);
    $wishlist = $stmt->fetchAll();
}

include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>My Account</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Account</li>
            </ol>
        </nav>
    </div>
</section>

<section class="account-section">
    <div class="container">
        <div class="account-container">
            <div class="account-sidebar">
                <div class="account-user">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-info">
                        <h3><?php echo htmlspecialchars($user['full_name'] ?: $user['username']); ?></h3>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>
                
                <ul class="account-menu">
                    <li class="<?php echo $currentTab === 'profile' ? 'active' : ''; ?>">
                        <a href="account.php?tab=profile">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li class="<?php echo $currentTab === 'orders' ? 'active' : ''; ?>">
                        <a href="account.php?tab=orders">
                            <i class="fas fa-shopping-bag"></i> Orders
                        </a>
                    </li>
                    <li class="<?php echo $currentTab === 'bookings' ? 'active' : ''; ?>">
                        <a href="account.php?tab=bookings">
                            <i class="fas fa-calendar-check"></i> Bookings
                        </a>
                    </li>
                    <li class="<?php echo $currentTab === 'wishlist' ? 'active' : ''; ?>">
                        <a href="account.php?tab=wishlist">
                            <i class="fas fa-heart"></i> Wishlist
                        </a>
                    </li>
                    <li>
                        <a href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="account-content">
                <?php if ($currentTab === 'profile'): ?>
                    <div class="account-tab" id="profile">
                        <h2>Profile Information</h2>
                        
                        <form class="account-form" method="POST" action="update-profile.php">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="full_name">Full Name</label>
                                    <input type="text" id="full_name" name="full_name" class="form-control" 
                                           value="<?php echo htmlspecialchars($user['full_name']); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">Username</label>
                                    <input type="text" id="username" name="username" class="form-control" 
                                           value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="tel" id="phone" name="phone" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea id="address" name="address" class="form-control" 
                                          rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </div>
                        </form>
                        
                        <div class="change-password">
                            <h3>Change Password</h3>
                            <form class="account-form" method="POST" action="change-password.php">
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirm_password">Confirm New Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                
                <?php elseif ($currentTab === 'orders'): ?>
                    <div class="account-tab" id="orders">
                        <h2>My Orders</h2>
                        
                        <?php if (empty($orders)): ?>
                            <div class="empty-state">
                                <i class="fas fa-shopping-bag"></i>
                                <h3>No Orders Yet</h3>
                                <p>You haven't placed any orders with us yet.</p>
                                <a href="products.php" class="btn btn-primary">Browse Products</a>
                            </div>
                        <?php else: ?>
                            <div class="orders-list">
                                <?php foreach ($orders as $order): ?>
                                    <div class="order-card">
                                        <div class="order-header">
                                            <div class="order-id">
                                                <span>Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></span>
                                                <span class="badge <?php echo strtolower($order['status']); ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </div>
                                            <div class="order-date">
                                                <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="order-details">
                                            <div class="order-items">
                                                <span><?php echo $order['item_count']; ?> item(s) • <?php echo $order['total_quantity']; ?> total</span>
                                                <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
                                            </div>
                                            
                                            <div class="order-actions">
                                                <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn btn-outline btn-sm">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                
                <?php elseif ($currentTab === 'bookings'): ?>
                    <div class="account-tab" id="bookings">
                        <h2>My Bookings</h2>
                        
                        <?php if (empty($bookings)): ?>
                            <div class="empty-state">
                                <i class="fas fa-calendar-check"></i>
                                <h3>No Bookings Yet</h3>
                                <p>You haven't made any decoration bookings yet.</p>
                                <a href="booking.php" class="btn btn-primary">Book Now</a>
                            </div>
                        <?php else: ?>
                            <div class="bookings-list">
                                <?php foreach ($bookings as $booking): ?>
                                    <div class="booking-card">
                                        <div class="booking-header">
                                            <div class="booking-id">
                                                <span>Booking #<?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></span>
                                                <span class="badge <?php echo strtolower($booking['status']); ?>">
                                                    <?php echo ucfirst($booking['status']); ?>
                                                </span>
                                            </div>
                                            <div class="booking-date">
                                                <?php echo date('F j, Y', strtotime($booking['event_date'])); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="booking-details">
                                            <div class="detail">
                                                <span>Event Type:</span>
                                                <span><?php echo ucfirst($booking['event_type']); ?></span>
                                            </div>
                                            <div class="detail">
                                                <span>Decoration:</span>
                                                <span><?php echo ucfirst(str_replace('_', ' ', $booking['decoration_type'])); ?></span>
                                            </div>
                                            <div class="detail">
                                                <span>Location:</span>
                                                <span><?php echo nl2br(htmlspecialchars($booking['location'])); ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="booking-actions">
                                            <a href="booking-detail.php?id=<?php echo $booking['id']; ?>" class="btn btn-outline btn-sm">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                
                <?php elseif ($currentTab === 'wishlist'): ?>
                    <div class="account-tab" id="wishlist">
                        <h2>My Wishlist</h2>
                        
                        <?php if (empty($wishlist)): ?>
                            <div class="empty-state">
                                <i class="fas fa-heart"></i>
                                <h3>Your Wishlist is Empty</h3>
                                <p>Save your favorite products here for easy access later.</p>
                                <a href="products.php" class="btn btn-primary">Browse Products</a>
                            </div>
                        <?php else: ?>
                            <div class="wishlist-grid">
                                <?php foreach ($wishlist as $product): ?>
                                    <div class="product-card">
                                        <div