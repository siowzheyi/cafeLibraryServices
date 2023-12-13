-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2023 at 04:18 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_cafe_services`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `id` varchar(10) NOT NULL,
  `library_id` varchar(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `title` varchar(20) NOT NULL,
  `content` text NOT NULL,
  `expired_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `beverage`
--

CREATE TABLE `beverage` (
  `id` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `item_category_id` varchar(10) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `cafe_id` varchar(10) NOT NULL,
  `remark` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` varchar(10) NOT NULL,
  `item_id` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `genre` varchar(20) NOT NULL,
  `author_name` varchar(40) NOT NULL,
  `publisher_name` varchar(50) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `stock_count` int(5) NOT NULL,
  `remainder_count` int(5) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `availability` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` varchar(10) NOT NULL,
  `item_id` varchar(10) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `quantity` int(100) NOT NULL,
  `start_booked_at` datetime NOT NULL,
  `end_booked_at` datetime NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `is_handled` int(1) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cafe`
--

CREATE TABLE `cafe` (
  `id` varchar(10) NOT NULL,
  `library_id` varchar(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `item_id` varchar(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `fees_category`
--

CREATE TABLE `fees_category` (
  `id` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `remark` varchar(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `library_id` varchar(10) DEFAULT NULL,
  `item_category_id` varchar(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `remark` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

CREATE TABLE `item_category` (
  `id` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `library`
--

CREATE TABLE `library` (
  `id` varchar(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` varchar(10) NOT NULL,
  `number` int(4) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `beverage_id` varchar(10) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `quantity` int(4) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `table_id` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` varchar(10) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `library_id` varchar(10) DEFAULT NULL,
  `cafe_id` varchar(10) DEFAULT NULL,
  `book_id` varchar(10) DEFAULT NULL,
  `equipment_id` varchar(10) DEFAULT NULL,
  `room_id` varchar(10) DEFAULT NULL,
  `beverage_id` varchar(10) DEFAULT NULL,
  `fees_category_id` varchar(10) NOT NULL,
  `booking_id` varchar(10) NOT NULL,
  `quantity` int(100) NOT NULL,
  `receipt_no` varchar(10) NOT NULL,
  `subtotal` decimal(7,2) NOT NULL,
  `service_tax_amount` decimal(2,1) NOT NULL DEFAULT 0.1,
  `sst_amount` decimal(3,2) NOT NULL DEFAULT 0.06,
  `total_price` decimal(7,2) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` varchar(10) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `remark` int(1) NOT NULL DEFAULT 1,
  `library_id` varchar(10) NOT NULL,
  `item_id` varchar(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` varchar(10) NOT NULL,
  `item_id` varchar(10) NOT NULL,
  `number` int(3) NOT NULL,
  `type` varchar(20) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `table`
--

CREATE TABLE `table` (
  `id` varchar(10) NOT NULL,
  `library_id` varchar(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `number` int(3) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `email` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `plain_password` varchar(20) NOT NULL,
  `role_id` varchar(10) NOT NULL,
  `library_id` varchar(10) DEFAULT NULL,
  `cafe_id` varchar(10) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `library_id` (`library_id`);

--
-- Indexes for table `beverage`
--
ALTER TABLE `beverage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_category_id` (`item_category_id`),
  ADD KEY `cafe_id` (`cafe_id`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cafe`
--
ALTER TABLE `cafe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `library_id` (`library_id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `fees_category`
--
ALTER TABLE `fees_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `library_id` (`library_id`),
  ADD KEY `item_category_id` (`item_category_id`);

--
-- Indexes for table `item_category`
--
ALTER TABLE `item_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `library`
--
ALTER TABLE `library`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beverage_id` (`beverage_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `library_id` (`library_id`),
  ADD KEY `cafe_id` (`cafe_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `equipment_id` (`equipment_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `beverage_id` (`beverage_id`),
  ADD KEY `fees_category_id` (`fees_category_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `library_id` (`library_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `table`
--
ALTER TABLE `table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `library_id` (`library_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `library_id` (`library_id`),
  ADD KEY `cafe_id` (`cafe_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
