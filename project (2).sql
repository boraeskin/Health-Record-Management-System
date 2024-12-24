-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2024 at 09:28 PM
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
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Admin', 'admin@example.com', '$2y$10$UCwkf07mJQ.HP5szPo14gOwsBAg.SgFDeNCyxGfM91XoE8cW58fv2', '2024-12-15 11:39:31'),
(3, 'admin1', 'admin@outlook.com', '$2y$10$nn6XoSYOkVLxf5EPJJW.u.o1LLpqkC.XNiiUermunW3.kaNRfwO8K', '2024-12-17 14:36:36');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `user_id`, `doctor_id`, `appointment_date`, `description`) VALUES
(101, 322, 1, '2025-02-21 21:40:00', 'bora2'),
(107, 322, 1, '2024-12-12 23:00:00', 'sa'),
(114, 324, 5, '2025-01-15 11:53:00', 'onkolji prolemş'),
(117, 324, 5, '2025-01-08 11:58:00', 'EDNDR HOCZ'),
(118, 325, 6, '2024-12-23 12:04:00', 'kas ağrssadasds'),
(119, 325, 1, '2024-12-23 12:04:00', 'sa');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `specialization` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `email`, `password`, `specialization`) VALUES
(1, 'Salih', 'lixder@outlook.com', '$2y$10$Uk0uHUTqvR1xmPWs1PCPE.DJknZLewsJh38ASO88aodvnBckuUnbq', 'göz'),
(2, 'ekrem', 'e1@outlook.com', '$2y$10$GKvV5grHch1fiEVRVZpBieOJirXeZtJctfTHHFCpSieuUYxlHD9Bi', 'bogaz'),
(3, 'berkay', 'b32@outlook.com', '$2y$10$Vb5R91mqDEgAdXT/bsoY5.MUw0Wq7nFVVqvFsHgeoIHXEuu2cdA.K', 'göz doktoru'),
(4, 'denemedoktor', 'denemedoktor@outlook.com', '$2y$10$DowZw6vqDVFzziJ4jIyIauXp2E7leQYXOZg3wloGx9F/S0TuK./f2', 'deneme'),
(5, 'doktor1', 'doktor1@outlook.com', '$2y$10$Bt4k0F.IKUpoQC7B5svzHO7Lk6nef/w.sr8us5AYgQpd20YKG5xCa', 'onkoloji'),
(6, 'doktor2', 'doktor2@outlook.com', '$2y$10$xKU7YtnxDn9JNzrPFX0f5e43lGoKev83VLZbaYGik3QIu6sejL6Zm', 'onkoloji');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prescription_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `appointment_id`, `doctor_id`, `user_id`, `prescription_text`, `created_at`) VALUES
(42, 101, 1, 322, 'su ic', '2024-12-22 18:41:48'),
(45, 114, 5, 324, 'ilac \r\n\r\n\r\n\r\nMFMFM', '2024-12-23 08:57:33'),
(46, 118, 6, 325, 'aspirin', '2024-12-23 09:05:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(2, 'salih', 'dermanlix@outlook.com', '$2y$10$O5EMYvAaL./noyl3QHcC9OEas6IFFNji5WJweGaTCgxKpx07uBNKC'),
(3, 'ahmed', '123@outlook.com', '$2y$10$FR0lVLHXsRairjD2/otg7umTCswKlMgMADppsrqLp9PZQO3ORO7Te'),
(5, 'efe', 'e12@outlook.com', '$2y$10$6fYKuF16v8wZB/iPnMmScusWFO7wMk/eO0AyNynzjrthlLLHdCUom'),
(65, 'deniz', 'deniz@outlook.com', '$2y$10$SVHxPG3Ts33PRBYnDtDLRuvc.Rd5dFqd1uGEYbq3r7cqIq360chA6'),
(322, 'bora', 'bora030303@hotmail.com', '$2y$10$lCsho3ilEuFJEEQgG6wlMuuby9V69murGYA1wTasCswICxcP4p75y'),
(323, 'denemeuser', 'denemeuser@outlook.com', '$2y$10$7Wui1HqB/268l8Z.9vj/ROf0QlDPNeMvZD3zUTg0uDSnZBr5Gu4mi'),
(324, 'user1', 'user1@outlook.com', '$2y$10$SPDaJ/Br4l7utle6x8GPmetPBQ7EXx5k6V2DbzI3m.DClrTybgqoq'),
(325, 'user2', 'user2@outlook.com', '$2y$10$zTKGLYduTq4jUjiZe7NT7uJzwd4b8u6QUgduJOMSMbYH8XbIlmiuG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=326;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
