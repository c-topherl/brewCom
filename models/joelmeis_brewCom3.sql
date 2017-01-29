-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 18, 2016 at 11:16 PM
-- Server version: 5.5.42-37.1
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `joelmeis_brewCom`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_detail`
--

CREATE TABLE IF NOT EXISTS `cart_detail` (
  `user_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `price` double NOT NULL,
  `quantity` double NOT NULL,
  `unit_id` int(10) unsigned NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_header`
--

CREATE TABLE IF NOT EXISTS `cart_header` (
  `user_id` int(10) unsigned NOT NULL,
  `ship_date` date NOT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'pickup./delivery',
  `shipping_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'overnight/standard',
  `comments` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shipping_comments` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE IF NOT EXISTS `inventory` (
  `product_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `delivery_allowed` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT 'y/n',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` double NOT NULL,
  `order_date` date NOT NULL,
  `ship_date` date NOT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'pickup/delivery',
  `shipping_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'overnight/standard',
  `status` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'open/closed/void/test/etc',
  `comments` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'comments',
  `shipping_comments` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'comments',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `order_date`, `ship_date`, `type`, `shipping_type`, `status`, `comments`, `shipping_comments`, `last_updated`) VALUES
(1, 123, 0, '0000-00-00', '2016-02-10', 'pickup', '', 'open', '', '', '0000-00-00 00:00:00'),
(2, 123, 0, '0000-00-00', '2016-02-10', 'pickup', '', 'open', '', '', '0000-00-00 00:00:00'),
(3, 123, 0, '0000-00-00', '2016-02-10', 'pickup', '', 'open', '', '', '0000-00-00 00:00:00'),
(4, 123, 0, '0000-00-00', '2016-02-10', 'pickup', '', 'open', '', '', '0000-00-00 00:00:00'),
(5, 123, 0, '0000-00-00', '2016-02-10', 'pickup', '', 'open', '', '', '0000-00-00 00:00:00'),
(6, 123, 0, '0000-00-00', '2016-02-10', 'pickup', '', 'open', '', '', '0000-00-00 00:00:00'),
(7, 123, 0, '0000-00-00', '2016-02-10', 'pickup', '', 'open', '', '', '0000-00-00 00:00:00'),
(8, 123, 0, '0000-00-00', '2016-02-10', 'pickup', '', 'open', '', '', '0000-00-00 00:00:00'),
(9, 123, 0, '0000-00-00', '2016-02-10', 'pickup', '', 'open', '', '', '0000-00-00 00:00:00'),
(10, 123, 0, '0000-00-00', '2016-02-10', 'pickup', '', 'open', '', '', '0000-00-00 00:00:00'),
(11, 123, 0, '0000-00-00', '2016-02-10', 'pickup', '', 'open', '', '', '0000-00-00 00:00:00'),
(12, 125, 0, '0000-00-00', '2016-02-10', 'pickup', '', 'open', '', '', '0000-00-00 00:00:00'),
(13, 0, 0, '0000-00-00', '2016-02-10', 'pickup', '', 'open', '', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` double NOT NULL,
  `quantity` double NOT NULL,
  `unit_id` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `price`, `quantity`, `unit_id`, `last_updated`) VALUES
(0, 11, 1, 20, 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `class` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `code`, `description`, `price`, `class`, `active`, `last_updated`) VALUES
(1, 'zoe', 'the one they call zoe', 20, '2', 1, '0000-00-00 00:00:00'),
(2, 'joels', 'joels favorite beer', 99, '3', 1, '0000-00-00 00:00:00'),
(3, 'samantha', 'samanthas product', 8888, '4', 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `product_classes`
--

CREATE TABLE IF NOT EXISTS `product_classes` (
  `id` int(11) NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `product_classes`
--

INSERT INTO `product_classes` (`id`, `code`, `description`, `last_updated`) VALUES
(2, 'palelager', 'this is a pale lager', '0000-00-00 00:00:00'),
(3, 'beer', 'this is beer', '0000-00-00 00:00:00'),
(4, 'something', 'test for something else', '0000-00-00 00:00:00'),
(5, 'apolo', 'hes a dog', '0000-00-00 00:00:00'),
(7, 'jrm', 'joels initials', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE IF NOT EXISTS `units` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `abbreviation` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `last_updated`) VALUES
(0, 'joelmeister1209', 'joel@joelmeister.net', '04461c38290c7a8a2ceb3f116adf58224c00a7310d3dbc4e585ca924a05f7596', '2016-02-19 05:01:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE IF NOT EXISTS `user_addresses` (
  `address_id` int(11) NOT NULL COMMENT 'arbitrary',
  `user_id` int(11) NOT NULL,
  `address1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` int(10) NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_payment_methods`
--

CREATE TABLE IF NOT EXISTS `user_payment_methods` (
  `user_id` int(11) NOT NULL,
  `type` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'debit/credit',
  `card_number` varchar(19) COLLATE utf8_unicode_ci NOT NULL,
  `address_id` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE IF NOT EXISTS `user_profiles` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `signup_date` date NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE IF NOT EXISTS `user_settings` (
  `user_id` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_detail`
--
ALTER TABLE `cart_detail`
  ADD PRIMARY KEY (`user_id`), ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `cart_header`
--
ALTER TABLE `cart_header`
  ADD PRIMARY KEY (`user_id`), ADD KEY `ship_date` (`ship_date`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`product_id`,`unit_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`), ADD KEY `state` (`state`), ADD KEY `zipcode` (`zipcode`), ADD KEY `city` (`city`), ADD KEY `delivery_allowed` (`delivery_allowed`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`), ADD KEY `user_id` (`user_id`), ADD KEY `order_date` (`order_date`), ADD KEY `ship_date` (`ship_date`), ADD KEY `status` (`status`), ADD KEY `shipping_type` (`shipping_type`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`), ADD KEY `order_id` (`order_id`), ADD KEY `product_number` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `code` (`code`), ADD KEY `type` (`class`);

--
-- Indexes for table `product_classes`
--
ALTER TABLE `product_classes`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD KEY `product_id` (`product_id`), ADD KEY `abbreviation` (`abbreviation`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`address_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_payment_methods`
--
ALTER TABLE `user_payment_methods`
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `product_classes`
--
ALTER TABLE `product_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
