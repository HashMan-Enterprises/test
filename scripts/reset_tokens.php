<?php
require_once '../config/db_connection.php';

/**
 * Script to reset tokens for all subscribers.
 * Use with caution, as this will invalidate all existing tokens.
 */

$query = "UPDATE subscribers SET token = ? WHERE id = ?";
$stmt = $link->prepare($query);

if ($stmt) {
    $query = "SELECT id FROM subscribers";
    $result = $link->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $new_token = bin2hex(random_bytes(32));
            $id = $row['id'];

            $stmt->bind_param("si", $new_token, $id);
            $stmt->execute();
        }
        echo "All tokens have been reset successfully.";
    } else {
        echo "No subscribers found.";
    }
} else {
    echo "Failed to prepare the statement: " . $link->error;
}
?>