-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2025 at 01:25 PM
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
-- Database: `medical_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_notified` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('Pending','Accepted','Declined') DEFAULT 'Pending',
  `is_notified_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_name`, `email`, `specialization`, `doctor_id`, `appointment_date`, `appointment_time`, `created_at`, `is_notified`, `status`, `is_notified_admin`) VALUES
(1, 'wq', 'ejazsab50@gmail.com', 'otolaryngology', 15, '0000-00-00', '09:15:00', '2025-05-24 16:57:29', 1, 'Accepted', 1),
(2, 'ad', 'mhammadadeel11@gmail.com', 'Psychiatry', 16, '0000-00-00', '10:30:00', '2025-05-24 18:02:04', 0, 'Pending', 0),
(3, 'qw', 'admin@gmail', 'pediatricians', 21, '2025-05-26', '10:00:00', '2025-05-24 22:31:01', 0, 'Declined', 0),
(4, 'as', 'admin@gmail', 'pediatricians', 21, '2025-05-26', '10:00:00', '2025-05-24 22:33:45', 0, 'Accepted', 0),
(5, 'qw', 'admin@gmail', 'Immunology', 22, '2025-06-17', '10:15:00', '2025-05-25 13:02:05', 0, 'Accepted', 1),
(6, 'Student1655498', 'maharmainfarooq@gmail.com', 'pediatricians', 21, '2025-07-14', '10:15:00', '2025-06-19 14:59:08', 0, 'Accepted', 1),
(7, 'Huzaifa', 'huzaifa@gmial.com', 'Immunology', 22, '2025-06-23', '09:45:00', '2025-06-18 12:32:09', 0, 'Accepted', 0),
(8, 'Aliyan', 'admin1@gmail.com', 'pediatricians', 21, '2025-07-02', '10:30:00', '2025-06-21 09:37:42', 0, 'Accepted', 1);

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `city_name` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `name`, `city`, `city_name`, `province`, `created_at`) VALUES
(1, '', '', ' karachi', '', '2025-06-21 11:04:04'),
(2, '', '', ' karachi', '..', '2025-06-21 11:04:04'),
(3, '', '', 'Sukkur', 'sindh', '2025-06-21 11:04:25');

-- --------------------------------------------------------

--
-- Table structure for table `diseases`
--

CREATE TABLE `diseases` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `reviewed_date` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diseases`
--

INSERT INTO `diseases` (`id`, `name`, `description`, `image`, `reviewed_date`) VALUES
(1, 'fdsf', 'asdf', 'uploads/1747666735_blog-2.jpg', '2025-05-19 19:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `image` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `hospital_name` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `days` varchar(100) DEFAULT NULL,
  `timing` varchar(100) DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','approved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `location` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `degree` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_notified` tinyint(1) NOT NULL DEFAULT 0,
  `changes_pending` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`image`, `id`, `name`, `hospital_name`, `phone`, `specialization`, `city`, `days`, `timing`, `experience`, `description`, `status`, `created_at`, `location`, `address`, `degree`, `email`, `password`, `is_notified`, `changes_pending`) VALUES
('uploads/team-2.jpg', 21, 'Muhammad Bin Adeel', 'al-khidmat', '0333111', 'pediatricians', ' karachi', 'Monday,Tuesday,Wednesday', '09:00 AM - 11:00 AM,11:00 AM - 01:00 PM', '9', 'asd', 'approved', '2025-05-24 22:17:14', 'dsf', '14/5, Block 2 Nazimabad, Karachi, Karachi City, Sindh 74600', 'MS', 'admin@gmail', 'admin', 0, 0),
('uploads/Dr 2.png', 22, 'ejazsab', 'al-khidmat', '03331114816', 'Immunology', ' karachi', 'Monday,Tuesday,Wednesday', '11:00 AM - 01:00 PM', '2Y', 'DAS', 'approved', '2025-05-25 13:00:56', 'QWS', '14/5, Block 2 Nazimabad, Karachi, Karachi City, Sindh 74600', 'BDS', 'ejazsab50@gmail.com', 'ADMIN', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `subject`, `message`, `submitted_at`) VALUES
(1, '', '', '', '', '2025-05-19 19:10:03'),
(2, '', '', '', '', '2025-05-19 19:10:07'),
(3, 'ejazsab', 'admin@gmail.com', 'wsd', 'sad', '2025-05-19 19:10:39'),
(4, 'waf', 'admin1@gmail.com', 'arg', 'asfdg', '2025-05-19 20:13:02');

-- --------------------------------------------------------

--
-- Table structure for table `medical_news`
--

CREATE TABLE `medical_news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_news`
--

INSERT INTO `medical_news` (`id`, `title`, `content`, `author`, `image`, `likes`, `created_at`) VALUES
(1, 'kuch bhi ', 'kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi ', 'kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi ', '', 0, '2025-05-16 15:32:28'),
(2, 'kuch bhi ', 'kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi ', 'kuch bhi kuch bhi kuch bhi kuch bhi kuch bhi ', 'uploads/1747409583_Screenshot (1).png', 0, '2025-05-16 15:33:03'),
(3, 'asd', 'asd', 'asd', 'uploads/1747409869_Screenshot 2025-01-15 180928.png', 0, '2025-05-16 15:37:49');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `email`, `phone`, `gender`, `age`, `address`, `password`, `created_at`) VALUES
(1, 'ejazsab', 'admin@gmail.com', '09975648348', '', 8, 'sad', '$2y$10$wC8oFq0usGTowdor9rh4uevJHSeS5OOBQZbDFMzhw/G7mDnjW5Yw2', '2025-05-17 17:16:04'),
(2, 'ejazsab', 'ejazsab50@gmail.com', '09975648348', '', 8, 'sad', '$2y$10$pYEqa18ibA/FE9DPP2PSQ.ClXpTCcZwsx0dNVoQZTF5bjQ55iWMJ2', '2025-05-17 17:41:38'),
(3, 'ejazsab', 'mhammadadeel11@gmail.com', '0452', 'Male', 3232, 'aSd', '$2y$10$BfDcc8TTwl2HHqc450rTbug6FJ8S1V/FwWezjb9TLU.tp1/wWe0gW', '2025-05-17 17:44:27'),
(4, 'MUHAMMAD', 'admin0@gmail.com', '09975648348', 'Male', 31, 'def', '$2y$10$j5BFn9sMEKFqrtgxi1lij.BVcxsIsMs3Ciw9WTUUaegRsW7Tf8R6W', '2025-05-17 17:57:26'),
(5, 'ejazsab', 'admin1@gmail.com', '09975648348', 'Male', 32, 'dsvdf', 'admin', '2025-05-17 20:34:23'),
(6, 'Muhammad Bin Adeel', 'ejazsab@gmail.com', '03331114816', 'Male', 21, '14/5, Block 2 Nazimabad, Karachi, Karachi City, Sindh 74600', '123admin', '2025-05-24 16:55:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diseases`
--
ALTER TABLE `diseases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medical_news`
--
ALTER TABLE `medical_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `diseases`
--
ALTER TABLE `diseases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `medical_news`
--
ALTER TABLE `medical_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
