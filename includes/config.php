<?php
// Site Configuration
define('SITE_NAME', 'Flora');
define('SITE_URL', 'http://localhost/flora');
define('ADMIN_EMAIL', 'admin@flora.com');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'flora_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Payment Configuration
define('STRIPE_PUBLIC_KEY', 'your_stripe_public_key');
define('STRIPE_SECRET_KEY', 'your_stripe_secret_key');
define('PAYPAL_CLIENT_ID', 'your_paypal_client_id');
define('PAYPAL_SECRET', 'your_paypal_secret');

// Email Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your_email@gmail.com');
define('SMTP_PASS', 'your_email_password');

// File Upload Configuration
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Session Configuration
ini_set('session.cookie_lifetime', 86400); // 24 hours
ini_set('session.gc_maxlifetime', 86400);

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('UTC');

// Security
define('HASH_SALT', 'your_random_salt_string');
?>
