-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql100.infinityfree.com
-- Generation Time: Jun 29, 2026 at 08:21 AM
-- Server version: 11.4.12-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41710498_rent`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'ຊຸດແມ່ຍິງ', 'ຊຸດໄໝສຳລັບແມ່ຍິງ', '2026-05-15 05:42:15'),
(2, 'ຊຸດຜູ້ຊາຍ', 'ຊຸດໄໝສຳລັບຜູ້ຊາຍ', '2026-05-15 05:42:15'),
(3, 'ຊຸດເດັກນ້ອຍ', 'ຊຸດໄໝສຳລັບເດັກນ້ອຍ', '2026-05-15 05:42:15'),
(4, 'ເຄື່ອງປະດັບ', 'ສາຍຄໍ, ຕຸ້ມຫູ, ແລະ ອື່ນໆ', '2026-05-15 05:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `id_card_no` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `village` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  `customer_type` varchar(100) DEFAULT 'Walk-in',
  `status` enum('Active','Inactive','Blacklisted') DEFAULT 'Active',
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `avatar`, `fullname`, `phone`, `email`, `id_card_no`, `address`, `province`, `district`, `village`, `occupation`, `gender`, `date_of_birth`, `contact_person`, `contact_phone`, `customer_type`, `status`, `notes`, `created_by`, `created_at`) VALUES
(1, NULL, 'ສົມພອນ ແກ້ວມະນີ', '02055551111', 'somphone@example.com', NULL, NULL, NULL, NULL, NULL, NULL, 'Male', NULL, NULL, NULL, 'Corporate', 'Active', NULL, NULL, '2026-05-15 05:42:15'),
(2, NULL, 'ນາງ ວິໄລວັນ', '02022223333', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Walk-in', 'Active', NULL, NULL, '2026-05-15 05:42:15'),
(3, NULL, 'ທ້າວ ສຸດໃຈ', '02099998888', 'sudchai@example.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Regular', 'Active', NULL, NULL, '2026-05-15 05:42:15'),
(4, NULL, 'ບົວເງີນ', '54497888', NULL, '01061995', 'ນາໂພເໜຶອ', NULL, NULL, NULL, NULL, 'Female', NULL, NULL, NULL, 'VIP', 'Active', NULL, 1, '2026-06-24 13:00:57');

-- --------------------------------------------------------

--
-- Table structure for table `customer_types`
--

CREATE TABLE IF NOT EXISTS `customer_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_types`
--

INSERT INTO `customer_types` (`id`, `name`, `created_at`) VALUES
(1, 'Walk-in', '2026-05-26 02:07:55'),
(2, 'Regular', '2026-05-26 02:07:55'),
(3, 'VIP', '2026-05-26 02:07:55'),
(4, 'Corporate', '2026-05-26 02:07:55');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int(11) NOT NULL,
  `expense_date` date NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_date`, `category_id`, `amount`, `description`, `created_by`, `created_at`) VALUES
(1, '2026-05-15', 3, '50000.00', 'ຄ່າຊັກຊຸດ S003', 1, '2026-05-15 05:42:15'),
(2, '2026-05-15', 2, '120000.00', 'ຄ່າໄຟຟ້າເດືອນ 5', 1, '2026-05-15 05:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE IF NOT EXISTS `expense_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'ຄ່າເຊົ່າສະຖານທີ່', 'ຄ່າເຊົ່າຮ້ານລາຍເດືອນ', '2026-05-15 05:42:15'),
(2, 'ຄ່ານ້ຳ-ຄ່າໄຟ', 'ຄ່າສາທາລະນູປະໂພກ', '2026-05-15 05:42:15'),
(3, 'ຄ່າຊັກລີດ', 'ຄ່າຈ້າງຊັກເຄື່ອງ', '2026-05-15 05:42:15'),
(4, 'ຄ່າພະນັກງານ', 'ເງິນເດືອນພະນັກງານ', '2026-05-15 05:42:15'),
(5, 'ອື່ນໆ', 'ລາຍຈ່າຍອື່ນໆ', '2026-05-15 05:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `details`, `created_at`) VALUES
(1, 'ເງິນສົດ (Cash)', 'ຊຳລະດ້ວຍເງິນສົດ', '2026-05-15 05:42:15'),
(2, 'QR CODE', 'ຊຳລະຜ່ານ QR Code', '2026-05-15 05:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `bust` varchar(50) DEFAULT NULL,
  `waist` varchar(50) DEFAULT NULL,
  `hips` varchar(50) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `rental_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Available','Rented','Cleaning','Repairing','Inactive') DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `code`, `name`, `category_id`, `size`, `bust`, `waist`, `hips`, `color`, `rental_price`, `stock`, `image`, `status`, `created_at`) VALUES
(1, 'S001', 'ຊຸດໄໝລາວສີຟ້າ', 1, 'M', '', '', '', '', '150000.00', 13, 'https://ik.imagekit.io/ceo2gbv21/rent_miss_clean/products/1779764196_1-11_8aoFcBYoo.jpg?updatedAt=1779764200', 'Rented', '2026-05-15 05:42:15'),
(2, 'S002', 'ຊຸດໄໝລາວສີບົວ', 1, 'L', '', '', '', '', '180000.00', 0, NULL, 'Rented', '2026-05-15 05:42:15'),
(3, 'M001', 'ຊຸດໄໝຊາຍສີຄີມ', 2, 'XL', '', '', '', '', '200000.00', 0, 'https://ik.imagekit.io/ceo2gbv21/rent_miss_clean/products/1779950172_WhatsApp-Image-2025-08-29-at-12.56.58-2_W2n3WDp1R.jpeg?updatedAt=1779950174', 'Rented', '2026-05-15 05:42:15'),
(4, 'S003', 'ຊຸດໄໝລາວສີແດງ', 1, 'S', '', '', '', '', '150000.00', 0, NULL, 'Rented', '2026-05-15 05:42:15'),
(5, 'S004', 'ຜ້າຄຸມບ່າ ລາດຊະວົງ', 2, 'L', '', '', '', 'sdgmltd69rbc41u', '200000.00', 4, 'https://ik.imagekit.io/ceo2gbv21/rent_miss_clean/products/1779762975_images_yPh5L7wCL.jpeg?updatedAt=1779762977', 'Rented', '2026-05-15 06:51:30');

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE IF NOT EXISTS `rentals` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(50) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pickup_date` date NOT NULL,
  `return_date` date NOT NULL,
  `total_rental_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_deposit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(20) DEFAULT 'Paid',
  `change_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_method_id` int(11) DEFAULT NULL,
  `guarantee_id_card` tinyint(1) DEFAULT 0,
  `guarantee_passport` tinyint(1) DEFAULT 0,
  `guarantee_family_book` tinyint(1) DEFAULT 0,
  `guarantee_cash` tinyint(1) DEFAULT 0,
  `status` enum('Pending','Active','Returned','Overdue','Cancelled') DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`id`, `invoice_number`, `customer_id`, `user_id`, `pickup_date`, `return_date`, `total_rental_fee`, `total_deposit`, `discount`, `tax_amount`, `grand_total`, `paid_amount`, `payment_status`, `change_amount`, `payment_method_id`, `guarantee_id_card`, `guarantee_passport`, `guarantee_family_book`, `guarantee_cash`, `status`, `notes`, `created_at`) VALUES
(24, 'INV-2406-S1-0001', 1, 1, '2026-06-24', '2026-06-27', '880000.00', '111110.00', '0.00', '0.00', '991110.00', '991110.00', 'Paid', '0.00', 2, 0, 0, 0, 1, 'Active', NULL, '2026-06-24 11:07:33'),
(25, 'INV-2406-S1-0002', 3, 1, '2026-06-22', '2026-06-25', '530000.00', '500000.00', '20000.00', '0.00', '1010000.00', '1010000.00', 'Paid', '0.00', 1, 0, 0, 0, 1, 'Overdue', NULL, '2026-06-24 12:52:49'),
(26, 'INV-2406-S1-0003', 4, 1, '2026-06-20', '2026-06-23', '1060000.00', '0.00', '0.00', '0.00', '1060000.00', '1060000.00', 'Paid', '0.00', 2, 0, 1, 0, 0, 'Overdue', NULL, '2026-06-24 13:11:22'),
(27, 'INV-2406-S1-0004', 4, 1, '2026-06-20', '2026-06-22', '800000.00', '0.00', '0.00', '0.00', '800000.00', '800000.00', 'Paid', '0.00', 2, 0, 1, 0, 0, 'Overdue', NULL, '2026-06-24 13:46:46'),
(28, 'INV-2406-S1-0005', 4, 1, '2026-06-18', '2026-06-21', '530000.00', '500000.00', '0.00', '0.00', '1030000.00', '1030000.00', 'Paid', '0.00', 2, 0, 0, 0, 1, 'Overdue', NULL, '2026-06-24 13:48:04'),
(29, 'INV-2406-S1-0006', 1, 1, '2026-06-16', '2026-06-20', '400000.00', '0.00', '0.00', '0.00', '400000.00', '400000.00', 'Paid', '0.00', 1, 0, 1, 0, 0, 'Overdue', NULL, '2026-06-24 13:50:39'),
(30, 'INV-2406-S1-0007', 2, 1, '2026-06-24', '2026-06-27', '350000.00', '0.00', '0.00', '0.00', '350000.00', '350000.00', 'Paid', '0.00', 2, 1, 0, 0, 0, 'Active', NULL, '2026-06-24 14:22:35'),
(31, 'INV-2606-S1-0001', 4, 1, '2026-06-26', '2026-06-29', '350000.00', '1500000.00', '0.00', '0.00', '1850000.00', '1850000.00', 'Paid', '0.00', 2, 0, 0, 0, 1, 'Active', NULL, '2026-06-26 15:02:42');

-- --------------------------------------------------------

--
-- Table structure for table `rental_items`
--

CREATE TABLE IF NOT EXISTS `rental_items` (
  `id` int(11) NOT NULL,
  `rental_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rental_price` decimal(15,2) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental_items`
--

INSERT INTO `rental_items` (`id`, `rental_id`, `product_id`, `rental_price`, `qty`) VALUES
(44, 24, 5, '200000.00', 1),
(45, 24, 4, '150000.00', 1),
(46, 24, 3, '200000.00', 1),
(47, 24, 2, '180000.00', 1),
(48, 24, 1, '150000.00', 1),
(49, 25, 2, '180000.00', 1),
(50, 25, 1, '150000.00', 1),
(51, 25, 3, '200000.00', 1),
(52, 26, 1, '150000.00', 1),
(53, 26, 2, '180000.00', 2),
(54, 26, 3, '200000.00', 1),
(55, 26, 4, '150000.00', 1),
(56, 26, 5, '200000.00', 1),
(57, 27, 5, '200000.00', 4),
(58, 28, 4, '150000.00', 1),
(59, 28, 3, '200000.00', 1),
(60, 28, 2, '180000.00', 1),
(61, 29, 5, '200000.00', 2),
(62, 30, 5, '200000.00', 1),
(63, 30, 1, '150000.00', 1),
(64, 31, 1, '150000.00', 1),
(65, 31, 5, '200000.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `store_phone` varchar(50) DEFAULT NULL,
  `store_email` varchar(255) DEFAULT NULL,
  `store_address` text DEFAULT NULL,
  `currency` varchar(20) DEFAULT '₭',
  `tax_percent` decimal(5,2) DEFAULT 0.00,
  `paper_size` enum('58mm','80mm','A4') DEFAULT '58mm',
  `rental_terms` text DEFAULT NULL,
  `receipt_footer` text DEFAULT NULL,
  `store_logo` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `store_name`, `store_phone`, `store_email`, `store_address`, `currency`, `tax_percent`, `paper_size`, `rental_terms`, `receipt_footer`, `store_logo`, `updated_at`) VALUES
(1, 'Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ', '02054497888', 'contact@missclean.com', 'ບ້ານ ວຽງຈະເລີນ ເມືອງໄຊເສດຖາ ນະຄອນຫຼວງວຽງຈັນ', 'ກີບ', '0.00', '80mm', '1. ສິນຄ້າທີຕົກລົງເຊົ່າ ຫຼື ຂາຍແລ້ວບໍ່ສາມາດຄືນເງີນໄດ້ໃນທຸກກໍລະນີ\r\n2. ເຊັກເຄື່ອງລະອຽດທຸກຄັ້ງກ່ອນອອກຈາກຮ້ານ\r\n3. ຫ້າມນໍາໄປຍັບ ຫຼື ແປງສະພາບເຄື່ອງ ກໍລະນິກວດພົບປັບໄໝ 100% ຂອງມູນຄ່າເຄື່ອງ\r\n4. ກຳນົດເຊົ່າເຄື່ອງ 3 ມື້ ລວມມື້ເຊົ່າອອກຮ້ານ. ກາຍກຳນົດປັບໄໝມື້ລະ 300,000 ກີບ\r\n5. ກໍລະນີເປື້ອນຫຼາຍທາງຮ້ານຂໍເກັບຄ່າສະປາ 150,000 ກີບ\r\n6. ຊຸດມີຕໍານິຈົນບໍ່ສາມາດນໍາໃຊ້ຕໍ່ໄດ້ ລູກຄ້າຕ້ອງຊື້ເຕັມມູນຄ່າເຄື່ອງ\r\n7. ຫ້າມຊັກເອງເດັດຂາດ, ນຸ່ງອອກແລ້ວສົ່ງກັບຮ້ານທັນທີ\r\n8. ເອກະສານມັດຈໍາ (ຝາກໄວ້ເກີນ 3 ວັນ) ທາງຮ້ານຈະບໍ່ຮັບຜິດຊອບ', 'ກະລຸນນໍາບີນມາຮັບເຄື່ອງທຸກຄັ້ງ\r\n      ຂໍຂອບໃຈທີໃຊ້ບໍລິການ', 'https://ik.imagekit.io/ceo2gbv21/rent_miss_clean/1779762905_logo_mPTKLUKEE.jpg?updatedAt=1779762907', '2026-06-24 14:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `role` enum('admin','staff') DEFAULT 'staff',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `phone`, `avatar`, `role`, `status`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', '02055555555', NULL, 'admin', 'Active', '2026-05-15 05:42:15'),
(2, 'staff1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ພະນັກງານ ຂາຍ', '02055555555', 'https://ik.imagekit.io/ceo2gbv21/rent_miss_clean/staff/1782398801_icon-192_sqa1MWCQ9.png?updatedAt=1782398803', 'staff', 'Active', '2026-05-15 05:42:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_types`
--
ALTER TABLE `customer_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rental_items`
--
ALTER TABLE `rental_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer_types`
--
ALTER TABLE `customer_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `rental_items`
--
ALTER TABLE `rental_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
