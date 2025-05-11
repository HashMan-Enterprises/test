<?php
require_once '../config/db_connection.php';

// Check database connection status
$db_status = $link->connect_error ? 'Disconnected' : 'Connected';

// Fetch subscribers from the database
$query = "SELECT id, first_name, last_name, email, phone, status, created_at, token FROM subscribers";
$result = $link->query($query);

// Initialize messages
$remove_message = $resend_message = null;
$action_performed = false; // Flag to check if any action was performed

// Handle bulk removal form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_ids'])) {
    $remove_ids = $_POST['remove_ids']; // Array of selected IDs

    // Prepare the DELETE query
    $placeholders = implode(',', array_fill(0, count($remove_ids), '?'));
    $stmt = $link->prepare("DELETE FROM subscribers WHERE id IN ($placeholders)");

    if ($stmt) {
        $stmt->bind_param(str_repeat('i', count($remove_ids)), ...$remove_ids);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $remove_message = "Selected subscribers have been removed successfully.";
        } else {
            $remove_message = "No subscribers were removed.";
        }
    } else {
        $remove_message = "Failed to process removal request: " . $link->error;
    }

    $action_performed = true; // Set flag to indicate an action was performed
}

// Handle resend confirmation email form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend_ids'])) {
    $resend_ids = $_POST['resend_ids']; // Array of selected IDs

    // Fetch subscriber details for the selected IDs
    $placeholders = implode(',', array_fill(0, count($resend_ids), '?'));
    $stmt = $link->prepare("SELECT email, first_name, token FROM subscribers WHERE id IN ($placeholders)");

    if ($stmt) {
        $stmt->bind_param(str_repeat('i', count($resend_ids)), ...$resend_ids);
        $stmt->execute();
        $result = $stmt->get_result();

        $emails_sent = 0;

        while ($row = $result->fetch_assoc()) {
            $email = $row['email'];
            $firstName = $row['first_name'];
            $token = $row['token'];

            // Resend confirmation email
            $subject = "Resend: Confirm Your Subscription";
            $message = "Hi $firstName,\n\nPlease click the link below to confirm your subscription:\n";
            $message .= "http://cryptofreedomnow.com/public/confirm.php?token=$token";
            $headers = "From: no-reply@cryptofreedomnow.com";

            if (mail($email, $subject, $message, $headers)) {
                $emails_sent++;
            }
        }

        $resend_message = "$emails_sent confirmation email(s) have been successfully resent.";
    } else {
        $resend_message = "Failed to process resend request: " . $link->error;
    }

    $action_performed = true; // Set flag to indicate an action was performed
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../styles/admin-styles.css">
    <?php if ($action_performed): ?>
    <script>
        // Automatically refresh the page after 2 seconds
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    </script>
    <?php endif; ?>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <div class="container">
        <!-- Database Connection Status -->
        <div class="status-box">
            <h2>Database Connection Status</h2>
            <p>Status: <strong style="color: <?php echo $db_status === 'Connected' ? 'green' : 'red'; ?>">
                <?php echo $db_status; ?></strong>
            </p>
        </div>

        <!-- Subscribers List with Actions -->
        <form method="POST" action="">
            <div class="table-container">
                <h2>Subscribers List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Select (Remove)</th>
                            <th>Select (Resend)</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Subscribed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="remove-checkbox" name="remove_ids[]" value="<?php echo $row['id']; ?>">
                                    </td>
                                    <td>
                                        <input type="checkbox" class="resend-checkbox" name="resend_ids[]" value="<?php echo $row['id']; ?>">
                                    </td>
                                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td class="status-<?php echo htmlspecialchars($row['status']); ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No subscribers found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Action Boxes -->
            <div class="action-box">
                <button type="submit" name="action" value="remove" class="remove-btn">Remove Selected Contacts</button>
                <button type="submit" name="action" value="resend" class="resend-btn">Resend Confirmation Email</button>
            </div>
        </form>

        <!-- Display Messages -->
        <?php if ($remove_message): ?>
            <div class="message"><?php echo $remove_message; ?></div>
        <?php endif; ?>

        <?php if ($resend_message): ?>
            <div class="message"><?php echo $resend_message; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>