-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 06:03 PM
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
  `user_id` int(11) DEFAULT NULL,
  `fraternal_counselor_id` int(11) DEFAULT NULL,
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
  `status` varchar(50) DEFAULT NULL,
  `application_status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `beneficiary_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `benefit_type` varchar(50) DEFAULT NULL,
  `benefit_name1` varchar(100) NOT NULL,
  `benefit_birthdate1` date DEFAULT NULL,
  `benefit_relationship1` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_info`
--

CREATE TABLE `contact_info` (
  `contact_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `street` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `city_province` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `user_id` int(11) NOT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `employment_status` varchar(100) DEFAULT NULL,
  `duties` text DEFAULT NULL,
  `employer` varchar(100) DEFAULT NULL,
  `work` varchar(100) DEFAULT NULL,
  `nature_business` varchar(100) DEFAULT NULL,
  `employer_mobile_number` varchar(15) NOT NULL,
  `employer_email_address` varchar(50) NOT NULL,
  `monthly_income` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `family_background`
--

CREATE TABLE `family_background` (
  `family_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `family_health`
--

CREATE TABLE `family_health` (
  `health_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
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
  `user_id` int(11) NOT NULL,
  `question_code` varchar(20) DEFAULT NULL,
  `response` text DEFAULT NULL,
  `yes_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `medical_id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `past_illness` text NOT NULL,
  `current_medication` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `membership_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `council_id` int(11) DEFAULT NULL,
  `first_degree_date` date DEFAULT NULL,
  `present_degree` varchar(100) DEFAULT NULL,
  `good_standing` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `physician`
--

CREATE TABLE `physician` (
  `physician_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `physician_name` varchar(100) DEFAULT NULL,
  `physician_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `plan_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `fraternal_benefits_id` int(11) DEFAULT NULL,
  `council_id` int(11) NOT NULL,
  `payment_mode` varchar(50) DEFAULT NULL,
  `contribution_amount` decimal(12,2) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD PRIMARY KEY (`applicant_id`),
  ADD KEY `user_id_applicant_FK` (`user_id`),
  ADD KEY `fraternal_counserlor_FKs` (`fraternal_counselor_id`);

--
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`beneficiary_id`),
  ADD KEY `beneficiaries_ibfk_1` (`applicant_id`),
  ADD KEY `bene_user_id_FK` (`user_id`);

--
-- Indexes for table `contact_info`
--
ALTER TABLE `contact_info`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `contact_user_id_FK` (`user_id`);

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
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `user_id_emp_FK` (`user_id`);

--
-- Indexes for table `family_background`
--
ALTER TABLE `family_background`
  ADD PRIMARY KEY (`family_id`),
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `fam_bg_user_id_FK` (`user_id`);

--
-- Indexes for table `family_health`
--
ALTER TABLE `family_health`
  ADD PRIMARY KEY (`health_id`),
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `fam_health_user_id_FK` (`user_id`);

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
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `health_user_id_FK` (`user_id`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD PRIMARY KEY (`medical_id`),
  ADD KEY `med_history_user_id_FK` (`user_id`),
  ADD KEY `med_history_applicant_id_FK` (`applicant_id`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`membership_id`),
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `fk_council_id` (`council_id`),
  ADD KEY `member_user_id_FK` (`user_id`);

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
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `phys_user_id_FK` (`user_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`plan_id`),
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `fk_fraternal_benefits_id` (`fraternal_benefits_id`),
  ADD KEY `plans_user_id_FK` (`user_id`),
  ADD KEY `plans_council_id_FK` (`council_id`);

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
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `medical_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `applicants`
--
ALTER TABLE `applicants`
  ADD CONSTRAINT `fraternal_counserlor_FKs` FOREIGN KEY (`fraternal_counselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id_applicant_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD CONSTRAINT `bene_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `beneficiaries_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contact_info`
--
ALTER TABLE `contact_info`
  ADD CONSTRAINT `contact_info_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`),
  ADD CONSTRAINT `contact_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `employment_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`),
  ADD CONSTRAINT `user_id_emp_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `family_background`
--
ALTER TABLE `family_background`
  ADD CONSTRAINT `fam_bg_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `family_background_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `family_health`
--
ALTER TABLE `family_health`
  ADD CONSTRAINT `fam_health_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `family_health_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `health_questions`
--
ALTER TABLE `health_questions`
  ADD CONSTRAINT `health_questions_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`),
  ADD CONSTRAINT `health_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD CONSTRAINT `med_history_applicant_id_FK` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `med_history_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `membership`
--
ALTER TABLE `membership`
  ADD CONSTRAINT `fk_council_id` FOREIGN KEY (`council_id`) REFERENCES `council` (`council_id`),
  ADD CONSTRAINT `member_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
  ADD CONSTRAINT `phys_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `physician_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `plans`
--
ALTER TABLE `plans`
  ADD CONSTRAINT `fk_fraternal_benefits_id` FOREIGN KEY (`fraternal_benefits_id`) REFERENCES `fraternal_benefits` (`id`),
  ADD CONSTRAINT `plans_council_id_FK` FOREIGN KEY (`council_id`) REFERENCES `council` (`council_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `plans_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`),
  ADD CONSTRAINT `plans_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
