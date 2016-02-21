-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 21, 2016 at 04:53 AM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cafeteria`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'drinks'),
(2, 'meals'),
(3, 'cakies'),
(4, 'pizza');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('processing','out for delivery','done') DEFAULT NULL,
  `order_price` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `datetime`, `status`, `order_price`, `room_id`) VALUES
(10, 11, '2016-02-20 15:53:58', 'done', 33, 1001),
(11, 11, '2016-02-20 16:00:18', 'done', 15, 1000),
(12, 15, '2016-02-20 16:01:22', 'done', 20, 1000),
(13, 15, '2016-02-20 16:25:48', 'done', 37, 1000),
(14, 11, '2016-02-20 17:02:13', 'out for delivery', 14, 1000),
(16, 15, '2016-02-21 01:38:20', 'done', 7, 1000),
(17, 14, '2016-02-21 02:00:35', 'done', 3, 1000),
(18, 12, '2016-02-21 02:23:09', 'processing', 5, 1001);

-- --------------------------------------------------------

--
-- Table structure for table `order_product`
--

CREATE TABLE IF NOT EXISTS `order_product` (
  `order_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `amount` int(11) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  `notes` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`order_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_product`
--

INSERT INTO `order_product` (`order_id`, `product_id`, `amount`, `total_price`, `notes`) VALUES
(0, 2, 1, 7, ''),
(0, 23, 1, 3, ''),
(0, 25, 1, 5, ''),
(1, 25, 2, 10, ''),
(1, 30, 2, 20, ''),
(1, 32, 1, 25, ''),
(2, 26, 2, 10, ''),
(2, 33, 2, 40, ''),
(3, 34, 2, 40, ''),
(3, 35, 2, 20, ''),
(3, 37, 2, 40, ''),
(4, 25, 1, 5, ''),
(4, 35, 1, 10, ''),
(4, 36, 2, 70, ''),
(5, 2, 3, 21, ''),
(6, 35, 2, 20, ''),
(6, 37, 2, 40, ''),
(6, 41, 2, 50, ''),
(10, 23, 1, 3, ''),
(10, 25, 1, 5, ''),
(10, 32, 1, 25, ''),
(11, 2, 1, 7, ''),
(11, 23, 1, 3, ''),
(11, 25, 1, 5, ''),
(12, 1, 1, 5, ''),
(12, 2, 1, 7, ''),
(12, 23, 1, 3, ''),
(12, 25, 1, 5, ''),
(13, 1, 1, 5, ''),
(13, 2, 1, 7, ''),
(13, 32, 1, 25, ''),
(14, 23, 3, 9, ''),
(14, 25, 1, 5, ''),
(16, 2, 1, 7, ''),
(17, 23, 1, 3, ''),
(18, 1, 1, 5, 'with suger');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT NULL,
  `pic` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category_id`, `is_available`, `pic`) VALUES
(1, 'tea', 5, 1, 1, 'tea.jpg'),
(2, 'tea with milk', 7, 1, 1, 'tea_with_milk.jpg'),
(23, 'water', 3, 1, 1, 'water.jpg'),
(25, 'pepsi', 5, 1, 1, 'cola.jpg'),
(26, 'sevenUp', 5, 1, 1, 'seven.jpg'),
(30, 'coafee', 10, 1, 1, 'coffee.jpg'),
(32, 'cheese pizza', 25, 4, 1, 'pizzacheesse.JPG'),
(33, 'Borger', 20, 2, 1, 'Borger.jpg'),
(34, 'Hot dog', 20, 2, 1, 'hotdog.jpg'),
(35, 'Fries', 10, 2, 1, 'fries.jpg'),
(36, 'Chicken Pizza', 35, 4, 1, 'checken pizza.jpg'),
(37, 'Chicken Sanswitch', 20, 2, 1, 'checken.jpg'),
(38, 'Cake', 25, 3, 1, 'cakie1.jpg'),
(39, 'Cookies', 40, 3, 1, 'cookies.jpg'),
(40, 'Ginger Cookies', 45, 3, 1, 'Ginger Cookies .jpg'),
(41, 'Snack Kiosk ', 25, 2, 1, 'Snack Kosk.jpg'),
(42, 'kasarli', 35, 2, 1, 'kasarli_tost.jpg'),
(43, '', 0, 0, 0, ''),
(44, '', 0, 0, 0, ''),
(45, '', 0, 0, 0, ''),
(46, '', 0, 0, 0, ''),
(47, '', 0, 0, 0, ''),
(48, '', 0, 0, 0, ''),
(49, '', 0, 0, 0, ''),
(50, '', 0, 0, 0, ''),
(51, '', 0, 0, 0, ''),
(52, '', 0, 0, 0, ''),
(53, '', 0, 0, 0, ''),
(54, '', 0, 0, 0, ''),
(55, '', 0, 0, 0, ''),
(56, '', 0, 0, 0, ''),
(57, '', 0, 0, 0, ''),
(58, '', 0, 0, 0, ''),
(59, '', 0, 0, 0, ''),
(60, '', 0, 0, 0, ''),
(61, '', 0, 0, 0, ''),
(62, '', 0, 0, 0, ''),
(63, '', 0, 0, 0, ''),
(64, '', 0, 0, 0, ''),
(65, '', 0, 0, 0, ''),
(66, '', 0, 0, 0, ''),
(67, '', 0, 0, 0, ''),
(68, '', 0, 0, 0, ''),
(69, '', 0, 0, 0, ''),
(70, '', 0, 0, 0, ''),
(71, '', 0, 0, 0, ''),
(72, '', 0, 0, 0, ''),
(73, '', 0, 0, 0, ''),
(74, '', 0, 0, 0, ''),
(75, '', 0, 0, 0, ''),
(76, '', 0, 0, 0, ''),
(77, '', 0, 0, 0, ''),
(78, '', 0, 0, 0, ''),
(79, '', 0, 0, 0, ''),
(80, '', 0, 0, 0, ''),
(81, '', 0, 0, 0, ''),
(82, '', 0, 0, 0, ''),
(83, '', 0, 0, 0, ''),
(84, '', 0, 0, 0, ''),
(85, '', 0, 0, 0, ''),
(86, '', 0, 0, 0, ''),
(87, '', 0, 0, 0, ''),
(88, '', 0, 0, 0, ''),
(89, '', 0, 0, 0, ''),
(90, '', 0, 0, 0, ''),
(91, '', 0, 0, 0, ''),
(92, '', 0, 0, 0, ''),
(93, '', 0, 0, 0, ''),
(94, '', 0, 0, 0, ''),
(95, '', 0, 0, 0, ''),
(96, '', 0, 0, 0, ''),
(97, '', 0, 0, 0, ''),
(98, '', 0, 0, 0, ''),
(99, '', 0, 0, 0, ''),
(100, '', 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `number` int(11) NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`number`) VALUES
(1000),
(1001);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `room_no` int(11) DEFAULT NULL,
  `ext` int(11) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  `question` varchar(50) DEFAULT NULL,
  `answer` varchar(50) DEFAULT NULL,
  `pic` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `room_no`, `ext`, `is_admin`, `question`, `answer`, `pic`) VALUES
(11, 'ahmed', 'ahmed@ahmed.com', 'e10adc3949ba59abbe56e057f20f883e', 1000, 124, 0, 'what is your age ?', '24', 'img2.jpg'),
(12, 'ashor', 'ashor@ashor.com', 'e10adc3949ba59abbe56e057f20f883e', 1000, 122, 0, 'what is your age ?', '22', 'img5.jpg'),
(13, 'Admin', 'admin@admin.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 1, NULL, NULL, 'admin.jpg'),
(14, 'ali', 'ali@ali.com', 'e10adc3949ba59abbe56e057f20f883e', 1000, 123, 0, 'what is your age ?', '24', 'img4.jpg'),
(15, 'kareem', 'kareem@kareem.com', 'e10adc3949ba59abbe56e057f20f883e', 151, 111, 0, 'what is your age ?', '24', 'img2.jpg');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
