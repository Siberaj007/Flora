<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=booking");
    exit();
}

$userId = $_SESSION['user_id'];

// Validate required fields
$required = ['event_type', 'event_date', 'location', 'decoration_type'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['error'] = "Please fill all required fields.";
        header("Location: booking.php");
        exit();
    }
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO bookings (
            user_id, 
            event_type, 
            event_date, 
            location, 
            decoration_type, 
            additional_notes,
            status
        ) VALUES (?, ?, ?, ?, ?, ?, 'pending')
    ");
    
    $stmt->execute([
        $userId,
        $_POST['event_type'],
        $_POST['event_date'] . ' ' . ($_POST['event_time'] ?? '12:00:00'),
        $_POST['location'],
        $_POST['decoration_type'],
        $_POST['additional_notes'] ?? null
    ]);
    
    $bookingId = $pdo->lastInsertId();
    
    // Send confirmation email (in a real app)
    // mail($userEmail, "Booking Confirmation", "Your booking #$bookingId has been received.");
    
    $_SESSION['booking_id'] = $bookingId;
    header("Location: booking-confirmation.php");
    exit();
} catch (PDOException $e) {
    $_SESSION['error'] = "Booking failed. Please try again.";
    header("Location: booking.php");
    exit();
}
?>