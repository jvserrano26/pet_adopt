-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2025 at 07:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pet_adopt`
--

-- --------------------------------------------------------

--
-- Table structure for table `adoptions`
--

CREATE TABLE `adoptions` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `valid_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adoptions`
--

INSERT INTO `adoptions` (`id`, `pet_id`, `user_id`, `full_name`, `address`, `contact`, `reason`, `status`, `created_at`, `valid_id`) VALUES
(1, 2, 2, 'jv', 'housing', '099112211', 'idk', 'Approved', '2025-10-27 07:19:11', NULL),
(2, 1, 2, 'jay', 'housing', '0921212121', 'ha', 'Approved', '2025-10-27 07:53:28', NULL),
(5, 7, 2, 'jayv', 'vic', '0992328327', 'ffsfffjvuj', 'Approved', '2025-11-01 02:41:05', 'id.jpg'),
(6, 5, 2, 'von', 'villa victorias', '09664342123', 'I NEED A BUDDY', 'Approved', '2025-11-01 08:42:32', 'id.jpg'),
(7, 8, 2, 'sa33', 'sasa', '0932382382', 'ccc', 'Rejected', '2025-11-16 20:57:21', 'stock-vector-driver-license-with-male-photo-identification-or-id-card-template-vector-illustration-1227173818.jpg'),
(8, 8, 2, 'vv', 'vv', '887770099', 'vv', 'Approved', '2025-11-16 21:00:52', 'stock-vector-driver-license-with-male-photo-identification-or-id-card-template-vector-illustration-1227173818.jpg'),
(9, 6, 2, 'xx', 'xx', '09765654422', 'xx', 'Rejected', '2025-11-16 21:21:30', 'stock-vector-driver-license-with-male-photo-identification-or-id-card-template-vector-illustration-1227173818.jpg'),
(10, 9, 2, 'mark', 'brgy66', '0978432327', 'vv', 'Approved', '2025-11-16 21:33:11', 'stock-vector-driver-license-with-male-photo-identification-or-id-card-template-vector-illustration-1227173818.jpg'),
(11, 4, 2, 'gg', 'gg', '6677885544', 'gg', 'Rejected', '2025-11-16 21:54:01', 'stock-vector-driver-license-with-male-photo-identification-or-id-card-template-vector-illustration-1227173818.jpg'),
(12, 4, 2, 'sasa', 'sasa', '232323', 'asasa', 'Rejected', '2025-11-16 22:09:09', '1763330949_stock-vector-driver-license-with-male-photo-identification-or-id-card-template-vector-illustration-1227173818.jpg'),
(14, 4, 2, 'sasa', 'sasa', '12121', 'sasas', 'Rejected', '2025-11-16 22:18:18', 'stock-vector-driver-license-with-male-photo-identification-or-id-card-template-vector-illustration-1227173818.jpg'),
(15, 4, 2, 'kk', 'kkk', '5676454543543', 'jhjh', 'Rejected', '2025-11-17 16:07:51', 'stock-vector-driver-license-with-male-photo-identification-or-id-card-template-vector-illustration-1227173818.jpg'),
(16, 4, 2, 'kk', 'kk', '656756454', 'lkk', 'Rejected', '2025-11-17 16:14:45', 'stock-vector-driver-license-with-male-photo-identification-or-id-card-template-vector-illustration-1227173818.jpg'),
(22, 6, 2, 'sasas', 'sasas', 'sasa', 'sasa', 'Approved', '2025-11-19 03:34:30', 'stock-vector-driver-license-with-male-photo-identification-or-id-card-template-vector-illustration-1227173818.jpg'),
(23, 4, 2, 'ggfg', 'fdff', 'dfdf', 'extry', 'Approved', '2025-11-19 03:35:26', 'stock-vector-driver-license-with-male-photo-identification-or-id-card-template-vector-illustration-1227173818.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `date_sent` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `date_sent`) VALUES
(1, 'sas', 'sasa@gmail.com', NULL, 'sasa', '2025-11-01 09:47:00'),
(2, 'sasa', 'sasa@gmail.com', 'sasa', 'sasa', '2025-11-01 09:50:41'),
(3, 'sasa', 'ccc@gmail.com', 'aA', 'aA', '2025-11-01 10:03:02');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `type` enum('cat','dog') NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`id`, `name`, `age`, `type`, `image`) VALUES
(1, 'exam_dog1', 2, 'dog', 'Untitled design8.png'),
(2, 'exam_cat', 1, 'cat', '1.png'),
(4, 'exam.dog2', 1, 'dog', '2.png'),
(5, 'cat001', 1, 'cat', 'cat1.jpg'),
(6, 'cat002', 2, 'cat', 'cat2.jpg'),
(7, 'dog001', 1, 'dog', 'dog4.avif'),
(8, 'dog002', 2, 'dog', 'dog1.png'),
(9, 'fufu', 3, 'cat', 'cat2.jpg'),
(10, 'sample-again', 1, 'dog', '1763524607_images.webp'),
(11, 'finaldog', 1, 'dog', 'dog-puppy-on-garden-royalty-free-image-1586966191.avif'),
(13, 'finalcat', 1, 'cat', 'FELV-cat.jpg'),
(17, 'sample008', 2, 'dog', 'images.webp'),
(19, 'sample007', 1, 'dog', 'images.webp');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$DHH8Gvg0zQz4wNBKTHYbSu1O4TenSS8JUgzJHkc5Tmy.T8DkzKm8e', 'admin'),
(2, 'user1', '$2y$10$L/x4QkkWC6gY3l.tW1MBo.0JoWthE.Pi7yh52MfvXMYGpk/l8T3v2', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adoptions`
--
ALTER TABLE `adoptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_id` (`pet_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`age`,`type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adoptions`
--
ALTER TABLE `adoptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adoptions`
--
ALTER TABLE `adoptions`
  ADD CONSTRAINT `adoptions_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`),
  ADD CONSTRAINT `adoptions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
