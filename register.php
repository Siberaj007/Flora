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
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $fullName = trim($_POST['full_name']);
    
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Please fill all required fields';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        // Check if username or email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        
        if ($stmt->fetch()) {
            $error = 'Username or email already exists';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name) VALUES (:username, :email, :password, :full_name)");
            
            if ($stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword, 'full_name' => $fullName])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}

include 'includes/header.php';
?>

<section class="auth-section">
    <div class="container">
        <div class="auth-container animate__animated animate__fadeIn">
            <div class="auth-form">
                <h2>Create Account</h2>
                <p class="auth-subtitle">Join our floral community today</p>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="mt-4">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="username">
                                <i class="fas fa-user"></i> Username *
                            </label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email *
                            </label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="full_name">
                            <i class="fas fa-user-circle"></i> Full Name
                        </label>
                        <input type="text" id="full_name" name="full_name" class="form-control">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password">
                                <i class="fas fa-lock"></i> Password *
                            </label>
                            <div class="password-input">
                                <input type="password" id="password" name="password" class="form-control" required>
                                <button type="button" class="toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="confirm_password">
                                <i class="fas fa-lock"></i> Confirm Password *
                            </label>
                            <div class="password-input">
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                <button type="button" class="toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-user-plus"></i> Create Account
                        </button>
                    </div>
                    
                    <div class="auth-links">
                        <a href="login.php">
                            <i class="fas fa-sign-in-alt"></i> Already have an account? Login
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="auth-image">
                <img src="assets/images/register-image.jpg" alt="Floral Register">
                <div class="auth-overlay"></div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>