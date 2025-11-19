-- Database schema for Food Delivery Admin Panel

CREATE DATABASE food_delivery_admin;

USE food_delivery_admin;

-- Admins table for login
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  -- Store hashed passwords
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Restaurants table
CREATE TABLE restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    restaurant_id INT,
    order_details TEXT,
    total_amount DECIMAL(10,2),
    status ENUM('pending', 'confirmed', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);

-- Insert sample admin user (password: admin123, hashed)
INSERT INTO admins (username, password, email) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com');

-- Insert sample restaurants
INSERT INTO restaurants (name, address, phone, email) VALUES
('Pizza Palace', '123 Main St', '123-456-7890', 'pizza@example.com'),
('Burger Joint', '456 Elm St', '987-654-3210', 'burger@example.com');

-- Insert sample orders
INSERT INTO orders (customer_name, restaurant_id, order_details, total_amount) VALUES
('John Doe', 1, 'Large Pizza', 15.99),
('Jane Smith', 2, 'Cheeseburger', 8.99);
