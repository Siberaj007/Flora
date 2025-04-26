<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flora - Flowers, Garlands & Decorations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
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
    
    <header class="header">
        <div class="container">
            <div class="header-container">
                <div class="logo">
                    <a href="index.php">Flora</a>
                </div>
                
                <nav class="main-nav">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li class="dropdown">
                            <a href="products.php?category=flower">Flowers <i class="fas fa-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="products.php?category=flower">All Flowers</a></li>
                                <li><a href="products.php?category=flower&type=rose">Roses</a></li>
                                <li><a href="products.php?category=flower&type=lily">Lilies</a></li>
                                <li><a href="products.php?category=flower&type=orchid">Orchids</a></li>
                            </ul>
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
                    <div class="search-box">
                        <input type="text" placeholder="Search...">
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    
                    <div class="user-actions">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="account.php" class="user-icon"><i class="fas fa-user"></i></a>
                        <?php else: ?>
                            <a href="login.php" class="user-icon"><i class="fas fa-user"></i></a>
                        <?php endif; ?>
                        
                        <a href="cart.php" class="cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count">
                                <?php 
                                if(isset($_SESSION['user_id'])) {
                                    require_once 'includes/db.php';
                                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
                                    $stmt->execute([$_SESSION['user_id']]);
                                    echo $stmt->fetchColumn();
                                } else {
                                    echo '0';
                                }
                                ?>
                            </span>
                        </a>
                    </div>
                    
                    <div class="mobile-menu-toggle">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
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