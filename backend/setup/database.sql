-- Create database
CREATE DATABASE IF NOT EXISTS portfolio_db;
USE portfolio_db;

-- Projects table
CREATE TABLE IF NOT EXISTS projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(500),
    link VARCHAR(500),
    published BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Messages table
CREATE TABLE IF NOT EXISTS messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    read_status BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample projects
INSERT INTO projects (title, description, image, link, published) VALUES
('E-Commerce Platform', 'A full-stack e-commerce platform with payment integration and admin dashboard.', 'https://via.placeholder.com/300x200?text=E-Commerce', '#', 1),
('Task Management App', 'Collaborative task management application with real-time updates.', 'https://via.placeholder.com/300x200?text=Task+Manager', '#', 1),
('Weather Dashboard', 'Real-time weather dashboard using external API with beautiful UI.', 'https://via.placeholder.com/300x200?text=Weather', '#', 1);