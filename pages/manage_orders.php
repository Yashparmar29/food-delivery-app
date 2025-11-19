<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';

// Handle add order
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_order'])) {
    $customer_name = $_POST['customer_name'];
    $restaurant_id = $_POST['restaurant_id'];
    $order_details = $_POST['order_details'];
    $total_amount = $_POST['total_amount'];

    $stmt = $conn->prepare("INSERT INTO orders (customer_name, restaurant_id, order_details, total_amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $customer_name, $restaurant_id, $order_details, $total_amount);
    $stmt->execute();
    $stmt->close();
}

// Handle delete order
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM orders WHERE id = $id");
}

// Get orders with restaurant names
$result = $conn->query("SELECT orders.*, restaurants.name as restaurant_name FROM orders LEFT JOIN restaurants ON orders.restaurant_id = restaurants.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="dashboard">
        <header>
            <h1>Manage Orders</h1>
            <a href="dashboard.php">Back to Dashboard</a>
        </header>
        <main>
            <h2>Add New Order</h2>
            <form method="POST" action="">
                <input type="text" name="customer_name" placeholder="Customer Name" required>
                <select name="restaurant_id" required>
                    <option value="">Select Restaurant</option>
                    <?php
                    $restaurants = $conn->query("SELECT id, name FROM restaurants");
                    while ($restaurant = $restaurants->fetch_assoc()) {
                        echo "<option value='{$restaurant['id']}'>{$restaurant['name']}</option>";
                    }
                    ?>
                </select>
                <textarea name="order_details" placeholder="Order Details" required></textarea>
                <input type="number" step="0.01" name="total_amount" placeholder="Total Amount" required>
                <button type="submit" name="add_order">Add Order</button>
            </form>

            <h2>Existing Orders</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Restaurant</th>
                    <th>Details</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['restaurant_name']; ?></td>
                    <td><?php echo $row['order_details']; ?></td>
                    <td>$<?php echo $row['total_amount']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </main>
    </div>
</body>
</html>
