-- Pizz_a64 Database Setup
-- Run this file once to set up the database

CREATE DATABASE IF NOT EXISTS pizz_a64 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pizz_a64;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(500),
    category VARCHAR(100),
    is_veg BOOLEAN DEFAULT 1,
    is_featured BOOLEAN DEFAULT 0,
    price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Product sizes table
CREATE TABLE IF NOT EXISTS product_sizes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    size_name VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    session_id VARCHAR(255),
    product_id INT NOT NULL,
    size_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (size_id) REFERENCES product_sizes(id) ON DELETE CASCADE
);

-- Wishlist table
CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    session_id VARCHAR(255),
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    session_id VARCHAR(255),
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    delivery_address TEXT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','confirmed','preparing','out_for_delivery','delivered','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    size_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (size_id) REFERENCES product_sizes(id)
);

-- ── Sample Products ──────────────────────────────────────────────────────
INSERT INTO products (name, description, image, category, is_veg, is_featured, price) VALUES
('Margherita Classic', 'Fresh mozzarella, tomato sauce, and basil on our signature hand-tossed crust', 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=600&h=400&fit=crop', 'Classic', 1, 1, 299.00),
('Pepperoni Supreme', 'Loaded with premium pepperoni, mozzarella cheese, and our special pizza sauce', 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=600&h=400&fit=crop', 'Classic', 0, 1, 399.00),
('BBQ Chicken Deluxe', 'Tender chicken pieces with BBQ sauce, onions, and bell peppers', 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&h=400&fit=crop', 'Specialty', 0, 1, 449.00),
('Veggie Paradise', 'Garden-fresh vegetables including bell peppers, onions, tomatoes, mushrooms, and olives', 'https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?w=600&h=400&fit=crop', 'Vegetarian', 1, 1, 349.00),
('Four Cheese Fusion', 'A blend of mozzarella, cheddar, parmesan, and feta cheese', 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=600&h=400&fit=crop', 'Specialty', 1, 0, 429.00),
('Mushroom Truffle', 'Fresh mushrooms with truffle oil, garlic, and parmesan', 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=600&h=400&fit=crop', 'Gourmet', 1, 1, 499.00),
('Spicy Paneer Tikka', 'Indian-style paneer tikka with onions, peppers, and spicy sauce', 'https://images.unsplash.com/photo-1593560708920-61dd98c46a4e?w=600&h=400&fit=crop', 'Fusion', 1, 0, 379.00),
('Meat Lovers Paradise', 'Pepperoni, sausage, ham, and bacon for the ultimate meat lover', 'https://images.unsplash.com/photo-1565299507177-b0ac66763828?w=600&h=400&fit=crop', 'Specialty', 0, 1, 549.00),
('Hawaiian Tropical', 'Ham and pineapple with mozzarella cheese', 'https://images.unsplash.com/photo-1571407970349-bc81e7e96a47?w=600&h=400&fit=crop', 'Classic', 0, 0, 399.00),
('Spinach & Feta', 'Fresh spinach, feta cheese, garlic, and cherry tomatoes', 'https://images.unsplash.com/photo-1595854341625-f33ee10dbf94?w=600&h=400&fit=crop', 'Gourmet', 1, 0, 429.00),
('Tandoori Chicken', 'Tandoori spiced chicken with onions and green peppers', 'https://images.unsplash.com/photo-1601924582970-9238bcb495d9?w=600&h=400&fit=crop', 'Fusion', 0, 0, 449.00),
('Buffalo Chicken', 'Spicy buffalo chicken with ranch drizzle and celery', 'https://images.unsplash.com/photo-1534308983496-4fabb1a015ee?w=600&h=400&fit=crop', 'Specialty', 0, 0, 469.00);

-- ── Sizes for all products ────────────────────────────────────────────────
INSERT INTO product_sizes (product_id, size_name, price)
SELECT id, 'Small (8")', price FROM products;

INSERT INTO product_sizes (product_id, size_name, price)
SELECT id, 'Medium (12")', ROUND(price * 1.5) FROM products;

INSERT INTO product_sizes (product_id, size_name, price)
SELECT id, 'Large (16")', ROUND(price * 2) FROM products;

INSERT INTO product_sizes (product_id, size_name, price)
SELECT id, 'Extra Large (20")', ROUND(price * 2.5) FROM products;