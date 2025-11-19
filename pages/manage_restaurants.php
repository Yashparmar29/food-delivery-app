<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';

// Handle add restaurant
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_restaurant'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO restaurants (name, address, phone, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $address, $phone, $email);
    $stmt->execute();
    $stmt->close();
}

// Handle delete restaurant
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM restaurants WHERE id = $id");
}

// Get restaurants
$result = $conn->query("SELECT * FROM restaurants");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Restaurants</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="dashboard">
        <header>
            <h1>Manage Restaurants</h1>
            <a href="dashboard.php">Back to Dashboard</a>
        </header>
        <main>
            <h2>Add New Restaurant</h2>
            <form method="POST" action="">
                <input type="text" name="name" placeholder="Restaurant Name" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="phone" placeholder="Phone" required>
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit" name="add_restaurant">Add Restaurant</button>
            </form>

            <h2>Existing Restaurants</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </main>
    </div>
</body>
</html>
