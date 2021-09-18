-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2021 at 03:50 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `brand` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `brand`) VALUES
(1, 'Nike'),
(2, 'Puma'),
(3, 'Levis'),
(4, 'Polo'),
(5, 'Sketchers'),
(6, 'Calvin Klein'),
(7, 'Charter Club'),
(8, 'Chiffon'),
(9, 'Helly Hanson'),
(10, 'Pieces'),
(11, 'Tommy Hilfiger'),
(12, 'Vince Camuto');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `items` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `expire_date` datetime NOT NULL,
  `paid` int(11) NOT NULL DEFAULT 0,
  `shipped` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `items`, `expire_date`, `paid`, `shipped`) VALUES
(1, '[{\"id\":\"13\",\"size\":\"S\",\"quantity\":\"1\"}]', '2021-10-18 14:46:28', 1, 0),
(2, '[{\"id\":\"15\",\"size\":\"M\",\"quantity\":\"1\"}]', '2021-10-18 15:05:26', 1, 0),
(3, '[{\"id\":\"15\",\"size\":\"M\",\"quantity\":\"1\"}]', '2021-10-18 15:24:55', 1, 0),
(5, '[{\"id\":\"15\",\"size\":\"M\",\"quantity\":\"1\"}]', '2021-10-18 15:46:24', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `parent`) VALUES
(1, 'Men', 0),
(2, 'Women', 0),
(3, 'Boys', 0),
(4, 'Girls', 0),
(5, 'Shirts', 1),
(6, 'Pants', 1),
(7, 'Shirts', 2),
(8, 'Pants', 2),
(9, 'Shoes', 2),
(10, 'Dresses', 2),
(11, 'Shoes', 1),
(12, 'Accessories', 1),
(13, 'Shirts', 3),
(14, 'Pants', 3),
(15, 'Dresses', 4),
(16, 'Shoes', 4),
(17, 'Accessories', 2),
(24, 'Gifts', 0),
(25, 'Home Decor', 24),
(27, 'Shoes', 3),
(28, 'Pants', 4),
(29, 'Skirts', 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `list_price` decimal(10,2) NOT NULL,
  `brand` int(11) NOT NULL,
  `categories` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `image` text NOT NULL,
  `description` text NOT NULL,
  `featured` tinyint(4) NOT NULL DEFAULT 0,
  `sizes` text NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `list_price`, `brand`, `categories`, `image`, `description`, `featured`, `sizes`, `deleted`) VALUES
(1, 'Levis Jeans', '29.99', '39.99', 1, '6', '/ecommerceProject/images/products/men4.png', 'These jeans are amazing. They are super comfy and sexy! Buy them.', 1, '28:10:2,32:10:2,36:10:2,38:10:2,40:10:2', 0),
(2, 'Beautiful Shirt', '19.99', '24.99', 1, '5', '/ecommerceProject/images/products/men1.png', 'What a beautiful blue colored polo-shirt.', 1, 'S:10:2,M:10:2,L:10:2,XL:10:2', 0),
(3, 'Generic Shirt', '14.99', '24.99', 3, '13', '/ecommerceProject/images/products/d281df48d5904d64ee36430418ab813b.png', 'This is a generic polo shirt for boys.', 1, 'S:10:2,M:10:2,L:10:2,XL:10:2', 0),
(4, 'High Heels', '99.99', '119.99', 1, '9', '/ecommerceProject/images/products/94c5942fe8470655aec0ef0a04df1bad.jpg', 'Beautiful black tall stiletto heel sandals with a chunky platform and ankle strap to add stability, these cross strap sandals are all play. Pair these with your fave skinny jeans or mini skirt. These babies are ready for a night out, are you?', 1, '34:10:2,35:10:2,36:10:2,37:10:2,38:10:2,39:10:2', 0),
(5, 'Serious Elegant Shoes', '45.99', '55.99', 2, '9', '/ecommerceProject/images/products/80187e4d47bcec27b6275c070436bfb8.png', 'Black and Gold stylish shoes for women.', 1, '34:10:2,35:10:2,36:10:2,37:10:2,38:10:2,39:10:2', 0),
(6, 'Summer Dress', '39.99', '49.99', 11, '10', '/ecommerceProject/images/products/9d86b78282d5ba63022eb67e64bf0eaa.png', 'Beautiful summer purple dress.', 1, 'S:10:2,M:10:2,L:10:2,XL:10:2', 0),
(7, 'Striped Hoodie', '7.99', '9.99', 7, '13', '/ecommerceProject/images/products/e637055ac91d9b75b56681773116bb91.png', 'Cool white striped hoodie.', 1, 'S:10:2,M:10:2,L:10:2,XL:10:2', 0),
(8, 'Modern Pants', '29.99', '39.00', 9, '14', '/ecommerceProject/images/products/56def07c9acddd9cebe89e035fdbcc00.png', 'Black  jeans', 1, 'S:10:2,M:10:2,L:10:2,XL:10:2', 0),
(9, 'Cover Alls', '39.99', '49.99', 11, '14', '/ecommerceProject/images/products/1516fa2b3d87af11dba9c8f2ed060833.png', 'Cute jumper pants', 1, 'S:10:2,M:10:2,L:10:2,XL:10:2', 0),
(10, 'Princess Dress', '99.99', '109.99', 10, '15', '/ecommerceProject/images/products/46ae46ca1fc76450cebf116f9934eed8.jpeg', 'Beautiful red princess dress', 1, 'S:10:2,M:10:2,L:10:2,XL:10:2', 0),
(11, 'Average Purse', '55.99', '69.99', 6, '17', '/ecommerceProject/images/products/1071f53c9d62ce36d3c608d4acfe1608.png', 'Stylish black hand bag', 1, 'Small:10:2,Normal:10:2,Big:10:2', 0),
(12, 'Long Skirt', '19.99', '24.99', 8, '29', '/ecommerceProject/images/products/096692b9978fd6e4cf6dda3ee29656c2.png', 'long skirt for women', 1, 'S:10:2,M:10:2,L:10:2,XL:10:2', 0),
(13, 'Blouse', '9.99', '14.99', 8, '7', '/ecommerceProject/images/products/98c8e975e36d04932bbea7a24ea5982d.png', 'pink and black blouse', 1, 'S:9:2,M:10:2,L:10:2,XL:10:2', 0),
(14, 'Maroon dress', '14.99', '19.99', 10, '10', '/ecommerceProject/images/products/ef4c4be38f2eeea8286c89ff29f87923.jpeg', 'Beautiful maroon dress', 1, 'S:10:2,M:10:2,L:10:2,XL:10:2', 0),
(15, 'Black &amp; Red Shoes', '29.99', '34.99', 1, '11', '/ecommerceProject/images/products/6c6c1ac009ad86ee72f435417b507248.jpeg', 'Best shoes for men', 1, 'S:10:2,M:7:2,L:10:2,XL:10:2', 0),
(16, 'Polo Shirt', '9.99', '14.99', 4, '13', '/ecommerceProject/images/products/0455919e8eb7706dc7171b41d091b659.png', 'Yellow polo shirt for boys', 1, 'S:10:2,M:10:2,L:10:2,XL:10:2', 0),
(17, 'Barbie Shoes', '9.99', '14.99', 5, '16', '/ecommerceProject/images/products/4bb1a78e328435a2f0afa5f91d3a4347.jpeg', 'Cute pink barbies shoes for baby girl', 1, 'small:10:2', 0),
(18, 'Wrist Watch', '29.99', '34.99', 2, '12', '/ecommerceProject/images/products/5896bfefb2e06425f327a41fbfc980da.jpeg', 'Stylish wrist watch for men', 1, 'normal:10:2', 0);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `charge_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cart_id` int(11) NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(175) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `street2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(175) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(175) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `zip_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(175) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `txn_type` varchar(255) DEFAULT NULL,
  `txn_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `charge_id`, `cart_id`, `full_name`, `email`, `street`, `street2`, `city`, `state`, `zip_code`, `country`, `sub_total`, `tax`, `grand_total`, `description`, `txn_type`, `txn_date`) VALUES
(1, 'ch_3Jb32wIhayvpwqHo1brMLr7X', 1, 'Aakash Giri', 'giriaakash00@gmail.com', 'Ghaziabad', '', 'Ghaziabad', 'Uttar Pradesh', '201001', 'India', '9.99', '0.87', '10.86', '1 item from Shauntas Boutique.', 'charge', '2021-09-18 12:53:35'),
(2, 'ch_3Jb3FCIhayvpwqHo1oq3by9n', 2, 'Aakash Giri', 'giriaakash00@gmail.com', 'Ghaziabad', '', 'Ghaziabad', 'Uttar Pradesh', '201001', 'India', '29.99', '2.61', '32.60', '1 item from Shauntas Boutique.', 'charge', '2021-09-18 13:06:15'),
(4, 'ch_3Jb3sbIhayvpwqHo2sGVN9V7', 5, 'Aakash Giri', 'giriaakash00@gmail.com', 'Ghaziabad', '', 'Ghaziabad', 'Uttar Pradesh', '201001', 'India', '29.99', '2.61', '32.60', '1 item from Shauntas Boutique.', 'charge', '2021-09-18 13:46:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(175) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `permission` varchar(50) NOT NULL,
  `join_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `permission`, `join_date`, `last_login`) VALUES
(1, 'Aakash Giri', 'giriaakash00@gmail.com', '$2y$10$Byhm6bEdfJEWxueHz75rW.B6Sl/BIjhsBXzpJo.nDOYOdqnYTzDIW', 'admin,editor', '2021-09-17 00:16:10', '2021-09-18 14:27:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
