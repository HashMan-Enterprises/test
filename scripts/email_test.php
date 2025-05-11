<?php
/**
 * Script to test email functionality.
 * Replace $test_email with the email address you want to test.
 */

$test_email = "test@example.com";
$subject = "Test Email";
$message = "This is a test email sent from the server.";
$headers = "From: connect@cryptofreedomnow.com";

if (mail($test_email, $subject, $message, $headers)) {
    echo "Test email sent successfully to $test_email.";
} else {
    echo "Failed to send test email.";
}
?>