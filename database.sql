-- POS Stock Billing System + E-Commerce Database Schema
-- Database: pos_stock_db
-- Supports two systems: /admin (POS) and / (E-commerce)

CREATE DATABASE IF NOT EXISTS pos_stock_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pos_stock_db;

-- Drop existing tables to apply fresh schema
DROP TABLE IF EXISTS banners, cart_items, order_items, orders, sale_items, sales, product_images, products, categories, customer_addresses, customers, suppliers, expenses, expense_categories, payment_methods, settings, users;

-- Users / Staff (Admin POS only)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    avatar VARCHAR(500) DEFAULT NULL,
    role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
    status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Categories (shared)
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) DEFAULT NULL,
    description TEXT,
    image VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products (shared between POS and E-commerce)
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) DEFAULT NULL,
    sku VARCHAR(50) UNIQUE,
    barcode VARCHAR(100) DEFAULT NULL,
    category_id INT DEFAULT NULL,
    cost_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    selling_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    compare_price DECIMAL(12,2) DEFAULT NULL,
    stock INT NOT NULL DEFAULT 0,
    min_stock INT NOT NULL DEFAULT 5,
    unit VARCHAR(50) DEFAULT 'ຊິ້ນ',
    weight DECIMAL(10,2) DEFAULT NULL,
    description TEXT,
    short_description VARCHAR(500) DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_featured (featured),
    INDEX idx_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Product Images Gallery (E-commerce)
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Customers (shared)
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    password VARCHAR(255) DEFAULT NULL,
    address TEXT,
    province VARCHAR(100) DEFAULT NULL,
    district VARCHAR(100) DEFAULT NULL,
    village VARCHAR(100) DEFAULT NULL,
    notes TEXT,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_phone (phone),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Customer Addresses (E-commerce)
CREATE TABLE customer_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    label VARCHAR(50) DEFAULT 'ບ້ານ',
    recipient_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    province VARCHAR(100) DEFAULT NULL,
    district VARCHAR(100) DEFAULT NULL,
    village VARCHAR(100) DEFAULT NULL,
    is_default TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Suppliers (Admin POS)
CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    contact_person VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    notes TEXT,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sales / Invoices (Admin POS)
CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    customer_id INT DEFAULT NULL,
    customer_name VARCHAR(200) DEFAULT NULL,
    customer_phone VARCHAR(20) DEFAULT NULL,
    customer_address TEXT,
    subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
    discount DECIMAL(12,2) NOT NULL DEFAULT 0,
    discount_type ENUM('percent', 'fixed') DEFAULT 'fixed',
    tax_percent DECIMAL(5,2) DEFAULT 0,
    tax_amount DECIMAL(12,2) DEFAULT 0,
    grand_total DECIMAL(12,2) NOT NULL DEFAULT 0,
    payment_method VARCHAR(50) DEFAULT 'cash',
    amount_paid DECIMAL(12,2) DEFAULT 0,
    change_amount DECIMAL(12,2) DEFAULT 0,
    notes TEXT,
    status ENUM('Completed', 'Refunded', 'Cancelled') NOT NULL DEFAULT 'Completed',
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_invoice (invoice_number),
    INDEX idx_date (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sale Items (Admin POS)
CREATE TABLE sale_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    product_id INT DEFAULT NULL,
    product_name VARCHAR(200) DEFAULT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(12,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- E-commerce Orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    customer_id INT DEFAULT NULL,
    customer_name VARCHAR(200) NOT NULL,
    customer_phone VARCHAR(20) DEFAULT NULL,
    customer_email VARCHAR(100) DEFAULT NULL,
    shipping_address TEXT,
    shipping_province VARCHAR(100) DEFAULT NULL,
    shipping_district VARCHAR(100) DEFAULT NULL,
    shipping_village VARCHAR(100) DEFAULT NULL,
    shipping_fee DECIMAL(12,2) DEFAULT 0,
    subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
    discount DECIMAL(12,2) DEFAULT 0,
    grand_total DECIMAL(12,2) NOT NULL DEFAULT 0,
    payment_method VARCHAR(50) DEFAULT 'cod',
    payment_status ENUM('Pending', 'Paid', 'Failed', 'Refunded') DEFAULT 'Pending',
    order_status ENUM('Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    INDEX idx_order_number (order_number),
    INDEX idx_order_status (order_status),
    INDEX idx_order_date (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order Items (E-commerce)
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT DEFAULT NULL,
    product_name VARCHAR(200) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(12,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Shopping Cart (E-commerce)
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) DEFAULT NULL,
    customer_id INT DEFAULT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    INDEX idx_session (session_id),
    INDEX idx_customer (customer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Expenses (Admin POS)
CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expense_date DATE NOT NULL,
    category_id INT DEFAULT NULL,
    amount DECIMAL(15,2) NOT NULL,
    description TEXT,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Expense Categories
CREATE TABLE expense_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Payment Methods (Admin POS)
CREATE TABLE payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    details TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Settings (key-value, shared)
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- E-commerce Banners
CREATE TABLE banners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) DEFAULT NULL,
    subtitle VARCHAR(500) DEFAULT NULL,
    image VARCHAR(255) NOT NULL,
    link VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default settings
INSERT INTO settings (setting_key, setting_value) VALUES
    ('store_name', 'POS & Stock'),
    ('store_address', ''),
    ('store_phone', ''),
    ('store_email', ''),
    ('currency', 'ກີບ'),
    ('currency_symbol', '₭'),
    ('tax_percent', '0'),
    ('paper_size', '58mm'),
    ('receipt_footer', ''),
    ('store_logo', ''),
    ('invoice_terms', '')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- Insert default users (password: 123456)
INSERT INTO users (username, password, full_name, role, status) VALUES
    ('admin', '$2y$10$jqUyvZnfaFvtc/F/cgTWG.ziCX4F/yjYmIy61xGd1fT9xpHCjEbei', 'Admin User', 'admin', 'Active'),
    ('staff', '$2y$10$jqUyvZnfaFvtc/F/cgTWG.ziCX4F/yjYmIy61xGd1fT9xpHCjEbei', 'Staff User', 'staff', 'Active')
ON DUPLICATE KEY UPDATE username = username;

-- Insert sample categories
INSERT INTO categories (name, slug, description) VALUES
    ('ທົ່ວໄປ', 'general', 'ສິນຄ້າທົ່ວໄປ'),
    ('ເຄື່ອງດື່ມ', 'drinks', 'ເຄື່ອງດື່ມຕ່າງໆ'),
    ('ອາຫານ', 'food', 'ອາຫານ ແລະ ຂອງກິນ')
ON DUPLICATE KEY UPDATE name = name;

-- Insert sample payment methods
INSERT INTO payment_methods (name, details) VALUES
    ('ເງິນສົດ', 'Cash'),
    ('QR Code', 'QR Code Payment')
ON DUPLICATE KEY UPDATE name = name;

-- Insert sample expense categories
INSERT INTO expense_categories (name, description) VALUES
    ('ຄ່າເຊົ່າສະຖານທີ່', 'Rent'),
    ('ຄ່ານ້ຳ-ຄ່າໄຟ', 'Utilities'),
    ('ຄ່າພະນັກງານ', 'Salary'),
    ('ຄ່າຂົນສົ່ງ', 'Shipping'),
    ('ອື່ນໆ', 'Other')
ON DUPLICATE KEY UPDATE name = name;

-- Insert sample products
INSERT INTO products (name, slug, sku, category_id, cost_price, selling_price, stock, min_stock, unit, description, status, featured) VALUES
    ('ສິນຄ້າຕົວຢ່າງ 1', 'sample-product-1', 'SMP-001', 1, 10000, 15000, 50, 5, 'ຊິ້ນ', 'ສິນຄ້າຕົວຢ່າງລາຍການທີ 1', 'Active', 1),
    ('ສິນຄ້າຕົວຢ່າງ 2', 'sample-product-2', 'SMP-002', 1, 20000, 30000, 30, 5, 'ຊິ້ນ', 'ສິນຄ້າຕົວຢ່າງລາຍການທີ 2', 'Active', 1),
    ('ສິນຄ້າຕົວຢ່າງ 3', 'sample-product-3', 'SMP-003', 2, 5000, 8000, 100, 10, 'ກະປຸກ', 'ສິນຄ້າຕົວຢ່າງລາຍການທີ 3', 'Active', 0)
ON DUPLICATE KEY UPDATE name = name;
