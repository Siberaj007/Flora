<?php
$host = 'localhost';
$dbname = 'flora_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Create tables if they don't exist
$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100),
        address TEXT,
        phone VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        category ENUM('garland', 'flower', 'decoration') NOT NULL,
        image_url VARCHAR(255),
        stock_quantity INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        event_type VARCHAR(50) NOT NULL,
        event_date DATE NOT NULL,
        location TEXT NOT NULL,
        decoration_type VARCHAR(100) NOT NULL,
        additional_notes TEXT,
        status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );

    CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        total_amount DECIMAL(10,2) NOT NULL,
        status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
        payment_method VARCHAR(50),
        shipping_address TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );

    CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        product_id INT,
        quantity INT NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    );

    CREATE TABLE IF NOT EXISTS cart (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        product_id INT,
        quantity INT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    );

    CREATE TABLE IF NOT EXISTS wishlist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        product_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (product_id) REFERENCES products(id),
        UNIQUE KEY unique_wishlist (user_id, product_id)
    );
");

// Insert sample data if tables are empty
function tableIsEmpty($pdo, $table) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
    return $stmt->fetchColumn() == 0;
}

if (tableIsEmpty($pdo, 'products')) {
    $pdo->exec("
        INSERT INTO products (name, description, price, category, image_url, stock_quantity) VALUES
        ('Rose Garland', 'Beautiful fresh rose garland for weddings and events', 49.99, 'garland', 'assets/images/rose-garland.jpg', 20),
        ('Jasmine Garland', 'Fragrant jasmine garland for traditional ceremonies', 39.99, 'garland', 'assets/images/jasmine-garland.jpg', 15),
        ('Marigold Garland', 'Bright marigold garland for festive decorations', 29.99, 'garland', 'assets/images/marigold-garland.jpg', 30),
        ('Red Roses Bouquet', '12 fresh red roses bouquet with greenery', 35.99, 'flower', 'assets/images/red-roses.jpg', 25),
        ('White Lilies', 'Elegant white lilies bouquet', 42.99, 'flower', 'assets/images/white-lilies.jpg', 18),
        ('Orchid Arrangement', 'Exotic orchid arrangement in ceramic vase', 55.99, 'flower', 'assets/images/orchids.jpg', 12),
        ('Wedding Arch Decoration', 'Full wedding arch floral decoration service', 299.99, 'decoration', 'assets/images/wedding-arch.jpg', 5),
        ('Table Centerpieces', 'Set of 10 floral table centerpieces', 199.99, 'decoration', 'assets/images/centerpieces.jpg', 8);
    ");
}

if (tableIsEmpty($pdo, 'users')) {
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("
        INSERT INTO users (username, email, password, full_name, address, phone) VALUES
        ('admin', 'admin@flora.com', '$password', 'Admin User', '123 Flower St, Garden City', '+1234567890');
    ");
}
?>