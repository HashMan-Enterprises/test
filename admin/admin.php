<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        .container {
            padding: 20px;
            text-align: center;
        }
        .admin-options {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .admin-option {
            padding: 15px 30px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.2em;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
        }
        .admin-option:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <div class="container">
        <h2>Welcome, Admin!</h2>
        <p>Choose an option below to manage the system:</p>
        <div class="admin-options">
            <a href="users.php" class="admin-option">Manage Users</a>
            <a href="analytics.php" class="admin-option">View Analytics</a>
            <a href="settings.php" class="admin-option">System Settings</a>
            <a href="logs.php" class="admin-option">View Logs</a>
        </div>
    </div>
</body>
</html>