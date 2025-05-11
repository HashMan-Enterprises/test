<?php
/**
 * Database Connection Script
 * Handles connection to the MySQL database securely.
 */

// Database credentials
$host_name = 'db5017802025.hosting-data.io';
$database = 'dbs14205220';
$user_name = 'dbu868427';
$password = 'slowturtle@2025';

// Create a new MySQLi connection
$link = new mysqli($host_name, $user_name, $password, $database);

// Check for connection errors
if ($link->connect_error) {
    // Log connection error for debugging (do not expose sensitive details to users)
    error_log('Database connection failed: ' . $link->connect_error);
    die('<p>We are currently experiencing technical difficulties. Please try again later.</p>');
}

// Set character set to UTF-8 (important for handling special characters)
if (!$link->set_charset("utf8")) {
    error_log('Error loading character set utf8: ' . $link->error);
    die('<p>We are currently experiencing technical difficulties. Please try again later.</p>');
}
?>