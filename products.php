<?php 
include 'includes/header.php';

require_once 'includes/db.php';

$category = $_GET['category'] ?? '';
$type = $_GET['type'] ?? '';

// Build the query based on filters
$sql = "SELECT * FROM products WHERE stock_quantity > 0";
$params = [];

if ($category) {
    $sql .= " AND category = ?";
    $params[] = $category;
}

if ($type) {
    $sql .= " AND name LIKE ?";
    $params[] = "%$type%";
}

// Add sorting
$sort = $_GET['sort'] ?? '';
$sortOptions = [
    'price_asc' => 'price ASC',
    'price_desc' => 'price DESC',
    'newest' => 'created_at DESC',
    'popular' => '(SELECT COUNT(*) FROM order_items WHERE order_items.product_id = products.id) DESC'
];

$sortSql = $sortOptions[$sort] ?? 'name ASC';
$sql .= " ORDER BY $sortSql";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get category title
$categoryTitles = [
    'flower' => 'Flowers',
    'garland' => 'Garlands',
    'decoration' => 'Decorations'
];

$categoryTitle = $categoryTitles[$category] ?? 'All Products';
$typeTitle = $type ? ucfirst(str_replace('-', ' ', $type)) : '';
?>

<section class="page-header">
    <div class="container">
        <h1><?php echo $typeTitle ? "$typeTitle $categoryTitle" : $categoryTitle; ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <?php if($category): ?>
                    <li class="breadcrumb-item"><a href="products.php?category=<?php echo $category; ?>"><?php echo $categoryTitle; ?></a></li>
                <?php endif; ?>
                <?php if($typeTitle): ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $typeTitle; ?></li>
                <?php endif; ?>
            </ol>
        </nav>
    </div>
</section>

<section class="products-section">
    <div class="container">
        <div class="products-filter">
            <div class="filter-left">
                <div class="filter-item">
                    <label>Sort By:</label>
                    <select id="sort-products" class="form-select">
                        <option value="">Default</option>
                        <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest</option>
                        <option value="popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                    </select>
                </div>
            </div>
            <div class="filter-right">
                <div class="view-options">
                    <button class="view-option active" data-view="grid"><i class="fas fa-th"></i></button>
                    <button class="view-option" data-view="list"><i class="fas fa-list"></i></button>
                </div>
            </div>
        </div>
        
        <div class="product-grid">
            <?php if(count($products) > 0): ?>
                <?php foreach($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo $product['image_url'] ?: 'assets/images/product-placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="product-actions">
                            <button class="quick-view" data-id="<?php echo $product['id']; ?>"><i class="fas fa-eye"></i></button>
                            <button class="add-to-wishlist"><i class="far fa-heart"></i></button>
                        </div>
                        <?php if($product['stock_quantity'] < 5): ?>
                            <span class="stock-badge">Only <?php echo $product['stock_quantity']; ?> left</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-content">
                        <h3><a href="product-detail.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                        <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                        <div class="product-meta">
                            <span><i class="fas fa-tag"></i> <?php echo ucfirst($product['category']); ?></span>
                            <?php if($product['stock_quantity'] > 0): ?>
                                <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock</span>
                            <?php else: ?>
                                <span class="out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn-sm add-to-cart" data-id="<?php echo $product['id']; ?>">Add to Cart</button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products">
                    <i class="fas fa-leaf"></i>
                    <h3>No products found</h3>
                    <p>We couldn't find any products matching your criteria.</p>
                    <a href="products.php" class="btn btn-primary">View All Products</a>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if(count($products) > 0): ?>
        <div class="pagination">
            <a href="#" class="page-item disabled"><i class="fas fa-chevron-left"></i></a>
            <a href="#" class="page-item active">1</a>
            <a href="#" class="page-item">2</a>
            <a href="#" class="page-item">3</a>
            <a href="#" class="page-item"><i class="fas fa-chevron-right"></i></a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>