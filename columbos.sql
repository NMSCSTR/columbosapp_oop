-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2025 at 07:51 AM
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
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date_posted` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `subject`, `content`, `date_posted`) VALUES
(3, 'Meeting Officers Part 2', 'Mag meeting ta nenyo mga gwapo ug gwapa', '2025-05-02 20:50:10'),
(4, 'Meeting Officers Part 3', 'This is an announcement to be use when sending all to phone.', '2025-05-02 21:02:39'),
(5, 'Boardinghouse Meeting', 'Please be reminded that we will have our meeting tonight', '2025-05-02 21:05:52'),
(6, 'Meeting Ta', 'Please be reminded that we will have our meeting tonight.', '2025-05-03 08:09:10');

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `applicant_id` int(11) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `birthplace` varchar(255) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `marital_status` varchar(50) DEFAULT NULL,
  `tin_sss` varchar(50) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `fraternal_counselor_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `application_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`applicant_id`, `lastname`, `firstname`, `middlename`, `age`, `birthdate`, `birthplace`, `gender`, `marital_status`, `tin_sss`, `nationality`, `fraternal_counselor_id`, `created_at`, `status`, `application_status`) VALUES
(1, 'Dela Cruz', 'Juan', 'Santos', 0, '1991-04-14', 'Manila', 'Male', 'Single', '123-456-789', 'Filipino', 1, '2025-05-03 09:08:36', 'active', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `beneficiary_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `benefit_type` varchar(50) DEFAULT NULL,
  `benefit_name` varchar(100) DEFAULT NULL,
  `benefit_dob` date DEFAULT NULL,
  `relationship` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beneficiaries`
--

INSERT INTO `beneficiaries` (`beneficiary_id`, `applicant_id`, `benefit_type`, `benefit_name`, `benefit_dob`, `relationship`) VALUES
(1, 1, 'Primary', 'Maria Dela Cruz', '2009-12-08', 'Daughter'),
(2, 1, 'Secondary', 'Pedro Dela Cruz', '1996-02-26', 'Son');

-- --------------------------------------------------------

--
-- Table structure for table `contact_info`
--

CREATE TABLE `contact_info` (
  `contact_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `city_prov` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_info`
--

INSERT INTO `contact_info` (`contact_id`, `applicant_id`, `street`, `barangay`, `city_prov`, `mobile`, `email`) VALUES
(1, 1, '123 Sampaguita St.', 'Barangay 1', 'Quezon City', '09171234567', 'juan.delacruz@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `council`
--

CREATE TABLE `council` (
  `council_id` int(11) NOT NULL,
  `council_number` varchar(11) NOT NULL,
  `council_name` varchar(255) NOT NULL,
  `unit_manager_id` int(11) NOT NULL,
  `fraternal_counselor_id` int(11) NOT NULL,
  `date_established` datetime(6) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `council`
--

INSERT INTO `council` (`council_id`, `council_number`, `council_name`, `unit_manager_id`, `fraternal_counselor_id`, `date_established`, `date_created`) VALUES
(6, '122', 'St.Vincents', 10, 11, '2025-04-17 00:00:00.000000', '2025-04-29 10:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `employment`
--

CREATE TABLE `employment` (
  `employment_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `employment_status` varchar(100) DEFAULT NULL,
  `duties` text DEFAULT NULL,
  `employer` varchar(100) DEFAULT NULL,
  `work` varchar(100) DEFAULT NULL,
  `nature_business` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employment`
--

INSERT INTO `employment` (`employment_id`, `applicant_id`, `occupation`, `employment_status`, `duties`, `employer`, `work`, `nature_business`) VALUES
(1, 1, 'Software Developer', 'Employed', 'Develops software applications.', 'TechCorp Inc.', 'IT Department', 'Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `family_background`
--

CREATE TABLE `family_background` (
  `family_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `father_lastname` varchar(100) DEFAULT NULL,
  `father_firstname` varchar(100) DEFAULT NULL,
  `father_mi` varchar(10) DEFAULT NULL,
  `mother_lastname` varchar(100) DEFAULT NULL,
  `mother_firstname` varchar(100) DEFAULT NULL,
  `mother_mi` varchar(10) DEFAULT NULL,
  `siblings_living` int(11) DEFAULT NULL,
  `siblings_deceased` int(11) DEFAULT NULL,
  `children_living` int(11) DEFAULT NULL,
  `children_deceased` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `family_background`
--

INSERT INTO `family_background` (`family_id`, `applicant_id`, `father_lastname`, `father_firstname`, `father_mi`, `mother_lastname`, `mother_firstname`, `mother_mi`, `siblings_living`, `siblings_deceased`, `children_living`, `children_deceased`) VALUES
(1, 1, 'Dela Cruz', 'Jose', 'S', 'Reyes', 'Maria', 'G', 2, 1, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `family_health`
--

CREATE TABLE `family_health` (
  `health_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `father_living_age` int(11) DEFAULT NULL,
  `father_health` text DEFAULT NULL,
  `mother_living_age` int(11) DEFAULT NULL,
  `mother_health` text DEFAULT NULL,
  `siblings_living_age` text DEFAULT NULL,
  `siblings_health` text DEFAULT NULL,
  `children_living_age` text DEFAULT NULL,
  `children_health` text DEFAULT NULL,
  `father_death_age` int(11) DEFAULT NULL,
  `father_cause` text DEFAULT NULL,
  `mother_death_age` int(11) DEFAULT NULL,
  `mother_cause` text DEFAULT NULL,
  `siblings_death_age` text DEFAULT NULL,
  `siblings_cause` text DEFAULT NULL,
  `children_death_age` text DEFAULT NULL,
  `children_cause` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `family_health`
--

INSERT INTO `family_health` (`health_id`, `applicant_id`, `father_living_age`, `father_health`, `mother_living_age`, `mother_health`, `siblings_living_age`, `siblings_health`, `children_living_age`, `children_health`, `father_death_age`, `father_cause`, `mother_death_age`, `mother_cause`, `siblings_death_age`, `siblings_cause`, `children_death_age`, `children_cause`) VALUES
(1, 1, 70, 'Healthy', 68, 'Diabetic', '45,40', 'Healthy,Asthma', '12,10', 'Healthy,Healthy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fraternal_benefits`
--

CREATE TABLE `fraternal_benefits` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `about` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `contribution_period` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fraternal_benefits`
--

INSERT INTO `fraternal_benefits` (`id`, `type`, `name`, `about`, `benefits`, `contribution_period`, `image`, `created_at`, `updated_at`) VALUES
(5, 'Retirement Plan', 'Retirement Plan Name', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic ipsa dolores, nobis expedita laboriosam ab voluptatum blanditiis possimus facere voluptas?', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo, aut quisquam doloribus facere consectetur optio? Blanditiis, vitae nobis, distinctio rerum et in veritatis perferendis provident facere animi ab id? A!', 'Contribution of paying period is 10 years.', 'uploads/fraternalBenefitsUpload/RetirementPlanImage_1746123658.jpg', '2025-05-01 18:20:58', '2025-05-01 18:20:58');

-- --------------------------------------------------------

--
-- Table structure for table `health_questions`
--

CREATE TABLE `health_questions` (
  `question_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `question_code` varchar(20) DEFAULT NULL,
  `response` text DEFAULT NULL,
  `yes_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `health_questions`
--

INSERT INTO `health_questions` (`question_id`, `applicant_id`, `question_code`, `response`, `yes_details`) VALUES
(1, 1, 'p3_q3', 'No', NULL),
(2, 1, 'p3_q4', 'Yes', 'Had chickenpox at age 10'),
(3, 1, 'p4_q10', 'No', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `membership_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `first_degree_date` date DEFAULT NULL,
  `present_degree` varchar(100) DEFAULT NULL,
  `good_standing` varchar(10) DEFAULT NULL,
  `council_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership`
--

INSERT INTO `membership` (`membership_id`, `applicant_id`, `first_degree_date`, `present_degree`, `good_standing`, `council_id`) VALUES
(1, 1, '2017-11-08', '3rd Degree', 'Yes', 6);

-- --------------------------------------------------------

--
-- Table structure for table `personal_details`
--

CREATE TABLE `personal_details` (
  `details_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `signature_file` varchar(255) DEFAULT NULL,
  `pregnant_question` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_details`
--

INSERT INTO `personal_details` (`details_id`, `applicant_id`, `height`, `weight`, `signature_file`, `pregnant_question`) VALUES
(1, 1, 170.50, 65.00, 'uploads/signatures/juan_signature.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `physician`
--

CREATE TABLE `physician` (
  `physician_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `physician_name` varchar(100) DEFAULT NULL,
  `physician_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `physician`
--

INSERT INTO `physician` (`physician_id`, `applicant_id`, `physician_name`, `physician_address`) VALUES
(1, 1, 'Dr. Ana Santos', '789 Medical Ave., Makati City');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `plan_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `plan_code` varchar(50) DEFAULT NULL,
  `face_value` decimal(12,2) DEFAULT NULL,
  `yrs_contribute` int(11) DEFAULT NULL,
  `yrs_protect` int(11) DEFAULT NULL,
  `yrs_mature` int(11) DEFAULT NULL,
  `payment_mode` varchar(50) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `contribution_amount` decimal(12,2) DEFAULT NULL,
  `fraternal_benefits_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`plan_id`, `applicant_id`, `plan_code`, `face_value`, `yrs_contribute`, `yrs_protect`, `yrs_mature`, `payment_mode`, `currency`, `contribution_amount`, `fraternal_benefits_id`) VALUES
(1, 1, 'BP100', 100000.00, 5, 10, 15, 'Monthly', 'PHP', 1000.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `kcfapicode` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `role` enum('admin','unit-manager','fraternal-counselor','family-member','member') DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `kcfapicode`, `email`, `phone_number`, `role`, `password`, `status`, `created_at`) VALUES
(9, 'Rodrigo', 'Duterte', 'ROACODE', 'rodrigoroa@gmail.com', '09105200970', 'admin', '$2y$10$JDN36eEv6KaqtL8EPjKnAOAymSmK6f8TSF2ZghJ4S22sFGLKmkZyW', 'approved', '2025-04-29 02:37:07'),
(10, 'Bong', 'Marcos', 'MARCOSCODE', 'bongmarcos@gmail.com', '09363816243', 'unit-manager', '$2y$10$Zao9HR5toa9Lo/X2sHbvVeo5mhuBRpopN4jIPnsbeqCdBizZkBqTu', 'disabled', '2025-04-29 03:12:25'),
(11, 'Sarah', 'Duterte', 'SARAHCODE', 'sarahduterte@gmail.com', '09683013329', 'fraternal-counselor', '$2y$10$Mz8F/Ya25UUr5dstBEZ2N.VJuxuLq0hi5IeK3y1CwC8LMvoX2ERIm', 'approved', '2025-04-29 03:17:08'),
(12, 'Kitty', 'Duterte', 'KITTYCODE', 'kittyduterte@gmail.com', '09105200973', 'family-member', '$2y$10$kM2uW5S8ehCbpfI/7sqWS.oLhsYZNuYB69YBXPBCkTI9PbPgnAycC', 'disabled', '2025-04-29 03:18:54'),
(13, 'Sandro', 'Marcos', 'SANDROCODE', 'sandromarcos@gmail.com', '09105200974', 'member', '$2y$10$9t/uW.w9GTrU7HsdBQjck.W3VQcNAkz71N6eZ7yPCm3aSNcdKnJCm', 'disabled', '2025-04-29 03:19:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`applicant_id`);

--
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`beneficiary_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `contact_info`
--
ALTER TABLE `contact_info`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `council`
--
ALTER TABLE `council`
  ADD PRIMARY KEY (`council_id`),
  ADD KEY `umid` (`unit_manager_id`),
  ADD KEY `fcid` (`fraternal_counselor_id`);

--
-- Indexes for table `employment`
--
ALTER TABLE `employment`
  ADD PRIMARY KEY (`employment_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `family_background`
--
ALTER TABLE `family_background`
  ADD PRIMARY KEY (`family_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `family_health`
--
ALTER TABLE `family_health`
  ADD PRIMARY KEY (`health_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `fraternal_benefits`
--
ALTER TABLE `fraternal_benefits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `health_questions`
--
ALTER TABLE `health_questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`membership_id`),
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `fk_council_id` (`council_id`);

--
-- Indexes for table `personal_details`
--
ALTER TABLE `personal_details`
  ADD PRIMARY KEY (`details_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `physician`
--
ALTER TABLE `physician`
  ADD PRIMARY KEY (`physician_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`plan_id`),
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `fk_fraternal_benefits_id` (`fraternal_benefits_id`);

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
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `applicant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `beneficiary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `council`
--
ALTER TABLE `council`
  MODIFY `council_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employment`
--
ALTER TABLE `employment`
  MODIFY `employment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `family_background`
--
ALTER TABLE `family_background`
  MODIFY `family_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `family_health`
--
ALTER TABLE `family_health`
  MODIFY `health_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fraternal_benefits`
--
ALTER TABLE `fraternal_benefits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `health_questions`
--
ALTER TABLE `health_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `membership`
--
ALTER TABLE `membership`
  MODIFY `membership_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_details`
--
ALTER TABLE `personal_details`
  MODIFY `details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `physician`
--
ALTER TABLE `physician`
  MODIFY `physician_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD CONSTRAINT `beneficiaries_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `contact_info`
--
ALTER TABLE `contact_info`
  ADD CONSTRAINT `contact_info_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `council`
--
ALTER TABLE `council`
  ADD CONSTRAINT `fcid` FOREIGN KEY (`fraternal_counselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `umid` FOREIGN KEY (`unit_manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employment`
--
ALTER TABLE `employment`
  ADD CONSTRAINT `employment_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `family_background`
--
ALTER TABLE `family_background`
  ADD CONSTRAINT `family_background_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `family_health`
--
ALTER TABLE `family_health`
  ADD CONSTRAINT `family_health_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `health_questions`
--
ALTER TABLE `health_questions`
  ADD CONSTRAINT `health_questions_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `membership`
--
ALTER TABLE `membership`
  ADD CONSTRAINT `fk_council_id` FOREIGN KEY (`council_id`) REFERENCES `council` (`council_id`),
  ADD CONSTRAINT `membership_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `personal_details`
--
ALTER TABLE `personal_details`
  ADD CONSTRAINT `personal_details_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `physician`
--
ALTER TABLE `physician`
  ADD CONSTRAINT `physician_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `plans`
--
ALTER TABLE `plans`
  ADD CONSTRAINT `fk_fraternal_benefits_id` FOREIGN KEY (`fraternal_benefits_id`) REFERENCES `fraternal_benefits` (`id`),
  ADD CONSTRAINT `plans_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
