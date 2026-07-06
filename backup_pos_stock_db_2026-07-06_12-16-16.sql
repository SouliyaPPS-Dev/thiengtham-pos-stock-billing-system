-- Database Backup: 2026-07-06 12:16:16
-- System: POS Stock Billing System
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";



DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `subtitle` varchar(500) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `banners` VALUES("1","ສິນຄ້າໃໝ່ມາຮອດແລ້ວ!","ສິນຄ້າຄຸນນະພາບສູງ ລາຄາຖືກ ສົ່ງທົ່ວປະເທດ","https://picsum.photos/id/10/1200/400","/products","1","Active","2026-07-05 20:52:51");
INSERT INTO `banners` VALUES("2","ໂປຣໂມຊັ້ນພິເສດ","ຊື້ 5 ຂຶ້ນໄປ ຮັບສ່ວນຫຼຸດ 10%","https://picsum.photos/id/11/1200/400","/products?category=drinks","2","Active","2026-07-05 20:52:51");





DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `idx_session` (`session_id`),
  KEY `idx_customer` (`customer_id`),
  CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;






DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categories` VALUES("1","ທົ່ວໄປ","general","ສິນຄ້າທົ່ວໄປ",NULL,"0","Active","2026-07-05 20:52:51");
INSERT INTO `categories` VALUES("2","ເຄື່ອງດື່ມ","drinks","ເຄື່ອງດື່ມຕ່າງໆ",NULL,"0","Active","2026-07-05 20:52:51");
INSERT INTO `categories` VALUES("3","ອາຫານ","food","ອາຫານ ແລະ ຂອງກິນ",NULL,"0","Active","2026-07-05 20:52:51");





DROP TABLE IF EXISTS `customer_addresses`;
CREATE TABLE `customer_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `label` varchar(50) DEFAULT 'ບ້ານ',
  `recipient_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `province` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `village` varchar(100) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `customer_addresses_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customer_addresses` VALUES("1","1","ບ້ານ","ສຸລິຍາ ວົງສະຫວັດ","020 55667788","ບ້ານໂພນສີໄຄ ເສັ້ນ 13 ໃຕ້","ນະຄອນຫຼວງວຽງຈັນ","ຈັນທະບູລີ","ໂພນສີໄຄ","1","2026-07-05 20:52:51");
INSERT INTO `customer_addresses` VALUES("2","1","ຫ້ອງການ","ສຸລິຍາ ວົງສະຫວັດ","020 55667788","ຕຶກລາວໄອທີ ຊັ້ນ 3 ຫ້ອງ 302","ນະຄອນຫຼວງວຽງຈັນ","ໄຊເສດຖາ","ສະພັນທະ","0","2026-07-05 20:52:51");
INSERT INTO `customer_addresses` VALUES("3","2","ບ້ານ","ອະນຸສາ ແກ້ວມະນີ","020 99887766","ບ້ານສະພັນທະ ໃກ້ໂຮງຮຽນ","ນະຄອນຫຼວງວຽງຈັນ","ໄຊເສດຖາ","ສະພັນທະ","1","2026-07-05 20:52:51");





DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `village` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_phone` (`phone`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customers` VALUES("1","ສຸລິຍາ ວົງສະຫວັດ","020 55667788","suriya@example.com","$2y$10$jqUyvZnfaFvtc/F/cgTWG.ziCX4F/yjYmIy61xGd1fT9xpHCjEbei","ບ້ານໂພນສີໄຄ","ນະຄອນຫຼວງວຽງຈັນ","ຈັນທະບູລີ","ໂພນສີໄຄ",NULL,"Active","2026-06-25 17:30:00","2026-07-05 20:52:51");
INSERT INTO `customers` VALUES("2","ອະນຸສາ ແກ້ວມະນີ","020 99887766","anousa@example.com","$2y$10$jqUyvZnfaFvtc/F/cgTWG.ziCX4F/yjYmIy61xGd1fT9xpHCjEbei","ບ້ານສະພັນທະ","ນະຄອນຫຼວງວຽງຈັນ","ໄຊເສດຖາ","ສະພັນທະ",NULL,"Active","2026-06-20 21:00:00","2026-07-05 20:52:51");
INSERT INTO `customers` VALUES("3","ບຸນທອນ ສີສະຫວາດ","020 77441122","bounthong@example.com","$2y$10$jqUyvZnfaFvtc/F/cgTWG.ziCX4F/yjYmIy61xGd1fT9xpHCjEbei","ບ້ານດົງໂດກ","ນະຄອນຫຼວງວຽງຈັນ","ສີໂຄດຕະບອງ","ດົງໂດກ",NULL,"Active","2026-06-15 16:00:00","2026-07-05 20:52:51");
INSERT INTO `customers` VALUES("4","souliya pps","+856 2078287500","souliyapps@gmail.com","$2y$10$gP3vt//5YYgtTfnF1T..UOZZSeZlsNTrHQt.Uca/Xd7.oxbnurhhO","Xaythany District\r\nPhakhao","","","","","Active","2026-07-06 11:45:00","2026-07-06 11:45:00");





DROP TABLE IF EXISTS `expense_categories`;
CREATE TABLE `expense_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `expense_categories` VALUES("1","ຄ່າເຊົ່າສະຖານທີ່","Rent","2026-07-05 20:52:51");
INSERT INTO `expense_categories` VALUES("2","ຄ່ານ້ຳ-ຄ່າໄຟ","Utilities","2026-07-05 20:52:51");
INSERT INTO `expense_categories` VALUES("3","ຄ່າພະນັກງານ","Salary","2026-07-05 20:52:51");
INSERT INTO `expense_categories` VALUES("4","ຄ່າຂົນສົ່ງ","Shipping","2026-07-05 20:52:51");
INSERT INTO `expense_categories` VALUES("5","ອື່ນໆ","Other","2026-07-05 20:52:51");





DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expense_date` date NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `expenses` VALUES("1","2026-07-01","1","1500000.00","ຄ່າເຊົ່າຮ້ານ ເດືອນກໍລະກົດ","1","2026-07-01 15:00:00");
INSERT INTO `expenses` VALUES("2","2026-07-01","2","350000.00","ຄ່າໄຟຟ້າ ເດືອນມິຖຸນາ","1","2026-07-01 15:30:00");
INSERT INTO `expenses` VALUES("3","2026-06-30","3","2000000.00","ເງິນເດືອນພະນັກງານ 2 ຄົນ","1","2026-07-01 00:00:00");
INSERT INTO `expenses` VALUES("4","2026-06-28","2","180000.00","ຄ່ານ້ຳປະປາ ເດືອນມິຖຸນາ","1","2026-06-28 16:00:00");
INSERT INTO `expenses` VALUES("5","2026-06-25","4","120000.00","ຄ່າຂົນສົ່ງ ນຳເຂົ້າສິນຄ້າຮອບໃໝ່","2","2026-06-25 17:00:00");
INSERT INTO `expenses` VALUES("6","2026-06-20","5","85000.00","ຄ່າຊື້ອຸປະກອນຫ້ອງນ້ຳ","1","2026-06-20 21:00:00");
INSERT INTO `expenses` VALUES("7","2026-06-15","5","45000.00","ຄ່າກາເຟ ແລະ ນ້ຳດື່ມພະນັກງານ","2","2026-06-15 16:30:00");





DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `order_items` VALUES("1","1","3","ນ້ຳມັນພືດ 1ລ","5","12000.00","60000.00","2026-07-05 20:52:51");
INSERT INTO `order_items` VALUES("2","1","5","ໝີ່ສຳຣະເຄັດ ຊອງໃຫຍ່","10","5000.00","50000.00","2026-07-05 20:52:51");
INSERT INTO `order_items` VALUES("3","1","8","ນ້ຳອັດລົມ 1.25ລ","3","5000.00","15000.00","2026-07-05 20:52:51");
INSERT INTO `order_items` VALUES("4","2","2","ເຂົ້າສານ 5ກກ","4","35000.00","140000.00","2026-07-05 20:52:51");
INSERT INTO `order_items` VALUES("5","2","6","ກາເຟສຳເລັດຮູບ","2","25000.00","50000.00","2026-07-05 20:52:51");
INSERT INTO `order_items` VALUES("6","2","7","ຊາຂຽວ ຊອງໃຫຍ່","2","18000.00","36000.00","2026-07-05 20:52:51");
INSERT INTO `order_items` VALUES("7","2","10","ສະບູອາບນ້ຳ","4","10000.00","40000.00","2026-07-05 20:52:51");





DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(200) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `shipping_province` varchar(100) DEFAULT NULL,
  `shipping_district` varchar(100) DEFAULT NULL,
  `shipping_village` varchar(100) DEFAULT NULL,
  `shipping_fee` decimal(12,2) DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) DEFAULT 0.00,
  `grand_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT 'cod',
  `payment_status` enum('Pending','Paid','Failed','Refunded') DEFAULT 'Pending',
  `order_status` enum('Pending','Confirmed','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `customer_id` (`customer_id`),
  KEY `idx_order_number` (`order_number`),
  KEY `idx_order_status` (`order_status`),
  KEY `idx_order_date` (`created_at`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `orders` VALUES("1","ORD-20260701-0001","1","ສຸລິຍາ ວົງສະຫວັດ","020 55667788","suriya@example.com","ບ້ານໂພນສີໄຄ ເສັ້ນ 13 ໃຕ້","ນະຄອນຫຼວງວຽງຈັນ","ຈັນທະບູລີ","ໂພນສີໄຄ","15000.00","125000.00","0.00","140000.00","cod","Pending","Pending",NULL,"2026-07-01 15:00:00","2026-07-05 20:52:51");
INSERT INTO `orders` VALUES("2","ORD-20260628-0002","2","ອະນຸສາ ແກ້ວມະນີ","020 99887766","anousa@example.com","ບ້ານສະພັນທະ ໃກ້ໂຮງຮຽນ","ນະຄອນຫຼວງວຽງຈັນ","ໄຊເສດຖາ","ສະພັນທະ","15000.00","230000.00","10000.00","235000.00","cod","Paid","Delivered",NULL,"2026-06-28 22:00:00","2026-07-05 20:52:51");





DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payment_methods` VALUES("1","ເງິນສົດ","Cash","1","2026-07-05 20:52:51");
INSERT INTO `payment_methods` VALUES("2","QR Code","QR Code Payment","1","2026-07-05 20:52:51");





DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `product_images` VALUES("1","1","https://picsum.photos/id/10/600/600","1","2026-07-05 20:52:51");
INSERT INTO `product_images` VALUES("2","2","https://picsum.photos/id/11/600/600","1","2026-07-05 20:52:51");
INSERT INTO `product_images` VALUES("3","2","https://picsum.photos/id/12/600/600","2","2026-07-05 20:52:51");
INSERT INTO `product_images` VALUES("4","3","https://picsum.photos/id/13/600/600","1","2026-07-05 20:52:51");
INSERT INTO `product_images` VALUES("5","3","https://picsum.photos/id/14/600/600","2","2026-07-05 20:52:51");
INSERT INTO `product_images` VALUES("6","5","https://picsum.photos/id/15/600/600","1","2026-07-05 20:52:51");
INSERT INTO `product_images` VALUES("7","6","https://picsum.photos/id/16/600/600","1","2026-07-05 20:52:51");
INSERT INTO `product_images` VALUES("8","8","https://picsum.photos/id/17/600/600","1","2026-07-05 20:52:51");





DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `cost_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `compare_price` decimal(12,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `min_stock` int(11) NOT NULL DEFAULT 5,
  `unit` varchar(50) DEFAULT 'ຊິ້ນ',
  `weight` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `idx_status` (`status`),
  KEY `idx_featured` (`featured`),
  KEY `idx_category` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `products` VALUES("1","ນ້ຳດື່ມສະອາດ 600ml","clean-water-600ml","SMP-001",NULL,"2","2000.00","4000.00",NULL,"199","20","ຂວດ",NULL,"ນ້ຳດື່ມສະອາດ ຂະໜາດ 600ml ເໝາະສຳລັບດື່ມປະຈຳວັນ",NULL,"https://picsum.photos/id/1/400/400","Active","1","2026-07-05 20:52:51","2026-07-05 22:32:27");
INSERT INTO `products` VALUES("2","ເຂົ້າສານ 5ກກ","rice-5kg","SMP-002",NULL,"3","25000.00","35000.00",NULL,"80","10","ຖົງ",NULL,"ເຂົ້າສານຄຸນນະພາບດີ ນຳເຂົ້າຈາກໄທ ຂະໜາດ 5 ກິໂລກຣາມ",NULL,"https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400&h=400&fit=crop","Active","1","2026-07-05 20:52:51","2026-07-05 20:52:51");
INSERT INTO `products` VALUES("3","ນ້ຳມັນພືດ 1ລ","cooking-oil-1l","SMP-003",NULL,"3","8000.00","12000.00",NULL,"60","10","ຂວດ",NULL,"ນ້ຳມັນພືດບໍລິສຸດ ຂະໜາດ 1 ລິດ",NULL,"https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=400&h=400&fit=crop","Active","1","2026-07-05 20:52:51","2026-07-05 20:52:51");
INSERT INTO `products` VALUES("4","ນ້ຳປາ 500ml","fish-sauce-500ml","SMP-004",NULL,"3","5000.00","8000.00",NULL,"90","10","ຂວດ",NULL,"ນ້ຳປາແທ້ ຂະໜາດ 500ml ລົດຊາດແຊບ",NULL,"https://picsum.photos/id/20/400/400","Active","1","2026-07-05 20:52:51","2026-07-05 20:52:51");
INSERT INTO `products` VALUES("5","ໝີ່ສຳຣະເຄັດ ຊອງໃຫຍ່","instant-noodles","SMP-005",NULL,"3","3000.00","5000.00",NULL,"300","30","ຊອງ",NULL,"ໝີ່ສຳຣະເຄັດ ຊອງໃຫຍ່ ລົດຊາດຕົ້ນຕຳຫຼັບ",NULL,"https://picsum.photos/id/30/400/400","Active","1","2026-07-05 20:52:51","2026-07-05 20:52:51");
INSERT INTO `products` VALUES("6","ກາເຟສຳເລັດຮູບ","instant-coffee","SMP-006",NULL,"2","15000.00","25000.00",NULL,"40","5","ກະປຸກ",NULL,"ກາເຟສຳເລັດຮູບ ຄຸນນະພາບສູງ ນຳເຂົ້າຈາກຫວຽດນາມ",NULL,"https://picsum.photos/id/40/400/400","Active","0","2026-07-05 20:52:51","2026-07-05 20:52:51");
INSERT INTO `products` VALUES("7","ຊາຂຽວ ຊອງໃຫຍ່","green-tea","SMP-007",NULL,"2","10000.00","18000.00",NULL,"50","5","ຊອງ",NULL,"ຊາຂຽວຄຸນນະພາບດີ ນຳເຂົ້າຈາກຈີນ ຊອງໃຫຍ່ 100 ຊອງນ້ອຍ",NULL,"https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&h=400&fit=crop","Active","0","2026-07-05 20:52:51","2026-07-05 20:52:51");
INSERT INTO `products` VALUES("8","ນ້ຳອັດລົມ 1.25ລ","soda-125l","SMP-008",NULL,"2","5000.00","8000.00",NULL,"150","15","ຂວດ",NULL,"ນ້ຳອັດລົມ ຂະໜາດ 1.25 ລິດ",NULL,"https://picsum.photos/id/60/400/400","Active","0","2026-07-05 20:52:51","2026-07-05 20:52:51");
INSERT INTO `products` VALUES("9","ຜ້ງຊັກຟອກ 2ກກ","laundry-detergent-2kg","SMP-009",NULL,"1","18000.00","28000.00",NULL,"35","5","ຖົງ",NULL,"ຜ້ງຊັກຟອກ ສູດອ່ອນໂຍນ ຂະໜາດ 2 ກິໂລກຣາມ",NULL,"https://images.unsplash.com/photo-1610557892470-55d9e80c0bce?w=400&h=400&fit=crop","Active","0","2026-07-05 20:52:51","2026-07-05 20:52:51");
INSERT INTO `products` VALUES("10","ສະບູອາບນ້ຳ","body-soap","SMP-010",NULL,"1","5000.00","10000.00",NULL,"100","10","ກ້ອນ",NULL,"ສະບູອາບນ້ຳ ກິ່ນຫອມ ຜິວລຽນນຸ້ມ",NULL,"https://picsum.photos/id/80/400/400","Active","0","2026-07-05 20:52:51","2026-07-05 20:52:51");





DROP TABLE IF EXISTS `quotation_items`;
CREATE TABLE `quotation_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quotation_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(50) DEFAULT 'SET',
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `quotation_id` (`quotation_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `quotation_items_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotation_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `quotation_items` VALUES("20","2","10","ສະບູອາບນ້ຳ","1.00","ກ້ອນ","10000.00","10000.00");
INSERT INTO `quotation_items` VALUES("21","2","9","ຜ້ງຊັກຟອກ 2ກກ","1.00","ຖົງ","28000.00","28000.00");
INSERT INTO `quotation_items` VALUES("22","2","7","ຊາຂຽວ ຊອງໃຫຍ່","2.00","ຊອງ","18000.00","36000.00");
INSERT INTO `quotation_items` VALUES("23","2","8","ນ້ຳອັດລົມ 1.25ລ","1.00","ຂວດ","8000.00","8000.00");
INSERT INTO `quotation_items` VALUES("24","2","5","ໝີ່ສຳຣະເຄັດ ຊອງໃຫຍ່","1.00","ຊອງ","5000.00","5000.00");
INSERT INTO `quotation_items` VALUES("25","2","1","ນ້ຳດື່ມສະອາດ 600ml","1.00","ຂວດ","4000.00","4000.00");
INSERT INTO `quotation_items` VALUES("26","2","2","ເຂົ້າສານ 5ກກ","1.00","ຖົງ","35000.00","35000.00");
INSERT INTO `quotation_items` VALUES("27","2","4","ນ້ຳປາ 500ml","1.00","ຂວດ","8000.00","8000.00");
INSERT INTO `quotation_items` VALUES("28","2","3","ນ້ຳມັນພືດ 1ລ","1.00","ຂວດ","12000.00","12000.00");
INSERT INTO `quotation_items` VALUES("29","2","6","ກາເຟສຳເລັດຮູບ","1.00","ກະປຸກ","25000.00","25000.00");





DROP TABLE IF EXISTS `quotations`;
CREATE TABLE `quotations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quotation_number` varchar(50) NOT NULL,
  `company_template` varchar(50) NOT NULL DEFAULT 'luang-prabarg',
  `supplier_id` int(11) DEFAULT NULL,
  `supplier_name` varchar(200) DEFAULT NULL,
  `supplier_contact` varchar(200) DEFAULT NULL,
  `ref_no` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_percent` decimal(5,2) DEFAULT 10.00,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `grand_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `terms` text DEFAULT NULL,
  `status` enum('Draft','Sent','Approved','Rejected') NOT NULL DEFAULT 'Draft',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `quotation_number` (`quotation_number`),
  KEY `supplier_id` (`supplier_id`),
  KEY `created_by` (`created_by`),
  KEY `idx_quotation_number` (`quotation_number`),
  CONSTRAINT `quotations_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `quotations_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `quotations` VALUES("2","QTN-20260706-0001","luang-prabarg","1"," CH.KARNCHANG(LAO)COMPANY LIMITED","ທ້າວ ສົມຊາຍ | 020 12345678","QUO-1400-2026-06-0010","2026-07-06","171000.00","0.00","10.00","17100.00","188100.00","","","Draft",NULL,"2026-07-06 10:57:54","2026-07-06 12:01:37");





DROP TABLE IF EXISTS `sale_items`;
CREATE TABLE `sale_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(200) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sale_items` VALUES("1","1","1","ນ້ຳດື່ມສະອາດ 600ml","5","4000.00","20000.00","2026-07-05 20:52:51");
INSERT INTO `sale_items` VALUES("2","1","4","ນ້ຳປາ 500ml","2","8000.00","16000.00","2026-07-05 20:52:51");
INSERT INTO `sale_items` VALUES("3","1","6","ກາເຟສຳເລັດຮູບ","1","25000.00","25000.00","2026-07-05 20:52:51");
INSERT INTO `sale_items` VALUES("4","2","2","ເຂົ້າສານ 5ກກ","3","35000.00","105000.00","2026-07-05 20:52:51");
INSERT INTO `sale_items` VALUES("5","2","3","ນ້ຳມັນພືດ 1ລ","2","12000.00","24000.00","2026-07-05 20:52:51");
INSERT INTO `sale_items` VALUES("6","3","2","ເຂົ້າສານ 5ກກ","3","35000.00","105000.00","2026-07-05 20:52:51");
INSERT INTO `sale_items` VALUES("7","3","1","ນ້ຳດື່ມສະອາດ 600ml","12","4000.00","48000.00","2026-07-05 20:52:51");
INSERT INTO `sale_items` VALUES("8","3","5","ໝີ່ສຳຣະເຄັດ ຊອງໃຫຍ່","5","4600.00","23000.00","2026-07-05 20:52:51");
INSERT INTO `sale_items` VALUES("9","4","9","ຜ້ງຊັກຟອກ 2ກກ","2","28000.00","56000.00","2026-07-05 20:52:51");
INSERT INTO `sale_items` VALUES("10","5","5","ໝີ່ສຳຣະເຄັດ ຊອງໃຫຍ່","8","5000.00","40000.00","2026-07-05 20:52:51");
INSERT INTO `sale_items` VALUES("11","6","1","","1","4000.00","4000.00","2026-07-05 22:32:27");





DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(200) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `supplier_name` varchar(200) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('percent','fixed') DEFAULT 'fixed',
  `tax_percent` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `grand_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT 'cash',
  `amount_paid` decimal(12,2) DEFAULT 0.00,
  `change_amount` decimal(12,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `status` enum('Completed','Refunded','Cancelled') NOT NULL DEFAULT 'Completed',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `customer_id` (`customer_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `created_by` (`created_by`),
  KEY `idx_invoice` (`invoice_number`),
  KEY `idx_date` (`created_at`),
  CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sales` VALUES("1","INV-20260701-0001",NULL,"ລູກຄ້າທົ່ວໄປ","","",NULL,NULL,"61000.00","0.00","fixed","0.00","0.00","61000.00","cash","65000.00","4000.00","","Completed","1","2026-07-01 16:15:00");
INSERT INTO `sales` VALUES("2","INV-20260630-0001","1","ສຸລິຍາ ວົງສະຫວັດ","020 55667788","ບ້ານໂພນສີໄຄ",NULL,NULL,"129000.00","5000.00","fixed","0.00","0.00","124000.00","QR Code","124000.00","0.00","ສ່ວນຫຼຸດລູກຄ້າປະຈຳ","Completed","1","2026-06-30 21:30:00");
INSERT INTO `sales` VALUES("3","INV-20260628-0001",NULL,"ລູກຄ້າທົ່ວໄປ","020 11112222","",NULL,NULL,"176000.00","10000.00","fixed","0.00","0.00","166000.00","cash","170000.00","4000.00","","Completed","2","2026-06-28 17:00:00");
INSERT INTO `sales` VALUES("4","INV-20260625-0001","2","ອະນຸສາ ແກ້ວມະນີ","020 99887766","ບ້ານສະພັນທະ",NULL,NULL,"56000.00","0.00","fixed","0.00","0.00","56000.00","cash","60000.00","4000.00","","Completed","1","2026-06-25 23:45:00");
INSERT INTO `sales` VALUES("5","INV-20260620-0001","3","ບຸນທອນ ສີສະຫວາດ","020 77441122","ບ້ານດົງໂດກ",NULL,NULL,"40000.00","0.00","fixed","0.00","0.00","40000.00","QR Code","40000.00","0.00","","Completed","2","2026-06-20 18:20:00");
INSERT INTO `sales` VALUES("6","INV-20260705-0001","3","ບຸນທອນ ສີສະຫວາດ","020 77441122","",NULL,"","4000.00","0.00","percent","0.00","0.00","4000.00","QR Code","4000.00","0.00","","Completed",NULL,"2026-07-05 22:32:27");





DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `settings` VALUES("1","store_name","Thiengtham","2026-07-06 10:53:51");
INSERT INTO `settings` VALUES("2","store_address","","2026-07-05 20:52:51");
INSERT INTO `settings` VALUES("3","store_phone","","2026-07-05 20:52:51");
INSERT INTO `settings` VALUES("4","store_email","","2026-07-05 20:52:51");
INSERT INTO `settings` VALUES("5","currency","LAK","2026-07-06 10:53:51");
INSERT INTO `settings` VALUES("6","currency_symbol","₭","2026-07-05 20:52:51");
INSERT INTO `settings` VALUES("7","tax_percent","0","2026-07-06 10:53:51");
INSERT INTO `settings` VALUES("8","paper_size","58mm","2026-07-06 10:53:51");
INSERT INTO `settings` VALUES("9","receipt_footer","","2026-07-05 20:52:51");
INSERT INTO `settings` VALUES("10","store_logo","https://ik.imagekit.io/ze1uqcd3p/pos-stock/1783310031_logo_zTUXio4y_.png?updatedAt=1783310034","2026-07-06 10:53:54");
INSERT INTO `settings` VALUES("11","invoice_terms","","2026-07-05 20:52:51");
INSERT INTO `settings` VALUES("17","bill_logo_width","350","2026-07-06 10:58:50");
INSERT INTO `settings` VALUES("18","bill_logo_height","150","2026-07-06 10:58:50");
INSERT INTO `settings` VALUES("19","bill_logo","https://ik.imagekit.io/ze1uqcd3p/pos-stock/bill/1783310044_logo_bill_M4WwP3KGR.png?updatedAt=1783310046","2026-07-06 10:54:06");
INSERT INTO `settings` VALUES("22","bill_logo_position","top-left","2026-07-06 10:58:50");
INSERT INTO `settings` VALUES("23","bill_signature_width","200","2026-07-06 10:58:50");
INSERT INTO `settings` VALUES("24","bill_signature_height","130","2026-07-06 10:58:50");
INSERT INTO `settings` VALUES("25","bill_signature_position","center","2026-07-06 10:58:50");
INSERT INTO `settings` VALUES("26","bill_terms","","2026-07-06 10:58:50");
INSERT INTO `settings` VALUES("27","bill_signature","https://ik.imagekit.io/ze1uqcd3p/pos-stock/bill/1783310258_signature_28KoG0u0H.jpg?updatedAt=1783310259","2026-07-06 10:57:39");





DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `tax_percent` decimal(5,2) DEFAULT 10.00,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `suppliers` VALUES("1"," CH.KARNCHANG(LAO)COMPANY LIMITED","ທ້າວ ສົມຊາຍ","020 12345678","info@laowater.la","215 Lane xang Avenue ,Ban Xieng yeun Muang  Chanthabouly  ,Vientiane, Lao P D R ","ສົ່ງທຸກວັນຈັນ ແລະ ວັນພະຫັດ","10.00","Active","2026-01-15 15:00:00","2026-07-06 12:15:07");
INSERT INTO `suppliers` VALUES("2","XAYABURI POWER COMPANY LIMITED","ນາງ ມາລິກາ","020 23456789","order@thailaoshop.la","215 Lane xang Avenue ,Ban Xieng yeun Muang  Chanthabouly  ,Vientiane, Lao P D R ","ສິນຄ້າອຸປະໂພກ ແລະ ບໍລິໂພກ","10.00","Active","2026-02-01 16:00:00","2026-07-06 10:56:56");
INSERT INTO `suppliers` VALUES("3","NAM NGUM 2 POWER COMPANY LIMITED","ທ້າວ ບຸນມີ","020 34567890","vte.dist@example.la","215 Lane xang Avenue ,Ban Xieng yeun Muang  Chanthabouly  ,Vientiane, Lao P D R ","ສົ່ງຟຣີເມື່ອສັ່ງ 500,000 ກີບຂຶ້ນໄປ","10.00","Active","2026-02-15 17:00:00","2026-07-06 10:56:27");





DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `role` enum('admin','staff') NOT NULL DEFAULT 'staff',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` VALUES("1","admin","$2y$10$jqUyvZnfaFvtc/F/cgTWG.ziCX4F/yjYmIy61xGd1fT9xpHCjEbei","Admin User",NULL,NULL,"admin","Active","2026-07-05 20:52:51","2026-07-05 20:52:51");
INSERT INTO `users` VALUES("2","staff","$2y$10$jqUyvZnfaFvtc/F/cgTWG.ziCX4F/yjYmIy61xGd1fT9xpHCjEbei","Staff User",NULL,NULL,"staff","Active","2026-07-05 20:52:51","2026-07-05 20:52:51");



