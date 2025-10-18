-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2025 at 08:51 PM
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
-- Database: `columbos`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `action_details` text DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`log_id`, `admin_id`, `action_type`, `entity_type`, `entity_id`, `action_details`, `old_value`, `new_value`, `timestamp`) VALUES
(2, 9, 'USER_APPROVED', 'users', 15, 'Approved user ID 15', 'disabled', 'approved', '2025-10-18 01:50:47'),
(3, 9, 'USER_DISABLED', 'users', 11, 'Disabled user ID 11', 'approved', 'disabled', '2025-10-18 02:00:41'),
(4, 9, 'USER_APPROVED', 'users', 11, 'Approved user ID 11', 'disabled', 'approved', '2025-10-18 02:10:11'),
(5, 9, 'USER_DISABLED', 'users', 14, 'Disabled user ID 14', 'approved', 'disabled', '2025-10-18 02:30:37'),
(6, 9, 'USER_DISABLED', 'users', 15, 'Disabled user ID 15', 'approved', 'disabled', '2025-10-18 02:30:48'),
(7, 9, 'USER_DISABLED', 'users', 11, 'Disabled user ID 11', 'approved', 'disabled', '2025-10-18 02:31:00'),
(8, 9, 'ANNOUNCEMENT_POST', 'announcements', NULL, 'Posted new announcement: \'Monthly Meeting...\'', NULL, 'Subject: Monthly Meeting', '2025-10-18 02:31:41'),
(9, 9, 'ANNOUNCEMENT_DELETE', 'announcements', 18, 'Deleted announcement: \'RHU-ANNOUNCEMENT...\'', '{\"subject\":\"RHU-ANNOUNCEMENT\",\"content\":\"KARUN ANG SCHED SA EMUNG PRENATAL\"}', NULL, '2025-10-18 02:39:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
