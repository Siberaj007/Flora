<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Flora - Flowers, Garlands & Decorations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="preloader">
        <div class="flower-spinner">
            <div class="petal"></div>
            <div class="petal"></div>
            <div class="petal"></div>
            <div class="petal"></div>
        </div>
    </div>
    
    <header class="header <?php echo $current_page === 'index' ? 'header-transparent' : ''; ?>">
        <div class="announcement-bar">
            <div class="container">
                <div class="announcement-text">
                    <i class="fas fa-truck"></i> Free delivery for orders above $50!
                </div>
                <div class="header-contact">
                    <a href="tel:+1234567890"><i class="fas fa-phone"></i> +123 456 7890</a>
                    <a href="mailto:info@flora.com"><i class="fas fa-envelope"></i> info@flora.com</a>
                </div>
            </div>
        </div>
        
        <div class="main-header">
            <div class="container">
                <div class="header-container">
                    <div class="logo">
                        <a href="index.php">
                            <span class="logo-text">Flora</span>
                        </a>
                    </div>
                    
                    <nav class="main-nav">
                        <ul>
                            <li><a href="index.php" class="nav-link <?php echo $current_page === 'index' ? 'active' : ''; ?>">Home</a></li>
                            <li class="dropdown">
                                <a href="#" class="nav-link">Flowers <i class="fas fa-chevron-down"></i></a>
                                <div class="mega-menu">
                                    <div class="mega-menu-content">
                                        <div class="mega-menu-column">
                                            <h4>Categories</h4>
                                            <ul>
                                                <li><a href="products.php?category=flower&type=rose">Roses</a></li>
                                                <li><a href="products.php?category=flower&type=lily">Lilies</a></li>
                                                <li><a href="products.php?category=flower&type=orchid">Orchids</a></li>
                                            </ul>
                                        </div>
                                        <div class="mega-menu-column">
                                            <div class="featured-product">
                                                <img src="assets/images/featured-flower.jpg" alt="Featured">
                                                <div class="featured-content">
                                                    <h4>Special Offer</h4>
                                                    <p>20% off on Rose Bouquets</p>
                                                    <a href="#" class="btn btn-sm">Shop Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown">
                                <a href="products.php?category=garland">Garlands <i class="fas fa-chevron-down"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a href="products.php?category=garland">All Garlands</a></li>
                                    <li><a href="products.php?category=garland&type=rose">Rose Garlands</a></li>
                                    <li><a href="products.php?category=garland&type=jasmine">Jasmine Garlands</a></li>
                                    <li><a href="products.php?category=garland&type=marigold">Marigold Garlands</a></li>
                                </ul>
                            </li>
                            <li><a href="booking.php">Decorations</a></li>
                            <li><a href="#contact">Contact</a></li>
                        </ul>
                    </nav>
                    
                    <div class="header-actions">
                        <button class="search-trigger" aria-label="Search">
                            <i class="fas fa-search"></i>
                        </button>
                        
                        <div class="user-actions">
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <div class="dropdown user-dropdown">
                                    <button class="user-btn" aria-label="User menu">
                                        <i class="fas fa-user"></i>
                                        <span class="user-status"></span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <ul>
                                            <li><a href="account.php"><i class="fas fa-user-circle"></i> My Account</a></li>
                                            <li><a href="orders.php"><i class="fas fa-shopping-bag"></i> Orders</a></li>
                                            <li><a href="wishlist.php"><i class="fas fa-heart"></i> Wishlist</a></li>
                                            <li class="divider"></li>
                                            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                            <?php else: ?>
                                <a href="login.php" class="user-btn" title="Login">
                                    <i class="fas fa-user"></i>
                                </a>
                            <?php endif; ?>
                            
                            <a href="cart.php" class="cart-btn" aria-label="Cart">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-count"><?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : '0'; ?></span>
                            </a>
                        </div>
                        
                        <button class="mobile-menu-toggle" aria-label="Menu">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="search-overlay">
            <div class="container">
                <form action="search.php" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="Search for products..." 
                           id="search-input" autocomplete="off">
                    <button type="submit" aria-label="Search"><i class="fas fa-search"></i></button>
                    <button type="button" class="close-search" aria-label="Close search">
                        <i class="fas fa-times"></i>
                    </button>
                </form>
                <div class="search-suggestions" id="search-suggestions"></div>
            </div>
        </div>
    </header>

    <div class="mobile-menu">
        <div class="mobile-menu-header">
            <div class="logo">Flora</div>
            <div class="close-menu"><i class="fas fa-times"></i></div>
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li class="mobile-dropdown">
                <a href="products.php?category=flower">Flowers <i class="fas fa-chevron-down"></i></a>
                <ul class="mobile-submenu">
                    <li><a href="products.php?category=flower">All Flowers</a></li>
                    <li><a href="products.php?category=flower&type=rose">Roses</a></li>
                    <li><a href="products.php?category=flower&type=lily">Lilies</a></li>
                    <li><a href="products.php?category=flower&type=orchid">Orchids</a></li>
                </ul>
            </li>
            <li class="mobile-dropdown">
                <a href="products.php?category=garland">Garlands <i class="fas fa-chevron-down"></i></a>
                <ul class="mobile-submenu">
                    <li><a href="products.php?category=garland">All Garlands</a></li>
                    <li><a href="products.php?category=garland&type=rose">Rose Garlands</a></li>
                    <li><a href="products.php?category=garland&type=jasmine">Jasmine Garlands</a></li>
                    <li><a href="products.php?category=garland&type=marigold">Marigold Garlands</a></li>
                </ul>
            </li>
            <li><a href="booking.php">Decorations</a></li>
            <li><a href="#contact">Contact</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="account.php">My Account</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
    
    <main>