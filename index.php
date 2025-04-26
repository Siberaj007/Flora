<?php include 'includes/header.php'; ?>

<section class="hero">
    <div class="hero-slider">
        <div class="slide active" style="background-image: url('assets/images/hero1.jpg');">
            <div class="container">
                <div class="hero-content">
                    <h1 class="slide-up">Beautiful Flowers for Every Occasion</h1>
                    <p class="slide-up delay-1">Fresh flowers handcrafted with love</p>
                    <a href="products.php?category=flower" class="btn btn-primary slide-up delay-2">Shop Now</a>
                </div>
            </div>
        </div>
        <div class="slide" style="background-image: url('assets/images/hero2.jpg');">
            <div class="container">
                <div class="hero-content">
                    <h1>Exquisite Garlands for Your Events</h1>
                    <p>Traditional and modern designs available</p>
                    <a href="products.php?category=garland" class="btn btn-primary">Explore Garlands</a>
                </div>
            </div>
        </div>
        <div class="slide" style="background-image: url('assets/images/hero3.jpg');">
            <div class="container">
                <div class="hero-content">
                    <h1>Professional Event Decorations</h1>
                    <p>Let us make your event unforgettable</p>
                    <a href="booking.php" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>
    </div>
    <div class="slider-controls">
        <button class="prev-slide"><i class="fas fa-chevron-left"></i></button>
        <button class="next-slide"><i class="fas fa-chevron-right"></i></button>
    </div>
</section>

<section class="categories">
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

<section class="featured-products">
    <div class="container">
        <h2 class="section-title fade-in">Featured Products</h2>
        <div class="product-grid">
            <?php
            require_once 'includes/db.php';
            $stmt = $pdo->prepare("SELECT * FROM products WHERE stock_quantity > 0 ORDER BY RAND() LIMIT 4");
            $stmt->execute();
            $products = $stmt->fetchAll();
            
            foreach($products as $product): 
                $animationDelay = 0.1 * ($loop->index % 4);
            ?>
            <div class="product-card fade-in" style="animation-delay: <?php echo $animationDelay; ?>s">
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
                    <button class="btn btn-sm add-to-cart" data-id="<?php echo $product['id']; ?>">Add to Cart</button>
                </div>
            </div>
            <?php endforeach; ?>
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
        <div class="cta-content">
            <h2 class="fade-in">Need Help With Your Event Decorations?</h2>
            <p class="fade-in delay-1">Our team of professionals will work with you to create the perfect floral arrangements for your special occasion.</p>
            <a href="booking.php" class="btn btn-primary fade-in delay-2">Book a Consultation</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>