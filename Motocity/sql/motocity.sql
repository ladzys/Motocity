CREATE DATABASE IF NOT EXISTS motocity CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE motocity;

-- USERS
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50) NOT NULL,
  last_name  VARCHAR(50) NOT NULL,
  phone      VARCHAR(20) NOT NULL,
  email      VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  user_type ENUM('ADMIN','USER') NOT NULL DEFAULT 'USER',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- MOTORBIKES
CREATE TABLE motorbikes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(30) NOT NULL UNIQUE,
  renting_location VARCHAR(80) NOT NULL,
  description VARCHAR(255) NOT NULL,
  cost_per_hour DECIMAL(10,2) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1
);

-- RENTALS (the key business logic)
CREATE TABLE rentals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  motorbike_id INT NOT NULL,
  start_time DATETIME NOT NULL,
  end_time DATETIME NULL,
  cost_per_hour DECIMAL(10,2) NOT NULL,
  total_cost DECIMAL(10,2) NULL,
  status ENUM('ONGOING','COMPLETED') NOT NULL DEFAULT 'ONGOING',
  ongoing_key TINYINT GENERATED ALWAYS AS (
    CASE WHEN status='ONGOING' THEN 1 ELSE NULL END
  ) STORED,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_rentals_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_rentals_bike FOREIGN KEY (motorbike_id) REFERENCES motorbikes(id) ON DELETE CASCADE
);

-- Allow many completed rentals while preventing more than one ongoing rental per bike
CREATE UNIQUE INDEX uniq_bike_ongoing ON rentals (motorbike_id, ongoing_key);

-- Helpful indexes for search
CREATE INDEX idx_bike_search ON motorbikes (code, renting_location, description);
CREATE INDEX idx_rentals_user_status ON rentals (user_id, status);

-- Create a default admin (password: Admin123!)
INSERT INTO users (first_name,last_name,phone,email,password_hash,user_type)
VALUES ('Admin','MotoCity','00000000','admin@motocity.com',
        '$2y$10$wHq8xjJ1xvR7Xg6mM0m8Qe7rV9Y4m0u4oQdE/2h8b1xQeO7v0vF1K', 'ADMIN');