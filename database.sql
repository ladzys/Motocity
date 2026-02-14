-- MotoCity Database Schema
-- Create database and tables for the motorbike rental system

CREATE DATABASE IF NOT EXISTS motocity;
USE motocity;

-- Users table with role-based access
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'User') DEFAULT 'User',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Motorbikes table
CREATE TABLE IF NOT EXISTS motorbikes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    price_per_day DECIMAL(10, 2) NOT NULL,
    availability ENUM('Available', 'Rented') DEFAULT 'Available',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Rentals table
CREATE TABLE IF NOT EXISTS rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    motorbike_id INT NOT NULL,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME,
    total_cost DECIMAL(10, 2),
    status ENUM('Active', 'Completed') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (motorbike_id) REFERENCES motorbikes(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@motocity.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin');

-- Insert sample motorbikes
INSERT INTO motorbikes (brand, model, year, price_per_day, description) VALUES
('Harley-Davidson', 'Street 750', 2023, 85.00, 'Powerful cruiser with classic styling'),
('Honda', 'CBR600RR', 2024, 95.00, 'Sport bike with exceptional performance'),
('Yamaha', 'MT-07', 2023, 75.00, 'Versatile naked bike for city and highway'),
('Ducati', 'Monster 821', 2024, 120.00, 'Italian sport bike with aggressive design'),
('Kawasaki', 'Ninja 400', 2023, 70.00, 'Lightweight sport bike perfect for beginners'),
('BMW', 'R1250GS', 2024, 140.00, 'Adventure touring bike with advanced technology'),
('Suzuki', 'GSX-R750', 2023, 90.00, 'Classic sport bike with race heritage'),
('Triumph', 'Bonneville T120', 2024, 100.00, 'British retro bike with modern performance');
