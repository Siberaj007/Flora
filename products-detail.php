<?php 
include 'includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

require_once 'includes/db.php';

$productId = intval($_GET['id']); // Ensure the ID is an integer
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: products.php");
    exit();
}

// Get related products
$relatedStmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? ORDER BY RAND() LIMIT 4");
$relatedStmt->execute([$product['category'], $productId]);
$relatedProducts = $relatedStmt->fetchAll();
?>

<section class="page-header">
    <div class="container">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="products.php?category=<?php echo $product['category']; ?>"><?php echo ucfirst($product['category']); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>
    </div>
</section>

<section class="product-detail">
    <div class="container">
        <div class="product-detail-container">
            <div class="product-gallery">
                <div class="main-image">
                    <img src="<?php echo $product['image_url'] ?: 'assets/images/product-placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" id="zoom-image" data-zoom-image="<?php echo $product['image_url'] ?: 'assets/images/product-placeholder.jpg'; ?>">
                </div>
                <div class="thumbnail-images">
                    <div class="thumbnail active">
                        <img src="<?php echo $product['image_url'] ?: 'assets/images/product-placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <div class="thumbnail">
                        <img src="assets/images/product-detail2.jpg" alt="Product Detail">
                    </div>
                    <div class="thumbnail">
                        <img src="assets/images/product-detail3.jpg" alt="Product Detail">
                    </div>
                    <div class="thumbnail">
                        <img src="assets/images/product-detail4.jpg" alt="Product Detail">
                    </div>
                </div>
            </div>
            
            <div class="product-info">
                <div class="product-header">
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                    <div class="product-meta">
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span>(24 reviews)</span>
                        </div>
                        <div class="sku">SKU: FL-<?php echo str_pad($product['id'], 4, '0', STR_PAD_LEFT); ?></div>
                    </div>
                </div>
                
                <div class="price-box">
                    <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                    <?php if($product['stock_quantity'] > 0): ?>
                        <div class="availability in-stock"><i class="fas fa-check-circle"></i> In Stock (<?php echo $product['stock_quantity']; ?> available)</div>
                    <?php else: ?>
                        <div class="availability out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</div>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                </div>
                
                <div class="product-actions">
                    <div class="quantity">
                        <button class="qty-minus"><i class="fas fa-minus"></i></button>
                        <input type="number" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" class="qty-input">
                        <button class="qty-plus"><i class="fas fa-plus"></i></button>
                    </div>
                    
                    <button class="btn btn-primary add-to-cart" data-id="<?php echo $product['id']; ?>">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                    
                    <button class="btn btn-outline add-to-wishlist">
                        <i class="far fa-heart"></i> Add to Wishlist
                    </button>
                </div>
                
                <div class="product-meta-info">
                    <div class="meta-item">
                        <span>Category:</span>
                        <a href="products.php?category=<?php echo $product['category']; ?>"><?php echo ucfirst($product['category']); ?></a>
                    </div>
                    <div class="meta-item">
                        <span>Share:</span>
                        <div class="social-share">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-pinterest"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="product-tabs">
            <ul class="nav-tabs">
                <li class="active" data-tab="description">Description</li>
                <li data-tab="reviews">Reviews (24)</li>
                <li data-tab="shipping">Shipping & Returns</li>
            </ul>
            
            <div class="tab-content">
                <div class="tab-pane active" id="description">
                    <h3>Product Details</h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    
                    <h3>Care Instructions</h3>
                    <ul>
                        <li>Keep in a cool place away from direct sunlight</li>
                        <li>Mist lightly with water daily to maintain freshness</li>
                        <li>For garlands, store in a slightly damp cloth when not in use</li>
                        <li>Use within 2-3 days for optimal freshness</li>
                    </ul>
                </div>
                
                <div class="tab-pane" id="reviews">
                    <div class="review-form">
                        <h3>Write a Review</h3>
                        <form>
                            <div class="form-group">
                                <label>Your Rating</label>
                                <div class="rating-input">
                                    <i class="far fa-star" data-rating="
<?php include 'includes/footer.php'; ?>