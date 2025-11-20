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
        <header>
            <h1>Food Delivery Admin Panel</h1>
            <a href="logout.php">Logout</a>
        </header>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_restaurants.php">Manage Restaurants</a></li>
                <li><a href="manage_orders.php">Manage Orders</a></li>
            </ul>
        </nav>
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
    </div>
</body>
</html>
