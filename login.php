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
        <div class="auth-container animate__animated animate__fadeIn">
            <div class="auth-form">
                <h2>Welcome Back!</h2>
                <p class="auth-subtitle">Please login to your account to continue</p>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="mt-4">
                    <div class="form-group">
                        <label for="username">
                            <i class="fas fa-user"></i> Username or Email
                        </label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <div class="password-input">
                            <input type="password" id="password" name="password" class="form-control" required>
                            <button type="button" class="toggle-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </div>
                    
                    <div class="auth-links">
                        <a href="register.php">
                            <i class="fas fa-user-plus"></i> Create Account
                        </a>
                        <a href="forgot-password.php">
                            <i class="fas fa-key"></i> Forgot Password?
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="auth-image">
                <img src="assets/images/login-image.jpg" alt="Floral Login">
                <div class="auth-overlay"></div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>