<?php
session_start();
// Temporarily bypass login check for testing
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: login.php");
//     exit();
// }
include '../config/db.php';

// Get stats
$restaurant_count = $conn->query("SELECT COUNT(*) as count FROM restaurants")->fetch_assoc()['count'];
$order_count = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$client_count = $conn->query("SELECT COUNT(DISTINCT customer_name) as count FROM orders")->fetch_assoc()['count'];
$revenue = $conn->query("SELECT SUM(total_amount) as total FROM orders")->fetch_assoc()['total'] ?? 0;

// Monthly revenue data for chart
$monthly_revenue = [];
$result = $conn->query("SELECT MONTH(created_at) as month, SUM(total_amount) as total FROM orders WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP BY MONTH(created_at)");
while ($row = $result->fetch_assoc()) {
    $monthly_revenue[$row['month']] = $row['total'];
}

// Order summary by status
$order_summary = [];
$result = $conn->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
while ($row = $result->fetch_assoc()) {
    $order_summary[$row['status']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <div class="sidebar-header">
                <h1>Food Delivery</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php" class="active"><i class="icon">🏠</i> Dashboard</a></li>
                    <li><a href="manage_users.php"><i class="icon">👥</i> Manage Users</a></li>
                    <li><a href="manage_restaurants.php"><i class="icon">🏪</i> Restaurants</a></li>
                    <li><a href="manage_orders.php"><i class="icon">📦</i> Orders</a></li>
                    <li><a href="logout.php"><i class="icon">🚪</i> Logout</a></li>
                </ul>
            </nav>
        </div>
        <div class="main-content">
            <header>
                <h1>Dashboard</h1>
                <div class="user-info">
                    <img src="https://via.placeholder.com/40" alt="Avatar" class="avatar">
                    <span>Admin</span>
                </div>
            </header>
            <main>
                <div class="stats">
                    <div class="stat-card">
                        <div class="stat-icon">🍽️</div>
                        <div class="stat-content">
                            <h3>Total Menus</h3>
                            <p><?php echo $restaurant_count; ?></p>
                            <div class="progress-bar">
                                <div class="progress" style="width: <?php echo min($restaurant_count / 10 * 100, 100); ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📦</div>
                        <div class="stat-content">
                            <h3>Orders</h3>
                            <p><?php echo $order_count; ?></p>
                            <div class="progress-bar">
                                <div class="progress" style="width: <?php echo min($order_count / 50 * 100, 100); ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-content">
                            <h3>Clients</h3>
                            <p><?php echo $client_count; ?></p>
                            <div class="progress-bar">
                                <div class="progress" style="width: <?php echo min($client_count / 20 * 100, 100); ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">💰</div>
                        <div class="stat-content">
                            <h3>Revenue</h3>
                            <p>$<?php echo number_format($revenue, 2); ?></p>
                            <div class="progress-bar">
                                <div class="progress" style="width: <?php echo min($revenue / 1000 * 100, 100); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="charts">
                    <div class="chart-container">
                        <h3>Revenue Overview</h3>
                        <canvas id="revenueChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <h3>Order Summary</h3>
                        <canvas id="orderChart"></canvas>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = <?php echo json_encode(array_values($monthly_revenue)); ?>;
        const revenueLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueData,
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });

        // Order Summary Chart
        const orderCtx = document.getElementById('orderChart').getContext('2d');
        const orderData = <?php echo json_encode($order_summary); ?>;
        new Chart(orderCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(orderData),
                datasets: [{
                    label: 'Orders',
                    data: Object.values(orderData),
                    backgroundColor: ['#f59e0b', '#10b981', '#ef4444', '#3b82f6']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>
