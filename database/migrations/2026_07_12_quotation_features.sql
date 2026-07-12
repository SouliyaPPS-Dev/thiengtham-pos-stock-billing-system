-- Migration: Add Customer, Expiry, and History to Quotations
-- Date: 2026-07-12
--
-- This migration is self-contained and idempotent:
--   * It creates the `quotations` table if it does not exist (with the new columns).
--   * If the table already exists with the old schema, it adds the missing columns.
--   * It creates the `quotation_history` table if it does not exist.
--
-- This fixes the "#1146 - Table '...quotations' doesn't exist" error that occurred
-- when the migration was imported into a database where the table had not been created.

-- 0. Helper procedure: add a column only if it does not already exist
DROP PROCEDURE IF EXISTS add_quotation_column;
DELIMITER $$
CREATE PROCEDURE add_quotation_column(IN col_name VARCHAR(64), IN col_def VARCHAR(512))
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'quotations'
          AND COLUMN_NAME = col_name
    ) THEN
        SET @sql = CONCAT('ALTER TABLE quotations ADD COLUMN `', col_name, '` ', col_def);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$
DELIMITER ;

-- 0b. Helper procedure: add an index only if it does not already exist
DROP PROCEDURE IF EXISTS add_quotation_index;
DELIMITER $$
CREATE PROCEDURE add_quotation_index(IN idx_name VARCHAR(64), IN idx_def VARCHAR(255))
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'quotations'
          AND INDEX_NAME = idx_name
    ) THEN
        SET @sql = CONCAT('ALTER TABLE quotations ADD INDEX `', idx_name, '` ', idx_def);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$
DELIMITER ;

-- 0c. Helper procedure: add a foreign key only if it does not already exist
--     AND only if the referenced (parent) table exists. This keeps the migration
--     importable on databases that do not yet contain the parent tables
--     (e.g. customers, suppliers, users). Works for any table.
DROP PROCEDURE IF EXISTS add_fk;
DELIMITER $$
CREATE PROCEDURE add_fk(IN tbl_name VARCHAR(64), IN fk_name VARCHAR(64), IN fk_def VARCHAR(512), IN parent_table VARCHAR(64))
BEGIN
    IF parent_table IS NULL OR EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = parent_table
    ) THEN
        IF NOT EXISTS (
            SELECT 1 FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = tbl_name
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
              AND CONSTRAINT_NAME = fk_name
        ) THEN
            SET @sql = CONCAT('ALTER TABLE `', tbl_name, '` ADD CONSTRAINT `', fk_name, '` ', fk_def);
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        END IF;
    END IF;
END$$
DELIMITER ;

-- 1. Create the quotations table (full schema including the new columns) if missing
CREATE TABLE IF NOT EXISTS quotations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quotation_number VARCHAR(50) NOT NULL UNIQUE,
    company_template VARCHAR(50) NOT NULL DEFAULT 'luang-prabarg',
    supplier_id INT DEFAULT NULL,
    supplier_name VARCHAR(200) DEFAULT NULL,
    supplier_contact VARCHAR(200) DEFAULT NULL,
    customer_id INT DEFAULT NULL,
    customer_name VARCHAR(200) DEFAULT NULL,
    customer_contact VARCHAR(200) DEFAULT NULL,
    ref_no VARCHAR(100) DEFAULT NULL,
    date DATE DEFAULT NULL,
    expiry_date DATE DEFAULT NULL,
    subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
    discount DECIMAL(12,2) NOT NULL DEFAULT 0,
    tax_percent DECIMAL(5,2) DEFAULT 10.00,
    tax_amount DECIMAL(12,2) DEFAULT 0,
    grand_total DECIMAL(12,2) NOT NULL DEFAULT 0,
    converted_to_sale_id INT DEFAULT NULL,
    notes TEXT,
    terms TEXT,
    status ENUM('Draft', 'Sent', 'Approved', 'Rejected') NOT NULL DEFAULT 'Draft',
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_quotation_number (quotation_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. For databases where an old-schema quotations table already exists, add the new columns
CALL add_quotation_column('customer_id', "INT DEFAULT NULL AFTER supplier_contact");
CALL add_quotation_column('customer_name', "VARCHAR(200) DEFAULT NULL AFTER customer_id");
CALL add_quotation_column('customer_contact', "VARCHAR(200) DEFAULT NULL AFTER customer_name");
CALL add_quotation_column('expiry_date', "DATE DEFAULT NULL AFTER date");
CALL add_quotation_column('converted_to_sale_id', "INT DEFAULT NULL AFTER grand_total");

-- Add foreign keys (only when the referenced parent tables exist), and index
CALL add_fk('quotations', 'quotations_ibfk_supplier', "FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL", 'suppliers');
CALL add_fk('quotations', 'quotations_ibfk_customer', "FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL", 'customers');
CALL add_fk('quotations', 'quotations_ibfk_created_by', "FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL", 'users');

-- Add index on converted_to_sale_id (only if missing)
CALL add_quotation_index('idx_converted', "(converted_to_sale_id)");

-- 3. Create quotation_history table for status tracking
CREATE TABLE IF NOT EXISTS quotation_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quotation_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    old_status VARCHAR(50) DEFAULT NULL,
    new_status VARCHAR(50) DEFAULT NULL,
    notes TEXT,
    performed_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE CASCADE,
    INDEX idx_quotation_history (quotation_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add performed_by FK only if the users table exists
CALL add_fk('quotation_history', 'quotation_history_ibfk_performed_by', "FOREIGN KEY (performed_by) REFERENCES users(id) ON DELETE SET NULL", 'users');

-- Clean up helper procedures
DROP PROCEDURE IF EXISTS add_quotation_column;
DROP PROCEDURE IF EXISTS add_quotation_index;
DROP PROCEDURE IF EXISTS add_fk;
