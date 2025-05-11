<?php
require_once '../config/db_connection.php';

// Initialize variables and messages
$token = $_GET['token'] ?? '';
$success_message = $error_message = "";

// Check if token is provided
if (!empty($token)) {
    // Verify token in the database
    $stmt = $link->prepare("SELECT id FROM subscribers WHERE token = ? AND status = 'pending'");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update subscriber status to confirmed
        $stmt = $link->prepare("UPDATE subscribers SET status = 'confirmed', confirmed_at = NOW() WHERE token = ?");
        $stmt->bind_param("s", $token);

        if ($stmt->execute()) {
            $success_message = "Thank you for confirming your subscription!";
        } else {
            $error_message = "Failed to confirm your subscription. Please try again later.";
        }
    } else {
        $error_message = "Invalid or expired token.";
    }
} else {
    $error_message = "No token provided.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Subscription</title>
    <link rel="stylesheet" href="../style/signup-styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Subscription Confirmation</h1>
        <?php if (!empty($success_message)): ?>
            <p class="message success"><?php echo $success_message; ?></p>
        <?php elseif (!empty($error_message)): ?>
            <p class="message error"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>