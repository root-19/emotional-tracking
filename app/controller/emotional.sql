-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 04:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `emotional`
--

-- --------------------------------------------------------

--
-- Table structure for table `emotions`
--

CREATE TABLE `emotions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emotion` enum('happy','sad','good','bad') NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emotions`
--

INSERT INTO `emotions` (`id`, `user_id`, `emotion`, `date`, `created_at`) VALUES
(1, 1, 'happy', '2025-04-25', '2025-04-25 07:47:14'),
(3, 2, 'sad', '2025-04-25', '2025-04-25 08:01:19'),
(6, 1, 'bad', '2025-04-26', '2025-04-26 17:31:04'),
(8, 1, 'good', '2025-04-27', '2025-04-27 13:36:02'),
(9, 1, 'good', '2025-04-28', '2025-04-28 08:10:11'),
(11, 1, 'good', '2025-04-29', '2025-04-29 01:11:09'),
(14, 4, 'bad', '2025-04-29', '2025-04-29 02:15:51');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `type` enum('text','drawing','image') NOT NULL,
  `content` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `type`, `content`, `created_at`) VALUES
(10, 'text', 'test  journal', '2025-04-28 11:54:52'),
(11, 'drawing', 'uploads/drawing_680f6fa28ccd8.png', '2025-04-28 12:08:02'),
(12, 'drawing', 'uploads/drawing_680f73e3642c5.png', '2025-04-28 12:26:11'),
(13, 'drawing', 'uploads/drawing_680f75193b350.png', '2025-04-28 12:31:21'),
(14, 'drawing', 'uploads/drawing_680f7521263f3.png', '2025-04-28 12:31:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_notification_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `last_notification_date`) VALUES
(1, 'user', 'hperformanceexhaust@gmail.com', '$2y$10$ZFdFEA3srBQNHYbYC0hfO.mz5GETwlmWsqbPjktML.UVyNY.PebHi', 'user', '2025-04-25 06:12:21', NULL),
(3, 'users', 'wasieacuna123@gmail.com', '$2y$10$NjddCnPmI6TvDjnQtJrwjO5Q6V7MDO3l2zqdJYzsv9U5vZt2PfoUi', 'user', '2025-04-29 02:07:41', NULL),
(4, 'user', 'wasieacuna@gmail.com', '$2y$10$3/zIm9AxNaSnh/xIfUWlnebOYYgb8xqklabHXwHOOMOtIAWwCSz46', 'user', '2025-04-29 02:13:02', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `emotions`
--
ALTER TABLE `emotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`date`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `emotions`
--
ALTER TABLE `emotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
