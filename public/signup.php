<?php
require_once '../config/db_connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? null;

    // Check if the email already exists in the database
    $stmt = $link->prepare("SELECT status FROM subscribers WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the status of the email
            $row = $result->fetch_assoc();
            $status = $row['status'];

            // Handle different statuses
            switch ($status) {
                case 'confirmed':
                    // Redirect if already confirmed
                    header("Location: /public/already_subscribed.php");
                    exit();
                case 'pending':
                    // Email is pending confirmation
                    echo "<p style='color: orange;'>This email is pending confirmation. Please check your email to confirm.</p>";
                    exit();
                default:
                    // Handle other statuses (e.g., unsubscribed)
                    echo "<p style='color: red;'>This email was previously unsubscribed. Please contact support if this is an error.</p>";
                    exit();
            }
        }
    }

    // If the email doesn't exist, insert it into the database
    $token = bin2hex(random_bytes(16)); // Generate a unique token
    $stmt = $link->prepare("INSERT INTO subscribers (first_name, last_name, email, phone, token, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    if ($stmt) {
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $phone, $token);
        if ($stmt->execute()) {
            // Send confirmation email
            $subject = "Please Confirm Your Subscription";
            $message = "Hi $firstName,\n\nClick the link below to confirm your subscription:\n";
            $message .= "http://cryptofreedomnow.com/public/confirm.php?token=$token";
            $headers = "From: no-reply@cryptofreedomnow.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8";

            if (mail($email, $subject, $message, $headers)) {
                echo "<p style='color: green;'>A confirmation email has been sent to your address. Please check your email to confirm your subscription.</p>";
            } else {
                echo "<p style='color: red;'>Failed to send confirmation email.</p>";
            }
        } else {
            echo "<p style='color: red;'>Failed to insert data into the database: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Database query failed: " . $link->error . "</p>";
    }
}
?>