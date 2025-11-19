<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection without DB
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the SQL file
$sql = file_get_contents('food-delivery-app/database/schema.sql');

// Execute multi query
if ($conn->multi_query($sql)) {
    echo "Database and tables created successfully";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
