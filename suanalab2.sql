-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2026 at 12:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suanalab2`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Electronics'),
(2, 'Office Supplies'),
(3, 'Furniture'),
(4, 'Books'),
(5, 'Accessories');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `stock`, `category_id`, `supplier_id`, `created_at`) VALUES
(1, 'Laptop', 'Core i5 laptop suitable for work, study, and everyday use', 45000.00, 10, 1, 1, '2026-06-15 09:42:27'),
(2, 'Keyboard', 'Compact keyboard for efficient typing and office tasks', 1200.00, 25, 1, 1, '2026-06-15 09:42:27'),
(3, 'Mouse', 'Optical mouse with smooth tracking and ergonomic design', 800.00, 30, 1, 1, '2026-06-15 09:42:27'),
(4, 'Printer Paper', 'High-quality A4 printer paper for office and school use', 250.00, 100, 2, 2, '2026-06-15 09:42:27'),
(5, 'Stapler', 'Durable stapler for organizing documents and reports', 150.00, 40, 2, 2, '2026-06-15 09:42:27'),
(6, 'Notebook', 'Ruled notebook ideal for taking notes and assignments', 80.00, 50, 4, 2, '2026-06-15 09:42:27'),
(7, 'Office Chair', 'Comfortable office chair with ergonomic back support', 3500.00, 15, 3, 3, '2026-06-15 09:42:27'),
(8, 'Study Desk', 'Spacious study desk suitable for home and office work', 5000.00, 8, 3, 3, '2026-06-15 09:42:27'),
(9, 'Bookshelf', 'Wooden bookshelf with multiple storage shelves', 4200.00, 5, 3, 3, '2026-06-15 09:42:27'),
(10, 'USB Flash Drive', 'Portable USB flash drive for storing and transferring files', 600.00, 20, 5, 1, '2026-06-15 09:42:27'),
(11, 'Headphones', 'Stereo headphones with clear sound and comfortable fit', 1500.00, 12, 5, 1, '2026-06-15 09:42:27'),
(12, 'Backpack', 'Lightweight backpack with multiple compartments for storage', 900.00, 18, 5, 2, '2026-06-15 09:42:27');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(100) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `contact_person`, `phone`) VALUES
(1, 'TechSource Inc.', 'John Cruz', NULL),
(2, 'Office Depot PH', 'Maria Santos', NULL),
(3, 'Furniture Hub', 'Pedro Reyes', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
