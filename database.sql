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
    supplier_id INT DEFAULT NULL,
    supplier_name VARCHAR(200) DEFAULT NULL,
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
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL,
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
INSERT INTO products (name, slug, sku, category_id, cost_price, selling_price, stock, min_stock, unit, description, image, status, featured) VALUES
    ('ນ້ຳດື່ມສະອາດ 600ml', 'clean-water-600ml', 'SMP-001', 2, 2000, 4000, 200, 20, 'ຂວດ', 'ນ້ຳດື່ມສະອາດ ຂະໜາດ 600ml ເໝາະສຳລັບດື່ມປະຈຳວັນ', 'https://picsum.photos/id/1/400/400', 'Active', 1),
    ('ເຂົ້າສານ 5ກກ', 'rice-5kg', 'SMP-002', 3, 25000, 35000, 80, 10, 'ຖົງ', 'ເຂົ້າສານຄຸນນະພາບດີ ນຳເຂົ້າຈາກໄທ ຂະໜາດ 5 ກິໂລກຣາມ', 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400&h=400&fit=crop', 'Active', 1),
    ('ນ້ຳມັນພືດ 1ລ', 'cooking-oil-1l', 'SMP-003', 3, 8000, 12000, 60, 10, 'ຂວດ', 'ນ້ຳມັນພືດບໍລິສຸດ ຂະໜາດ 1 ລິດ', 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=400&h=400&fit=crop', 'Active', 1),
    ('ນ້ຳປາ 500ml', 'fish-sauce-500ml', 'SMP-004', 3, 5000, 8000, 90, 10, 'ຂວດ', 'ນ້ຳປາແທ້ ຂະໜາດ 500ml ລົດຊາດແຊບ', 'https://picsum.photos/id/20/400/400', 'Active', 1),
    ('ໝີ່ສຳຣະເຄັດ ຊອງໃຫຍ່', 'instant-noodles', 'SMP-005', 3, 3000, 5000, 300, 30, 'ຊອງ', 'ໝີ່ສຳຣະເຄັດ ຊອງໃຫຍ່ ລົດຊາດຕົ້ນຕຳຫຼັບ', 'https://picsum.photos/id/30/400/400', 'Active', 1),
    ('ກາເຟສຳເລັດຮູບ', 'instant-coffee', 'SMP-006', 2, 15000, 25000, 40, 5, 'ກະປຸກ', 'ກາເຟສຳເລັດຮູບ ຄຸນນະພາບສູງ ນຳເຂົ້າຈາກຫວຽດນາມ', 'https://picsum.photos/id/40/400/400', 'Active', 0),
    ('ຊາຂຽວ ຊອງໃຫຍ່', 'green-tea', 'SMP-007', 2, 10000, 18000, 50, 5, 'ຊອງ', 'ຊາຂຽວຄຸນນະພາບດີ ນຳເຂົ້າຈາກຈີນ ຊອງໃຫຍ່ 100 ຊອງນ້ອຍ', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&h=400&fit=crop', 'Active', 0),
    ('ນ້ຳອັດລົມ 1.25ລ', 'soda-125l', 'SMP-008', 2, 5000, 8000, 150, 15, 'ຂວດ', 'ນ້ຳອັດລົມ ຂະໜາດ 1.25 ລິດ', 'https://picsum.photos/id/60/400/400', 'Active', 0),
    ('ຜ້ງຊັກຟອກ 2ກກ', 'laundry-detergent-2kg', 'SMP-009', 1, 18000, 28000, 35, 5, 'ຖົງ', 'ຜ້ງຊັກຟອກ ສູດອ່ອນໂຍນ ຂະໜາດ 2 ກິໂລກຣາມ', 'https://images.unsplash.com/photo-1610557892470-55d9e80c0bce?w=400&h=400&fit=crop', 'Active', 0),
    ('ສະບູອາບນ້ຳ', 'body-soap', 'SMP-010', 1, 5000, 10000, 100, 10, 'ກ້ອນ', 'ສະບູອາບນ້ຳ ກິ່ນຫອມ ຜິວລຽນນຸ້ມ', 'https://picsum.photos/id/80/400/400', 'Active', 0)
ON DUPLICATE KEY UPDATE name = name;

-- Insert sample customers (password: 123456)
INSERT INTO customers (fullname, phone, email, password, address, province, district, village, status, created_at) VALUES
    ('ສຸລິຍາ ວົງສະຫວັດ', '020 55667788', 'suriya@example.com', '$2y$10$jqUyvZnfaFvtc/F/cgTWG.ziCX4F/yjYmIy61xGd1fT9xpHCjEbei', 'ບ້ານໂພນສີໄຄ', 'ນະຄອນຫຼວງວຽງຈັນ', 'ຈັນທະບູລີ', 'ໂພນສີໄຄ', 'Active', '2026-06-25 10:30:00'),
    ('ອະນຸສາ ແກ້ວມະນີ', '020 99887766', 'anousa@example.com', '$2y$10$jqUyvZnfaFvtc/F/cgTWG.ziCX4F/yjYmIy61xGd1fT9xpHCjEbei', 'ບ້ານສະພັນທະ', 'ນະຄອນຫຼວງວຽງຈັນ', 'ໄຊເສດຖາ', 'ສະພັນທະ', 'Active', '2026-06-20 14:00:00'),
    ('ບຸນທອນ ສີສະຫວາດ', '020 77441122', 'bounthong@example.com', '$2y$10$jqUyvZnfaFvtc/F/cgTWG.ziCX4F/yjYmIy61xGd1fT9xpHCjEbei', 'ບ້ານດົງໂດກ', 'ນະຄອນຫຼວງວຽງຈັນ', 'ສີໂຄດຕະບອງ', 'ດົງໂດກ', 'Active', '2026-06-15 09:00:00');

-- Insert sample customer addresses
INSERT INTO customer_addresses (customer_id, label, recipient_name, phone, address, province, district, village, is_default) VALUES
    (1, 'ບ້ານ', 'ສຸລິຍາ ວົງສະຫວັດ', '020 55667788', 'ບ້ານໂພນສີໄຄ ເສັ້ນ 13 ໃຕ້', 'ນະຄອນຫຼວງວຽງຈັນ', 'ຈັນທະບູລີ', 'ໂພນສີໄຄ', 1),
    (1, 'ຫ້ອງການ', 'ສຸລິຍາ ວົງສະຫວັດ', '020 55667788', 'ຕຶກລາວໄອທີ ຊັ້ນ 3 ຫ້ອງ 302', 'ນະຄອນຫຼວງວຽງຈັນ', 'ໄຊເສດຖາ', 'ສະພັນທະ', 0),
    (2, 'ບ້ານ', 'ອະນຸສາ ແກ້ວມະນີ', '020 99887766', 'ບ້ານສະພັນທະ ໃກ້ໂຮງຮຽນ', 'ນະຄອນຫຼວງວຽງຈັນ', 'ໄຊເສດຖາ', 'ສະພັນທະ', 1);

-- Insert sample suppliers
INSERT INTO suppliers (name, contact_person, phone, email, address, notes, status, created_at) VALUES
    ('ບໍລິສັດ ນ້ຳດື່ມລາວ ຈຳກັດ', 'ທ້າວ ສົມຊາຍ', '020 12345678', 'info@laowater.la', 'ບ້ານທ່າແຂກ ເມືອງໄຊເສດຖາ ນະຄອນຫຼວງວຽງຈັນ', 'ສົ່ງທຸກວັນຈັນ ແລະ ວັນພະຫັດ', 'Active', '2026-01-15 08:00:00'),
    ('ຫ້າງສົ່ງ ສິນຄ້າໄທ-ລາວ', 'ນາງ ມາລິກາ', '020 23456789', 'order@thailaoshop.la', 'ບ້ານໂພນສີໄຄ ເສັ້ນເລກ 13', 'ສິນຄ້າອຸປະໂພກ ແລະ ບໍລິໂພກ', 'Active', '2026-02-01 09:00:00'),
    ('ສູນກາງຄ້າ ວຽງຈັນ ດິສທຣິບິວເຊັ້ນ', 'ທ້າວ ບຸນມີ', '020 34567890', 'vte.dist@example.la', 'ບ້ານດົງໂດກ ເມືອງສີໂຄດຕະບອງ', 'ສົ່ງຟຣີເມື່ອສັ່ງ 500,000 ກີບຂຶ້ນໄປ', 'Active', '2026-02-15 10:00:00');

-- Insert sample product images (e-commerce gallery)
INSERT INTO product_images (product_id, image, sort_order) VALUES
    (1, 'https://picsum.photos/id/10/600/600', 1),
    (2, 'https://picsum.photos/id/11/600/600', 1),
    (2, 'https://picsum.photos/id/12/600/600', 2),
    (3, 'https://picsum.photos/id/13/600/600', 1),
    (3, 'https://picsum.photos/id/14/600/600', 2),
    (5, 'https://picsum.photos/id/15/600/600', 1),
    (6, 'https://picsum.photos/id/16/600/600', 1),
    (8, 'https://picsum.photos/id/17/600/600', 1);

-- Insert sample sales (for demo on /admin/sales)
INSERT INTO sales (invoice_number, customer_id, customer_name, customer_phone, customer_address, subtotal, discount, discount_type, tax_percent, tax_amount, grand_total, payment_method, amount_paid, change_amount, notes, status, created_by, created_at) VALUES
    ('INV-20260701-0001', NULL, 'ລູກຄ້າທົ່ວໄປ', '', '', 61000, 0, 'fixed', 0, 0, 61000, 'cash', 65000, 4000, '', 'Completed', 1, '2026-07-01 09:15:00'),
    ('INV-20260630-0001', 1, 'ສຸລິຍາ ວົງສະຫວັດ', '020 55667788', 'ບ້ານໂພນສີໄຄ', 129000, 5000, 'fixed', 0, 0, 124000, 'QR Code', 124000, 0, 'ສ່ວນຫຼຸດລູກຄ້າປະຈຳ', 'Completed', 1, '2026-06-30 14:30:00'),
    ('INV-20260628-0001', NULL, 'ລູກຄ້າທົ່ວໄປ', '020 11112222', '', 176000, 10000, 'fixed', 0, 0, 166000, 'cash', 170000, 4000, '', 'Completed', 2, '2026-06-28 10:00:00'),
    ('INV-20260625-0001', 2, 'ອະນຸສາ ແກ້ວມະນີ', '020 99887766', 'ບ້ານສະພັນທະ', 56000, 0, 'fixed', 0, 0, 56000, 'cash', 60000, 4000, '', 'Completed', 1, '2026-06-25 16:45:00'),
    ('INV-20260620-0001', 3, 'ບຸນທອນ ສີສະຫວາດ', '020 77441122', 'ບ້ານດົງໂດກ', 40000, 0, 'fixed', 0, 0, 40000, 'QR Code', 40000, 0, '', 'Completed', 2, '2026-06-20 11:20:00');

-- Insert sample sale_items (must match the 5 sales above)
INSERT INTO sale_items (sale_id, product_id, product_name, quantity, unit_price, subtotal) VALUES
    (1, 1, 'ນ້ຳດື່ມສະອາດ 600ml', 5, 4000, 20000),
    (1, 4, 'ນ້ຳປາ 500ml', 2, 8000, 16000),
    (1, 6, 'ກາເຟສຳເລັດຮູບ', 1, 25000, 25000);

INSERT INTO sale_items (sale_id, product_id, product_name, quantity, unit_price, subtotal) VALUES
    (2, 2, 'ເຂົ້າສານ 5ກກ', 3, 35000, 105000),
    (2, 3, 'ນ້ຳມັນພືດ 1ລ', 2, 12000, 24000);

INSERT INTO sale_items (sale_id, product_id, product_name, quantity, unit_price, subtotal) VALUES
    (3, 2, 'ເຂົ້າສານ 5ກກ', 3, 35000, 105000),
    (3, 1, 'ນ້ຳດື່ມສະອາດ 600ml', 12, 4000, 48000),
    (3, 5, 'ໝີ່ສຳຣະເຄັດ ຊອງໃຫຍ່', 5, 4600, 23000);

INSERT INTO sale_items (sale_id, product_id, product_name, quantity, unit_price, subtotal) VALUES
    (4, 9, 'ຜ້ງຊັກຟອກ 2ກກ', 2, 28000, 56000);

INSERT INTO sale_items (sale_id, product_id, product_name, quantity, unit_price, subtotal) VALUES
    (5, 5, 'ໝີ່ສຳຣະເຄັດ ຊອງໃຫຍ່', 8, 5000, 40000);

-- Insert sample e-commerce orders
INSERT INTO orders (order_number, customer_id, customer_name, customer_phone, customer_email, shipping_address, shipping_province, shipping_district, shipping_village, shipping_fee, subtotal, discount, grand_total, payment_method, payment_status, order_status, created_at) VALUES
    ('ORD-20260701-0001', 1, 'ສຸລິຍາ ວົງສະຫວັດ', '020 55667788', 'suriya@example.com', 'ບ້ານໂພນສີໄຄ ເສັ້ນ 13 ໃຕ້', 'ນະຄອນຫຼວງວຽງຈັນ', 'ຈັນທະບູລີ', 'ໂພນສີໄຄ', 15000, 125000, 0, 140000, 'cod', 'Pending', 'Pending', '2026-07-01 08:00:00'),
    ('ORD-20260628-0002', 2, 'ອະນຸສາ ແກ້ວມະນີ', '020 99887766', 'anousa@example.com', 'ບ້ານສະພັນທະ ໃກ້ໂຮງຮຽນ', 'ນະຄອນຫຼວງວຽງຈັນ', 'ໄຊເສດຖາ', 'ສະພັນທະ', 15000, 230000, 10000, 235000, 'cod', 'Paid', 'Delivered', '2026-06-28 15:00:00');

-- Insert sample order items
INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, subtotal) VALUES
    (1, 3, 'ນ້ຳມັນພືດ 1ລ', 5, 12000, 60000),
    (1, 5, 'ໝີ່ສຳຣະເຄັດ ຊອງໃຫຍ່', 10, 5000, 50000),
    (1, 8, 'ນ້ຳອັດລົມ 1.25ລ', 3, 5000, 15000);

INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, subtotal) VALUES
    (2, 2, 'ເຂົ້າສານ 5ກກ', 4, 35000, 140000),
    (2, 6, 'ກາເຟສຳເລັດຮູບ', 2, 25000, 50000),
    (2, 7, 'ຊາຂຽວ ຊອງໃຫຍ່', 2, 18000, 36000),
    (2, 10, 'ສະບູອາບນ້ຳ', 4, 10000, 40000);

-- Insert sample expenses
INSERT INTO expenses (expense_date, category_id, amount, description, created_by, created_at) VALUES
    ('2026-07-01', 1, 1500000, 'ຄ່າເຊົ່າຮ້ານ ເດືອນກໍລະກົດ', 1, '2026-07-01 08:00:00'),
    ('2026-07-01', 2, 350000, 'ຄ່າໄຟຟ້າ ເດືອນມິຖຸນາ', 1, '2026-07-01 08:30:00'),
    ('2026-06-30', 3, 2000000, 'ເງິນເດືອນພະນັກງານ 2 ຄົນ', 1, '2026-06-30 17:00:00'),
    ('2026-06-28', 2, 180000, 'ຄ່ານ້ຳປະປາ ເດືອນມິຖຸນາ', 1, '2026-06-28 09:00:00'),
    ('2026-06-25', 4, 120000, 'ຄ່າຂົນສົ່ງ ນຳເຂົ້າສິນຄ້າຮອບໃໝ່', 2, '2026-06-25 10:00:00'),
    ('2026-06-20', 5, 85000, 'ຄ່າຊື້ອຸປະກອນຫ້ອງນ້ຳ', 1, '2026-06-20 14:00:00'),
    ('2026-06-15', 5, 45000, 'ຄ່າກາເຟ ແລະ ນ້ຳດື່ມພະນັກງານ', 2, '2026-06-15 09:30:00');

-- Insert sample e-commerce banners
INSERT INTO banners (title, subtitle, image, link, sort_order, status) VALUES
    ('ສິນຄ້າໃໝ່ມາຮອດແລ້ວ!', 'ສິນຄ້າຄຸນນະພາບສູງ ລາຄາຖືກ ສົ່ງທົ່ວປະເທດ', 'https://picsum.photos/id/10/1200/400', '/products', 1, 'Active'),
    ('ໂປຣໂມຊັ້ນພິເສດ', 'ຊື້ 5 ຂຶ້ນໄປ ຮັບສ່ວນຫຼຸດ 10%', 'https://picsum.photos/id/11/1200/400', '/products?category=drinks', 2, 'Active');

-- ==========================================================================
-- Migration for existing databases: add supplier columns to sales table
-- ==========================================================================
-- ALTER TABLE sales
--   ADD COLUMN supplier_id INT DEFAULT NULL AFTER customer_address,
--   ADD COLUMN supplier_name VARCHAR(200) DEFAULT NULL AFTER supplier_id,
--   ADD FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL;
