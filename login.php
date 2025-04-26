<?php
session_start();
require_once 'includes/db.php'; // Ensure database connection is included

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Redirect to the requested page or home
            $redirect = $_GET['redirect'] ?? 'index.php';
            header("Location: $redirect");
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    }
}

include 'includes/header.php';
?>

<section class="auth-section">
    <div class="container">
        <div class="auth-container">
            <div class="auth-form">
                <h2>Login to Your Account</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username or Email</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                    
                    <div class="auth-links">
                        <a href="register.php">Don't have an account? Register</a>
                        <a href="forgot-password.php">Forgot password?</a>
                    </div>
                </form>
            </div>
            
            <div class="auth-image">
                <img src="assets/images/login-image.jpg" alt="Floral Arrangement">
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>