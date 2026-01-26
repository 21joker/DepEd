-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 03:14 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sample_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `english` decimal(5,2) NOT NULL,
  `science` decimal(5,2) NOT NULL,
  `math` decimal(5,2) NOT NULL,
  `filipino` decimal(5,2) NOT NULL,
  `mapeh` decimal(5,2) NOT NULL,
  `average` decimal(5,2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `english`, `science`, `math`, `filipino`, `mapeh`, `average`, `created`, `modified`) VALUES
(1, 2, '9.99', '9.99', '9.99', '9.99', '9.99', '9.99', '2024-12-07 01:28:20', '2024-12-07 01:28:20'),
(3, 4, '89.00', '78.00', '75.00', '80.00', '76.00', '79.60', '2024-12-09 00:22:15', '2024-12-09 00:22:15');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lastname` varchar(55) NOT NULL,
  `firstname` varchar(55) NOT NULL,
  `middlename` varchar(55) DEFAULT NULL,
  `email` varchar(55) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `lastname`, `firstname`, `middlename`, `email`, `verified`, `created`, `modified`) VALUES
(2, 3, 'Fletchero', 'Renz', 'Pogi', '', 0, '2024-11-25 07:39:18', '2024-11-25 07:39:18'),
(4, 6, 'Marcos', 'Bongbong', 'Duterte', '', 0, '2024-12-07 00:37:15', '2024-12-07 00:37:15'),
(5, 7, 'Duterte', 'Sarah', 'Romualdez', '', 0, '2024-12-07 00:37:51', '2024-12-07 00:37:51'),
(13, 15, 'Castro', 'Marlon', 'Lina', 'marloncastro0901@gmail.com', 0, '2024-12-09 01:44:20', '2024-12-09 01:44:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(55) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(55) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created`, `modified`) VALUES
(4, 'admin', '$2y$10$d.T42J0NYqYZ.JpmfFzJpu3rK4cWwebKKYkr/qoEkU4rCn8hl5ASe', 'Superuser', '2024-12-07 00:36:18', '2024-12-07 00:36:18'),
(5, 'student', '$2y$10$482/Txb1DdchKtFKnQv7x.7y7RhNt9Xi7s6.BGXROcGIS6T5Zzd8S', 'User', '2024-12-07 00:36:43', '2024-12-07 00:36:43'),
(6, 'president', '$2y$10$G6wB0LhPBvWzK4vYWN/9KeEHs.bjGtOXWW0T0ySDgBi8IhIpX9mVq', 'User', '2024-12-07 00:37:15', '2024-12-07 00:37:15'),
(7, 'vices', '$2y$10$ble8xQ655KQ5tyIf3QpwmuGHlAu9iwxpzx3MzrDCX0oZl339aBkS6', 'User', '2024-12-07 00:37:51', '2024-12-07 00:51:34'),
(15, 'noniepogi', '$2y$10$DAa9CI7Atr1iQS9PwUfSIe9gxwDUOqqWkRlVvZrh0dJKTOHzCAXDa', 'User', '2024-12-09 01:44:20', '2024-12-09 01:44:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
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
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
