<?php 
require_once 'includes/config.php';
require_once 'includes/header.php';
require_once 'includes/db.php';

// Error handling for products
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE stock_quantity > 0 ORDER BY RAND() LIMIT 4");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $products = [];
}

// Add status check
$pageTitle = 'Home';
$productsAvailable = !empty($products);

// Hero slider content
$heroSlides = [
    [
        'image' => 'assets/images/hero1.jpg',
        'title' => 'Beautiful Flowers for Every Occasion',
        'description' => 'Fresh flowers handcrafted with love',
        'link' => 'products.php?category=flower',
        'button' => 'Shop Now'
    ],
    [
        'image' => 'assets/images/hero2.jpg',
        'title' => 'Exquisite Garlands for Your Events',
        'description' => 'Traditional and modern designs available',
        'link' => 'products.php?category=garland',
        'button' => 'Explore Garlands'
    ],
    [
        'image' => 'assets/images/hero3.jpg',
        'title' => 'Professional Event Decorations',
        'description' => 'Let us make your event unforgettable',
        'link' => 'booking.php',
        'button' => 'Book Now'
    ]
];
?>

<!-- Hero Section with Enhanced Animation -->
<section class="hero parallax">
    <div class="hero-slider">
        <?php foreach ($heroSlides as $index => $slide): ?>
        <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>" 
             style="background-image: url('<?php echo htmlspecialchars($slide['image']); ?>');">
            <div class="container">
                <div class="hero-content">
                    <h1><?php echo htmlspecialchars($slide['title']); ?></h1>
                    <p><?php echo htmlspecialchars($slide['description']); ?></p>
                    <a href="<?php echo htmlspecialchars($slide['link']); ?>" 
                       class="btn btn-primary"><?php echo htmlspecialchars($slide['button']); ?></a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="slider-navigation">
        <div class="prev-slide"><i class="fas fa-chevron-left"></i></div>
        <div class="next-slide"><i class="fas fa-chevron-right"></i></div>
        <div class="slider-dots">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
    <div class="hero-overlay"></div>
    <div class="scroll-indicator">
        <div class="mouse">
            <div class="wheel"></div>
        </div>
        <p>Scroll to explore</p>
    </div>
</section>

<!-- Add floating action button -->
<div class="floating-action-btn" title="Contact Us">
    <i class="fas fa-envelope"></i>
</div>

<section id="categories" class="categories">
    <div class="container">
        <h2 class="section-title fade-in">Our Categories</h2>
        <div class="category-grid">
            <div class="category-card scale-up">
                <div class="category-image">
                    <img src="assets/images/category1.jpg" alt="Flowers">
                    <div class="overlay"></div>
                </div>
                <div class="category-content">
                    <h3>Flowers</h3>
                    <p>Fresh and vibrant flowers for all occasions</p>
                    <a href="products.php?category=flower" class="btn btn-outline">Shop Flowers</a>
                </div>
            </div>
            <div class="category-card scale-up delay-1">
                <div class="category-image">
                    <img src="assets/images/category2.jpg" alt="Garlands">
                    <div class="overlay"></div>
                </div>
                <div class="category-content">
                    <h3>Garlands</h3>
                    <p>Traditional and decorative garlands</p>
                    <a href="products.php?category=garland" class="btn btn-outline">Shop Garlands</a>
                </div>
            </div>
            <div class="category-card scale-up delay-2">
                <div class="category-image">
                    <img src="assets/images/category3.jpg" alt="Decorations">
                    <div class="overlay"></div>
                </div>
                <div class="category-content">
                    <h3>Decorations</h3>
                    <p>Professional event decoration services</p>
                    <a href="booking.php" class="btn btn-outline">Book Service</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="features">
    <div class="container">
        <h2 class="section-title">Why Choose Us</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-truck"></i>
                <h3>Fast Delivery</h3>
                <p>Same day delivery available</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-flower"></i>
                <h3>Fresh Flowers</h3>
                <p>Handpicked daily from local gardens</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-hand-holding-heart"></i>
                <h3>Expert Care</h3>
                <p>Professional florists at your service</p>
            </div>
        </div>
    </div>
</section>

<section id="featured-products" class="featured-products">
    <div class="container">
        <h2 class="section-title fade-in">Featured Products</h2>
        <div class="product-grid">
            <?php if (empty($products)): ?>
                <div class="no-products">
                    <p>No products available at the moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                <div class="product-card fade-in">
                    <div class="product-image">
                        <img src="<?php echo $product['image_url'] ?: 'assets/images/product-placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="product-actions">
                            <button class="quick-view" data-id="<?php echo $product['id']; ?>"><i class="fas fa-eye"></i></button>
                            <button class="add-to-wishlist"><i class="far fa-heart"></i></button>
                        </div>
                    </div>
                    <div class="product-content">
                        <h3><a href="product-detail.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                        <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                        <button class="btn btn-sm add-to-cart" data-id="<?php echo $product['id']; ?>">Add to Cart</button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="products.php" class="btn btn-primary">View All Products</a>
        </div>
    </div>
</section>

<section class="testimonials">
    <div class="container">
        <h2 class="section-title fade-in">What Our Customers Say</h2>
        <div class="testimonial-slider">
            <div class="testimonial active">
                <div class="testimonial-content">
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>"The flowers were absolutely stunning and arrived fresh. The garlands for our wedding were beyond beautiful!"</p>
                    <div class="author">
                        <img src="assets/images/testimonial1.jpg" alt="Sarah J.">
                        <div>
                            <h4>Sarah J.</h4>
                            <span>Wedding Client</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial">
                <div class="testimonial-content">
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>"Professional service and the event decorations were exactly what we envisioned. Highly recommend!"</p>
                    <div class="author">
                        <img src="assets/images/testimonial2.jpg" alt="Michael T.">
                        <div>
                            <h4>Michael T.</h4>
                            <span>Corporate Event</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial">
                <div class="testimonial-content">
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p>"Regular customer for flowers. Always fresh and beautifully arranged. Their jasmine garlands are my favorite."</p>
                    <div class="author">
                        <img src="assets/images/testimonial3.jpg" alt="Priya K.">
                        <div>
                            <h4>Priya K.</h4>
                            <span>Regular Customer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="testimonial-dots">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</section>

<section class="cta">
    <div class="container">
        <div class="cta-content fade-in">
            <h2>Need Help With Your Event Decorations?</h2>
            <p>Our team of professionals will work with you to create the perfect floral arrangements for your special occasion.</p>
            <a href="booking.php" class="btn btn-primary">Book a Consultation</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>