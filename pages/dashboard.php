<?php
session_start();
// Temporarily bypass login check for testing
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: login.php");
//     exit();
// }
include '../config/db.php';

// Get some stats
$user_count = $conn->query("SELECT COUNT(*) as count FROM admins")->fetch_assoc()['count'];
$restaurant_count = $conn->query("SELECT COUNT(*) as count FROM restaurants")->fetch_assoc()['count'];
$order_count = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h1>Food Delivery Admin</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">🏠 Dashboard</a></li>
                    <li><a href="manage_users.php">👥 Manage Users</a></li>
                    <li><a href="manage_restaurants.php">🏪 Manage Restaurants</a></li>
                    <li><a href="manage_orders.php">📦 Manage Orders</a></li>
                    <li><a href="logout.php">🚪 Logout</a></li>
                </ul>
            </nav>
        </div>
        <div class="main-content">
            <header>
                <h1>Welcome to Dashboard</h1>
                <div class="user-info">
                    <span>👤 Admin</span>
                </div>
            </header>
            <main>
                <h2>Dashboard Overview</h2>
                <div class="stats">
                    <div class="stat">
                        <h3>Total Admins</h3>
                        <p><?php echo $user_count; ?></p>
                    </div>
                    <div class="stat">
                        <h3>Total Restaurants</h3>
                        <p><?php echo $restaurant_count; ?></p>
                    </div>
                    <div class="stat">
                        <h3>Total Orders</h3>
                        <p><?php echo $order_count; ?></p>
                    </div>
                </div>
            </main>
            <footer>
                &copy; 2023 Food Delivery Admin Panel. All rights reserved.
            </footer>
        </div>
    </div>
</body>
</html>
