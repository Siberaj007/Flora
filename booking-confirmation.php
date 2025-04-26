<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['booking_id'])) {
    header("Location: index.php");
    exit();
}

$bookingId = $_SESSION['booking_id'];
$userId = $_SESSION['user_id'];

// Get booking details
$stmt = $pdo->prepare("
    SELECT b.*, u.full_name, u.email, u.phone
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    WHERE b.id = ? AND b.user_id = ?
");
$stmt->execute([$bookingId, $userId]);
$booking = $stmt->fetch();

if (!$booking) {
    echo "Booking not found.";
    exit();
}

// Clear the booking ID from session so it can't be refreshed
unset($_SESSION['booking_id']);

include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Booking Confirmation</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Booking Confirmation</li>
            </ol>
        </nav>
    </div>
</section>

<section class="confirmation-section">
    <div class="container">
        <div class="confirmation-card">
            <div class="confirmation-header">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Your Booking is Confirmed!</h2>
                <p>We've received your decoration booking request. Our team will contact you shortly to discuss the details.</p>
            </div>
            
            <div class="confirmation-details">
                <div class="detail-row">
                    <div class="detail-col">
                        <h3>Booking Number</h3>
                        <p>#<?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></p>
                    </div>
                    <div class="detail-col">
                        <h3>Event Type</h3>
                        <p><?php echo ucfirst($booking['event_type']); ?></p>
                    </div>
                    <div class="detail-col">
                        <h3>Event Date</h3>
                        <p><?php echo date('F j, Y', strtotime($booking['event_date'])); ?></p>
                    </div>
                    <div class="detail-col">
                        <h3>Decoration Type</h3>
                        <p><?php echo ucfirst(str_replace('_', ' ', $booking['decoration_type'])); ?></p>
                    </div>
                </div>
                
                <div class="detail-info">
                    <h3>Event Location</h3>
                    <p><?php echo nl2br(htmlspecialchars($booking['location'])); ?></p>
                </div>
                
                <?php if (!empty($booking['additional_notes'])): ?>
                <div class="detail-info">
                    <h3>Additional Notes</h3>
                    <p><?php echo nl2br(htmlspecialchars($booking['additional_notes'])); ?></p>
                </div>
                <?php endif; ?>
                
                <div class="detail-info">
                    <h3>Contact Information</h3>
                    <p>
                        <strong>Name:</strong> <?php echo htmlspecialchars($booking['full_name']); ?><br>
                        <strong>Email:</strong> <?php echo htmlspecialchars($booking['email']); ?><br>
                        <strong>Phone:</strong> <?php echo htmlspecialchars($booking['phone']); ?>
                    </p>
                </div>
            </div>
            
            <div class="confirmation-actions">
                <a href="booking.php" class="btn btn-outline">Book Another Event</a>
                <a href="account.php?tab=bookings" class="btn btn-primary">View My Bookings</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>