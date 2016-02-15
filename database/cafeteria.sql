-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 15, 2016 at 05:35 PM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cafeteria`
--

create database cafeteria;

use cafeteria;
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
(6, 41, 2, 50, '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

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
(42, 'kasarli', 35, 2, 1, 'kasarli_tost.jpg');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `room_no`, `ext`, `is_admin`, `question`, `answer`, `pic`) VALUES
(11, 'ahmed', 'ahmed@ahmed.com', 'e10adc3949ba59abbe56e057f20f883e', 1000, 124, 0, 'what is your age ?', '24', 'img2.jpg'),
(12, 'ashor', 'ashor@ashor.com', 'e10adc3949ba59abbe56e057f20f883e', 1000, 122, 0, 'what is your age ?', '22', 'img5.jpg'),
(13, 'Admin', 'admin@admin.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 1, NULL, NULL, 'admin.jpg'),
(14, 'ali', 'ali@ali.com', 'e10adc3949ba59abbe56e057f20f883e', 1000, 123, 0, 'what is your age ?', '24', 'img4.jpg'),
(15, 'kareem', 'kareem@kareem.com', 'e10adc3949ba59abbe56e057f20f883e', 151, 111, 0, 'what is your age ?', '24', 'img2.jpg');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
