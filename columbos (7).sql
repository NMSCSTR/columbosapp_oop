-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2026 at 08:13 AM
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
(9, 9, 'ANNOUNCEMENT_DELETE', 'announcements', 18, 'Deleted announcement: \'RHU-ANNOUNCEMENT...\'', '{\"subject\":\"RHU-ANNOUNCEMENT\",\"content\":\"KARUN ANG SCHED SA EMUNG PRENATAL\"}', NULL, '2025-10-18 02:39:11'),
(10, 9, 'APPLICATION_STATUS_CHANGE', 'applicants', 17, 'Rejected application for: Sandras Marcoses', 'Approved', 'Rejected', '2025-10-18 22:37:44'),
(11, 9, 'USER_APPROVED', 'users', 14, 'Approved user ID 14', 'disabled', 'approved', '2025-10-18 23:27:57'),
(12, 9, 'USER_APPROVED', 'users', 15, 'Approved user ID 15', 'disabled', 'approved', '2025-10-18 23:28:08'),
(13, 9, 'USER_APPROVED', 'users', 11, 'Approved user ID 11', 'disabled', 'approved', '2025-10-18 23:28:19'),
(14, 9, 'COUNCIL_ADD', 'council', NULL, 'Added new Council #2311112: \'Log Council\'', NULL, 'Council No: 2311112, Name: Log Council', '2025-10-18 23:30:21'),
(15, 9, 'COUNCIL_DELETE', 'council', 8, 'Deleted Council #2311112: \'Log Council\'', '{\"council_number\":\"2311112\",\"council_name\":\"Log Council\"}', NULL, '2025-10-18 23:37:48'),
(16, 9, 'COUNCIL_UPDATE', 'council', 6, 'Updated Council #2026: \'St.Vincents\'', '{\"council_number\":\"2025\",\"council_name\":\"St.Vincent\",\"unit_manager_id\":\"10\",\"fraternal_counselor_id\":\"11\",\"date_established\":\"2025-04-17 00:00:00.000000\"}', '{\"council_number\":\"2026\",\"council_name\":\"St.Vincents\",\"unit_manager_id\":\"10\",\"fraternal_counselor_id\":\"11\",\"date_established\":\"2025-04-17\"}', '2025-10-18 23:57:04'),
(17, 9, 'USER_DISABLED', 'users', 15, 'Disabled user ID 15', 'approved', 'disabled', '2025-10-19 05:31:27'),
(18, 9, 'APPLICATION_STATUS_CHANGE', 'applicants', 17, 'Approved application for: Sandras Marcoses', 'Rejected', 'Approved', '2025-10-19 05:32:42'),
(19, 9, 'USER_APPROVED', 'users', 15, 'Approved user ID 15', 'disabled', 'approved', '2025-10-21 21:57:43'),
(20, 9, 'USER_REJECTED', 'users', 15, 'Rejected user ID 15', 'approved', 'reject', '2025-10-21 21:57:52'),
(21, 9, 'USER_APPROVED', 'users', 15, 'Approved user ID 15', 'pending', 'approved', '2025-10-26 23:03:13'),
(22, 9, 'USER_REJECTED', 'users', 15, 'Rejected user ID 15', 'pending', 'rejected', '2025-10-26 23:08:05'),
(23, 9, 'APPLICATION_STATUS_CHANGE', 'applicants', 17, 'Approved application for: Sandras Marcoses', 'Pending', 'Approved', '2025-11-04 10:39:22'),
(24, 9, 'ANNOUNCEMENT_POST', 'announcements', 20, 'Posted new announcement: \'ANNOUNCEMENT REVISED...\'', NULL, 'Subject: ANNOUNCEMENT REVISED', '2026-01-14 23:40:26'),
(25, 9, 'ANNOUNCEMENT_DELETE', 'announcements', 19, 'Deleted announcement: \'Monthly Meeting...\'', '{\"id\":\"19\",\"subject\":\"Monthly Meeting\",\"content\":\"Sample meeting message for monthly gathering.\",\"date_posted\":\"2025-10-18 02:31:40\"}', NULL, '2026-01-14 23:51:21'),
(26, 9, 'APPLICATION_STATUS_CHANGE', 'applicants', 18, 'Approved application for: Sandro Marcos', 'Pending', 'Approved', '2026-01-18 01:23:32'),
(27, 9, 'ANNOUNCEMENT_POST', 'announcements', 21, 'Posted new announcement: \'asndnj...\'', NULL, 'Subject: asndnj', '2026-01-18 23:01:40'),
(28, 9, 'ANNOUNCEMENT_POST', 'announcements', 22, 'Posted new announcement: \'asndnj...\'', NULL, 'Subject: asndnj', '2026-01-18 23:01:50'),
(29, 9, 'ANNOUNCEMENT_DELETE', 'announcements', 22, 'Deleted announcement: \'asndnj...\'', '{\"id\":\"22\",\"subject\":\"asndnj\",\"content\":\"jkjsnafjnsadbnabHDBSABHDBASBDHABSDHJAB\",\"date_posted\":\"2026-01-18 23:01:49\"}', NULL, '2026-01-18 23:02:18'),
(30, 13, 'APPLICATION_STATUS_CHANGE', 'applicants', 18, 'Approved application for: Sandro Marcos', 'Pending', 'Approved', '2026-01-19 00:48:43'),
(31, 13, 'USER_APPROVED', 'users', 18, 'Approved user ID 18', 'pending', 'approved', '2026-01-19 06:12:01'),
(32, 13, 'APPLICATION_STATUS_CHANGE', 'applicants', 19, 'Approved application for: Piolo Pascual', 'Pending', 'Approved', '2026-01-19 10:18:14');

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
(6, 'Meeting Ta', 'Please be reminded that we will have our meeting tonight.', '2025-05-03 08:09:10'),
(7, 'Urgent Meeting', 'Please be informed that we will have our meeting this evening.', '2025-05-26 14:48:00'),
(8, 'Meeting Test', 'Naa tay meeting unya mga unit manager ug fraternal counselor alas 8 sa gabie.', '2025-05-29 00:09:55'),
(9, 'Importanti kaayo', 'Please be informed that we will have our meeting this evening.\r\n\r\n Naa tay meeting unya mga unit manager ug fraternal counselor alas 8 sa gabie.', '2025-05-29 00:15:09'),
(11, 'Check SMS', 'Check SMS Notification if being receive.', '2025-06-19 16:14:28'),
(12, 'Second attempt SMS', 'Checking SMS integration on announcements', '2025-06-19 16:18:19'),
(13, 'Third Attempt SMS', 'Another attempt on Checking SMS integration on announcements', '2025-06-19 16:23:48'),
(15, 'Fourth Attempt SMS', 'Another fourth attempt on Checking SMS integration on announcements', '2025-06-19 16:25:13'),
(16, 'Meeting Officers Part97', 'Naa tay meeting unya mga unit manager ug fraternal counselor alas 8 sa gabie.', '2025-06-19 16:25:59'),
(17, 'Meeting Officers Part98', 'Please be informed that we will have our meeting this evening.', '2025-06-19 16:28:09'),
(20, 'ANNOUNCEMENT REVISED', 'THIS IS JUST A EXAMPLE MESSAGE FOR REVISED', '2026-01-14 23:40:25'),
(21, 'asndnj', 'jkjsnafjnsadbnabHDBSABHDBASBDHABSDHJAB', '2026-01-18 23:01:39');

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
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`applicant_id`, `user_id`, `fraternal_counselor_id`, `lastname`, `firstname`, `middlename`, `age`, `birthdate`, `birthplace`, `gender`, `marital_status`, `tin_sss`, `nationality`, `status`, `application_status`, `created_at`) VALUES
(17, 13, 11, 'Marcoses', 'Sandras', 'Bangag', 2002, '1999-02-11', '28', 'Female', 'Single', 'Sapiente dolorum per', 'Id praesentium anim', 'Active', 'Approved', '2025-05-08 11:35:23'),
(18, 13, 15, 'Marcos', 'Sandro', 'Steven Watson', 51, '1975-05-16', 'Excepturi voluptatem', 'Male', 'Single', 'Fugit totam sit al', 'Mollit pariatur Ita', 'Active', 'Approved', '2026-01-18 01:18:33'),
(19, 18, 11, 'Pascual', 'Piolo', 'Gwapo', 26, '1999-07-16', 'Maquilap', 'Male', 'Married', '345345435', 'Uy pilipins pilipinsss', 'Active', 'Approved', '2026-01-19 10:17:23');

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `beneficiary_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `benefit_type` varchar(50) DEFAULT NULL,
  `benefit_name` varchar(100) NOT NULL,
  `benefit_birthdate` date DEFAULT NULL,
  `benefit_relationship` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beneficiaries`
--

INSERT INTO `beneficiaries` (`beneficiary_id`, `applicant_id`, `user_id`, `benefit_type`, `benefit_name`, `benefit_birthdate`, `benefit_relationship`) VALUES
(14, 17, 13, 'Revocable', 'Hadley Cunningham', '1998-10-30', 'Est tempor excepteur'),
(15, 18, 13, 'Irrevocable', 'John Cote', '1978-01-04', 'Unde magnam labore e'),
(16, 19, 18, 'Revocable', 'Plato Stephens', '1980-09-28', 'Debitis sit laboris ');

-- --------------------------------------------------------

--
-- Table structure for table `benefit_rates`
--

CREATE TABLE `benefit_rates` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `min_age` int(3) NOT NULL,
  `max_age` int(3) NOT NULL,
  `rate` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `benefit_rates`
--

INSERT INTO `benefit_rates` (`id`, `category_id`, `min_age`, `max_age`, `rate`) VALUES
(1, 3, 1, 30, 4.57),
(2, 4, 1, 30, 3.65),
(3, 5, 1, 30, 0.66),
(4, 3, 31, 31, 4.67),
(5, 3, 31, 31, 4.67),
(6, 3, 31, 31, 3.75),
(7, 4, 31, 31, 3.75),
(8, 5, 31, 31, 0.66),
(9, 3, 32, 32, 4.79),
(10, 6, 1, 30, 4.57),
(11, 7, 1, 30, 3.65),
(12, 8, 1, 30, 0.66),
(13, 6, 31, 31, 4.67),
(14, 7, 31, 31, 3.75),
(15, 8, 31, 31, 0.66);

-- --------------------------------------------------------

--
-- Table structure for table `benefit_rate_categories`
--

CREATE TABLE `benefit_rate_categories` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `min_face_value` decimal(15,2) DEFAULT 0.00,
  `max_face_value` decimal(15,2) DEFAULT NULL,
  `is_adb` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `benefit_rate_categories`
--

INSERT INTO `benefit_rate_categories` (`id`, `plan_id`, `name`, `min_face_value`, `max_face_value`, `is_adb`) VALUES
(3, 6, '50k-<100', 50000.00, 100000.00, 0),
(4, 6, '100k & up', 100000.00, NULL, 0),
(5, 6, 'ADB', 0.00, NULL, 1),
(6, 9, '50k-<100', 50000.00, 99999.00, 0),
(7, 9, '100k & up', 100000.00, NULL, 0),
(8, 9, 'ADB', 0.00, NULL, 1);

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

--
-- Dumping data for table `contact_info`
--

INSERT INTO `contact_info` (`contact_id`, `applicant_id`, `user_id`, `street`, `barangay`, `city_province`, `mobile_number`, `email_address`) VALUES
(17, 17, 13, 'In occaecat itaque e', 'Elit accusantium id', 'Voluptatem amet at', '09105200970', 'jrfernandez.com'),
(18, 18, 13, 'Maiores laboris iust', 'Ut ut voluptas minim', 'Aspernatur eum dolor', '977', 'jowu@mailinator.com'),
(19, 19, 18, 'Blanditiis commodo v', 'Corporis sit aperia', 'Ad eveniet error si', '09105200970', 'piolo@gmail.com');

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
(6, '2026', 'St.Vincents', 10, 11, '2025-04-17 00:00:00.000000', '2025-04-29 10:54:06'),
(7, 'Z678', 'St. Michael', 14, 15, '2025-03-01 00:00:00.000000', '2025-06-03 13:53:03');

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

--
-- Dumping data for table `employment`
--

INSERT INTO `employment` (`employment_id`, `applicant_id`, `user_id`, `occupation`, `employment_status`, `duties`, `employer`, `work`, `nature_business`, `employer_mobile_number`, `employer_email_address`, `monthly_income`) VALUES
(15, 17, 13, 'In corporis consequa', 'employed', 'Aut possimus commod', 'A velit vero magnam', 'Voluptates sunt sed', 'Ea consequat Consec', '595', 'ziqu@mailinator.com', 711),
(16, 18, 13, 'Aut dolorum et cupid', 'self_employed', 'Fugiat ut et mollit', 'Exercitationem id su', 'Eveniet nostrud aut', 'Quia et quos placeat', '837', 'quhag@mailinator.com', 992),
(17, 19, 18, 'Quis id eum ratione', 'Employment Status', 'Quaerat ullamco qui', 'Soluta ea enim fugia', 'Ut iusto qui fugit', 'Enim et rerum dolor', '298', 'wusofeqy@mailinator.com', 20000);

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

--
-- Dumping data for table `family_background`
--

INSERT INTO `family_background` (`family_id`, `applicant_id`, `user_id`, `father_lastname`, `father_firstname`, `father_mi`, `mother_lastname`, `mother_firstname`, `mother_mi`, `siblings_living`, `siblings_deceased`, `children_living`, `children_deceased`) VALUES
(11, 17, 13, 'Maynards', 'Herrod', 'Ipsam blan', 'Coffey', 'Pearl', 'Rerum esse', 44, 88, 82, 28),
(12, 18, 13, 'Golden', 'Timon', 'Asperiores', 'Booth', 'Stewart', 'Qui volupt', 84, 74, 82, 85),
(13, 19, 18, 'Dickerson', 'Carol', 'Est conseq', 'Joyner', 'Jaquelyn', 'Assumenda ', 22, 97, 85, 30);

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

--
-- Dumping data for table `family_health`
--

INSERT INTO `family_health` (`health_id`, `applicant_id`, `user_id`, `father_living_age`, `father_health`, `mother_living_age`, `mother_health`, `siblings_living_age`, `siblings_health`, `children_living_age`, `children_health`, `father_death_age`, `father_cause`, `mother_death_age`, `mother_cause`, `siblings_death_age`, `siblings_cause`, `children_death_age`, `children_cause`) VALUES
(11, 17, 13, 15, 'Quidem in cumque ill', 65, 'Culpa eius consectet', '39', 'Soluta ea qui elit', '45', 'Sint qui voluptas ut', 31, 'Incidunt minima ess', 29, 'Accusamus ex quasi s', '63', 'Sint consequat Nih', '52', 'Inventore est et co'),
(12, 18, 13, 81, 'Quia aut enim sed no', 56, 'Et obcaecati in omni', '68', 'Molestiae do qui vol', '15', 'Qui molestiae ullamc', 6, 'Quae suscipit tempor', 61, 'Quia cumque dicta ip', '97', 'Voluptas reprehender', '66', 'Impedit proident q'),
(13, 19, 18, 22, 'Ad esse impedit fu', 50, 'Excepteur dignissimo', '', '', '', '', 0, '', 0, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `uploaded_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `uploaded_by` int(11) DEFAULT NULL,
  `file_located` varchar(255) DEFAULT NULL,
  `file_type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `filename`, `description`, `uploaded_on`, `uploaded_by`, `file_located`, `file_type`) VALUES
(6, 'Questionaire 1', 'This is a sample description for this file', '2025-05-22 19:14:02', 9, 'http://localhost/app/uploads/forms/questionnaire.docx', 'Questionnaire');

-- --------------------------------------------------------

--
-- Table structure for table `fraternal_benefits`
--

CREATE TABLE `fraternal_benefits` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `about` text DEFAULT NULL,
  `face_value` int(50) NOT NULL,
  `years_to_maturity` int(50) NOT NULL,
  `years_of_protection` int(50) NOT NULL,
  `benefits` text DEFAULT NULL,
  `contribution_period` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fraternal_benefits`
--

INSERT INTO `fraternal_benefits` (`id`, `type`, `name`, `about`, `face_value`, `years_to_maturity`, `years_of_protection`, `benefits`, `contribution_period`, `image`, `created_at`, `updated_at`) VALUES
(5, 'Retirement Plan', 'Retirement Plan Name', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic ipsa dolores, nobis expedita laboriosam ab voluptatum blanditiis possimus facere voluptas?', 100000, 15, 10, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo, aut quisquam doloribus facere consectetur optio? Blanditiis, vitae nobis, distinctio rerum et in veritatis perferendis provident facere animi ab id? A!', '5', 'uploads/fraternalBenefitsUpload/RetirementPlanImage_1746123658.jpg', '2025-05-01 18:20:58', '2025-05-13 02:39:24'),
(6, 'Protection Plan', 'GIOS Defender', 'Permanent life insurance plan that provides family benefit protection and guaranteed living benefits during the  lifetime of the Assured. This fraternal benefit plan can be offered to applicants whose issues ages ate within the age group 18-49', 500000, 20, 10, 'Upon reaching the maturity date at age 100, the plan will provide a cash maturity equal to 100% of the face value. The family benefits is equal to 100% of the face value given to the beneficiaries in case of the BC Holder\'s untimely demise during the protection period.', '15', 'uploads/fraternalBenefitsUpload/ProtectionPlanImage_1747618064.jpg', '2025-05-19 01:27:44', '2025-05-31 09:17:27'),
(9, 'Protection Plan', 'KC Term Protect 5 (Plan 134)', 'KC Term Protect 5 (Plan 134) DESCIPTION', 300000, 20, 10, 'KC Term Protect 5 (Plan 134) BENEFITS', '5', 'uploads/fraternalBenefitsUpload/Image_1768774950.jpg', '2026-01-18 22:22:30', '2026-01-18 22:22:55');

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

--
-- Dumping data for table `health_questions`
--

INSERT INTO `health_questions` (`question_id`, `applicant_id`, `user_id`, `question_code`, `response`, `yes_details`) VALUES
(82, 17, 13, 'q1', 'No', ''),
(83, 17, 13, 'q2', 'No', ''),
(84, 17, 13, 'q3', 'No', ''),
(85, 17, 13, 'q4', 'No', ''),
(86, 17, 13, 'q5', 'No', ''),
(87, 17, 13, 'q6', 'No', ''),
(88, 17, 13, 'q7', 'No', ''),
(89, 17, 13, 'q8', 'No', ''),
(90, 17, 13, 'q9', 'No', ''),
(91, 17, 13, 'q10a', 'No', ''),
(92, 17, 13, 'q10b', 'No', ''),
(93, 17, 13, 'q11', 'No', ''),
(94, 17, 13, 'q12', 'No', ''),
(95, 18, 13, 'q1', 'No', ''),
(96, 18, 13, 'q2', 'No', ''),
(97, 18, 13, 'q3', 'No', ''),
(98, 18, 13, 'q4', 'No', ''),
(99, 18, 13, 'q5', 'No', ''),
(100, 18, 13, 'q6', 'No', ''),
(101, 18, 13, 'q7', 'No', ''),
(102, 18, 13, 'q8', 'No', ''),
(103, 18, 13, 'q9', 'No', ''),
(104, 18, 13, 'q10a', 'No', ''),
(105, 18, 13, 'q10b', 'No', ''),
(106, 18, 13, 'q11', 'No', ''),
(107, 18, 13, 'q12', 'No', ''),
(108, 19, 18, 'q1', 'No', ''),
(109, 19, 18, 'q2', 'No', ''),
(110, 19, 18, 'q3', 'No', ''),
(111, 19, 18, 'q4', 'No', ''),
(112, 19, 18, 'q5', 'No', ''),
(113, 19, 18, 'q6', 'No', ''),
(114, 19, 18, 'q7', 'No', ''),
(115, 19, 18, 'q8', 'No', ''),
(116, 19, 18, 'q9', 'No', ''),
(117, 19, 18, 'q10a', 'No', ''),
(118, 19, 18, 'q10b', 'No', ''),
(119, 19, 18, 'q11', 'No', ''),
(120, 19, 18, 'q12', 'No', '');

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

--
-- Dumping data for table `medical_history`
--

INSERT INTO `medical_history` (`medical_id`, `applicant_id`, `user_id`, `past_illness`, `current_medication`) VALUES
(10, 17, 13, 'Magna officia quo as', 'Voluptate sint pari'),
(11, 18, 13, 'Voluptas eum culpa', 'Quia itaque voluptat'),
(12, 19, 18, 'Ullamco enim quibusd', 'Magnam aspernatur qu');

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

--
-- Dumping data for table `membership`
--

INSERT INTO `membership` (`membership_id`, `applicant_id`, `user_id`, `council_id`, `first_degree_date`, `present_degree`, `good_standing`) VALUES
(5, 17, 13, 6, '2010-01-11', 'Aut in qui sint qui', 'Yes'),
(6, 18, 13, 7, '1972-05-19', 'Iusto mollit animi ', 'Yes'),
(7, 19, 18, 6, '0000-00-00', '', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `membership_roster`
--

CREATE TABLE `membership_roster` (
  `member_number` varchar(10) NOT NULL,
  `council_id` int(11) NOT NULL,
  `member_name` varchar(100) DEFAULT NULL,
  `street_address` varchar(100) DEFAULT NULL,
  `city_state_zip` varchar(100) DEFAULT NULL,
  `first_degree` date DEFAULT NULL,
  `second_degree` date DEFAULT NULL,
  `third_degree` date DEFAULT NULL,
  `reentry_date` date DEFAULT NULL,
  `yrs_svc` int(11) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `mbr_type` char(1) DEFAULT NULL,
  `mbr_cls` char(1) DEFAULT NULL,
  `assy` char(1) DEFAULT NULL,
  `exm` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_roster`
--

INSERT INTO `membership_roster` (`member_number`, `council_id`, `member_name`, `street_address`, `city_state_zip`, `first_degree`, `second_degree`, `third_degree`, `reentry_date`, `yrs_svc`, `birth_date`, `mbr_type`, `mbr_cls`, `assy`, `exm`) VALUES
('5350618', 6, 'ABANG, STEPHEN JHON PONTEJON', 'Tambulig Zamboanga Del Sur', 'Tambulig 7214', '2025-03-09', NULL, NULL, NULL, 1, '2004-10-08', 'R', 'A', NULL, NULL),
('6256', 7, 'Sample Member', 'Member Address', 'asfmaomd', '2025-02-19', NULL, NULL, NULL, 1, '2014-06-18', 'A', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `used`, `created_at`) VALUES
(1, 'inesphilip72@gmail.com', 'd18c9e58f1055c97ef8b70e2251f3ed2deccb279915ec76d818ab5073324efbe', '2025-05-31 07:37:26', 1, '2025-05-31 04:37:26'),
(2, 'inesphilip72@gmail.com', '6d85e0e8a9323c8220ac80c50161e195361598a794f89bbcfc108d055e8916c2', '2025-05-31 07:37:43', 1, '2025-05-31 04:37:43'),
(4, 'inesphilip72@gmail.com', '3176285b00ce09081aa50b3eeb203380379a153059d5387b199fbc1afbf7898f', '2025-05-31 07:42:25', 1, '2025-05-31 04:42:25'),
(5, 'inesphilip72@gmail.com', 'da7e595a7c57e6b65036fc71b0e227d216d21c4bc256dfaabd636ff3605569b7', '2025-05-31 08:11:28', 1, '2025-05-31 05:11:28'),
(6, 'inesphilip72@gmail.com', '59608fbcfbecdad93e0bfb30df4b6b2c96f9e0a68ce634b074aec97dc2dd0964', '2025-05-31 08:16:00', 1, '2025-05-31 05:16:00'),
(7, 'inesphilip72@gmail.com', '296ddadb8ae186c17c17834c992c2ddc15970f6153093f34075fb0741e9be2b5', '2025-05-31 08:17:07', 1, '2025-05-31 05:17:07'),
(8, 'inesphilip72@gmail.com', '45df1748a5f197540348cd30a41c1f7fa5f255d438603d70f1520c564e8d6a85', '2025-05-31 08:21:44', 1, '2025-05-31 05:21:44'),
(9, 'inesphilip72@gmail.com', 'a329a1fb82003b253ae562b3ef6e4259c4f48bcadde1133ad08735ee6b999aad', '2025-05-31 08:24:12', 1, '2025-05-31 05:24:12'),
(10, 'inesphilip72@gmail.com', '57d876368e553aa22757ad4f4e6578bb722709ef82825073b7a01169ce5cbdbe', '2025-05-31 08:33:08', 1, '2025-05-31 05:33:08'),
(11, 'inesphilip72@gmail.com', '56c9ae52920d2a38d65b9aa88bcc406c41bec8c4cca10781ffbe1c701b3931a6', '2025-05-31 08:35:32', 0, '2025-05-31 05:35:32');

-- --------------------------------------------------------

--
-- Table structure for table `personal_details`
--

CREATE TABLE `personal_details` (
  `details_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `signature_file` varchar(255) DEFAULT NULL,
  `pregnant_question` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_details`
--

INSERT INTO `personal_details` (`details_id`, `applicant_id`, `user_id`, `height`, `weight`, `signature_file`, `pregnant_question`) VALUES
(5, 17, 13, 0.00, 0.00, 'http://localhost/app/uploads/signature/Image_1746675323.jpg', 'Yes'),
(6, 18, 13, 0.00, 0.00, 'http://localhost/app/uploads/signature/Image_1768670314.png', 'No'),
(7, 19, 18, 167.00, 67.00, 'http://localhost/app/uploads/signature/Image_1768789043.jpg', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `physician`
--

CREATE TABLE `physician` (
  `physician_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `physician_name` varchar(100) DEFAULT NULL,
  `contact_number` int(15) NOT NULL,
  `physician_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `physician`
--

INSERT INTO `physician` (`physician_id`, `applicant_id`, `user_id`, `physician_name`, `contact_number`, `physician_address`) VALUES
(8, 17, 13, 'Wynne Brock', 462, 'Aperiam repudiandae'),
(9, 18, 13, 'Sigourney England', 70, 'Eaque doloremque ea'),
(10, 19, 18, 'MacKenzie Leon', 366, 'Vitae atque laborios');

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
  `contribution_amount` double DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`plan_id`, `applicant_id`, `user_id`, `fraternal_benefits_id`, `council_id`, `payment_mode`, `contribution_amount`, `currency`) VALUES
(15, 17, 13, 5, 6, 'quarterly', 1000, 'PHP'),
(16, 18, 13, 6, 7, 'monthly', 1000, 'PHP'),
(17, 19, 18, 9, 6, 'semi-annually', 1120, 'PHP');

-- --------------------------------------------------------

--
-- Table structure for table `plan_rate_tables`
--

CREATE TABLE `plan_rate_tables` (
  `id` int(11) NOT NULL,
  `fraternal_benefits_id` int(11) NOT NULL,
  `issue_age` varchar(20) NOT NULL,
  `rate_50k_to_100k` double NOT NULL,
  `rate_100k_up` double NOT NULL,
  `adb_rate` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qouta`
--

CREATE TABLE `qouta` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `qouta` bigint(20) NOT NULL,
  `current_amount` bigint(20) NOT NULL,
  `duration` date NOT NULL,
  `status` enum('on-progress','completed','expired') NOT NULL DEFAULT 'on-progress',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qouta`
--

INSERT INTO `qouta` (`id`, `user_id`, `qouta`, `current_amount`, `duration`, `status`, `date_created`) VALUES
(1, 14, 1000000, 0, '2025-10-31', 'expired', '2025-08-30 14:53:22'),
(2, 11, 10000000, 100000, '2026-09-19', 'on-progress', '2025-09-19 22:11:39'),
(3, 10, 10000000, 100000, '2026-09-26', 'on-progress', '2025-09-26 21:19:18');

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `template` varchar(50) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `message_id` varchar(255) DEFAULT NULL,
  `provider_response` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sms_logs`
--

INSERT INTO `sms_logs` (`id`, `applicant_id`, `phone_number`, `subject`, `content`, `template`, `due_date`, `status`, `message_id`, `provider_response`, `created_at`) VALUES
(1, 17, '639105200970', 'Reminder', 'Hi Sandras Marcoses, this is a friendly reminder about your application. Please contact us if you have questions.', NULL, NULL, 'sent', '253607515', '[{\"message_id\":253607515,\"user_id\":57165,\"user\":\"inesphilip72@gmail.com\",\"account_id\":57026,\"account\":\"Knights of Columbus Fraternal Association\",\"recipient\":\"639105200970\",\"message\":\"Reminder\\n\\nHi Sandras Marcoses, this is a friendly reminder about your application. Please contact us if you have questions.\",\"sender_name\":\"KCFAPI\",\"network\":\"Smart\",\"status\":\"Pending\",\"type\":\"Single\",\"source\":\"Api\",\"created_at\":\"2025-08-20 19:16:32\",\"updated_at\":\"2025-08-20 19:16:32\"}]', '2025-08-20 19:16:32'),
(2, 18, '977', 'APPLICATION APPROVED', 'Your application was being approved!', NULL, NULL, 'failed', NULL, '{\"number\":[\"The number format is invalid.\"]}', '2026-01-18 01:23:32'),
(3, 18, '977', 'APPLICATION APPROVED', 'Your application was being approved!', NULL, NULL, 'failed', NULL, '{\"number\":[\"The number format is invalid.\"]}', '2026-01-19 00:48:43'),
(4, 19, '639105200970', 'APPLICATION APPROVED', 'Your application was being approved!', NULL, NULL, 'sent', '262819399', '[{\"message_id\":262819399,\"user_id\":57165,\"user\":\"inesphilip72@gmail.com\",\"account_id\":57026,\"account\":\"Knights of Columbus Fraternal Association\",\"recipient\":\"639105200970\",\"message\":\"APPLICATION APPROVED\\n\\nYour application was being approved!\",\"sender_name\":\"KCFAPI\",\"network\":\"Smart\",\"status\":\"Pending\",\"type\":\"Single\",\"source\":\"Api\",\"created_at\":\"2026-01-19 10:18:13\",\"updated_at\":\"2026-01-19 10:18:13\"}]', '2026-01-19 10:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `amount_paid` double NOT NULL,
  `currency` varchar(10) DEFAULT 'PHP',
  `next_due_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Paid',
  `payment_timing_status` varchar(20) DEFAULT '''On-Time''',
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `applicant_id`, `user_id`, `plan_id`, `payment_date`, `amount_paid`, `currency`, `next_due_date`, `status`, `payment_timing_status`, `remarks`, `created_at`) VALUES
(1, 17, 13, 15, '2025-05-26', 3000, 'PHP', '2025-06-26', 'Paid', 'On-Time', NULL, '2025-05-26 08:39:26'),
(2, 17, 13, 15, '2025-06-28', 3000, 'PHP', '2025-07-26', 'Paid', 'Late', 'jhb', '2025-05-26 10:11:54'),
(4, 17, 13, 15, '2025-07-26', 3000, 'PHP', '2025-08-26', 'Paid', 'On-Time', 'samples', '2025-05-26 13:19:28'),
(6, 17, 13, 15, '2026-01-21', 4500, 'PHP', '2026-04-21', 'Paid', 'On-Time', 'partial payment only\r\n', '2026-01-19 01:29:43'),
(7, 17, 13, 15, '2026-01-30', 365, 'PHP', '2026-04-30', 'Paid', 'On-Time', 'sdfsa', '2026-01-19 02:42:26'),
(8, 17, 13, 15, '2026-02-21', 365, 'PHP', '2026-05-21', 'Paid', 'On-Time', 'Paid via bank', '2026-01-19 03:55:35'),
(9, 17, 13, 16, '2026-01-18', 365, 'PHP', '2026-04-18', 'Paid', 'On-Time', 'sample edit', '2026-01-19 04:07:53'),
(10, 17, 13, 16, '2026-04-18', 365, 'PHP', '2026-07-18', 'Paid', 'On-Time', '.khblh', '2026-01-19 04:21:05'),
(11, 19, 18, 17, '2026-01-19', 672.36, 'PHP', '2026-07-19', 'Paid', 'On-Time', 'Paid via gcash', '2026-01-19 14:06:31');

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
(10, 'Bongbong', 'Marcos', 'MARCOSCODE', 'bongmarcos@gmail.com', '09105200970', 'unit-manager', '$2y$10$m.IidSJa6LQJDBt6A1cJs.c8NYkD/.IZXvlA15g2YG7zz5nl82Y9S', 'approved', '2025-04-29 03:12:25'),
(11, 'Sarah', 'Duterte', 'SARAHCODE', 'sarahduterte@gmail.com', '09105200975', 'fraternal-counselor', '$2y$10$Mz8F/Ya25UUr5dstBEZ2N.VJuxuLq0hi5IeK3y1CwC8LMvoX2ERIm', 'approved', '2025-04-29 03:17:08'),
(13, 'Sandro', 'Marcos', 'SANDROCODE', 'sandromarcos@gmail.com', '09105200974', 'member', '$2y$10$9t/uW.w9GTrU7HsdBQjck.W3VQcNAkz71N6eZ7yPCm3aSNcdKnJCm', 'approved', '2025-04-29 03:19:36'),
(14, 'Baste', 'Duterte', 'BasteCode', 'basteduterte@gmail.com', '09090909011', 'unit-manager', '$2y$10$4vI/eo6dVFgMVPGizOhmJucuWURLGo5foZFTDu8mGQWO7Mb0z3Fia', 'approved', '2025-06-03 11:15:44'),
(15, 'Pulong', 'Duterte', 'PULONGCODE', 'pulongduterte@gmail.com', '09123456788', 'fraternal-counselor', '$2y$10$P/xBZFYm/UWrCbQehQwOG.htLrTU.xufcvbBop9vJAzUrI8xdoGYO', 'pending', '2025-06-03 11:52:12'),
(16, 'admin', 'admin', 'KCFAPIADMIN', 'admin@kcfapi.com', '09123456789', 'admin', '$2y$10$7ozqmdWl7NNp6Pmg1cHt9.NQEK8SMfCEJ7icOS/EmfAPjFiPAehtm', 'approved', '2025-08-19 15:21:15'),
(17, 'Jr', 'Fernandez', 'kcf123', 'jrfernandez@gmail.com', '09123456789', 'member', '$2y$10$DjdIzr9lT/zra4elTGnmFePnlSpLTOY6reUTKK1qCXqjz4BqkO3XW', 'pending', '2025-10-29 21:29:04'),
(18, 'Piolo', 'Pascual', 'GHPIO', 'piolo@gmail.com', '09105200970', 'member', '$2y$10$C8WLyZ11g8CvfX/BdhoZ0.9pI2j0562UStl49x2zjqvaDkjUwlsIa', 'approved', '2026-01-18 22:11:16');

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
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`applicant_id`),
  ADD KEY `fraternal_counserlor_FKs` (`fraternal_counselor_id`),
  ADD KEY `fk_applicants_users` (`user_id`);

--
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`beneficiary_id`),
  ADD KEY `beneficiaries_ibfk_1` (`applicant_id`),
  ADD KEY `bene_user_id_FK` (`user_id`);

--
-- Indexes for table `benefit_rates`
--
ALTER TABLE `benefit_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `benefit_rate_categories`
--
ALTER TABLE `benefit_rate_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_id` (`plan_id`);

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
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_uploadedby_userid` (`uploaded_by`);

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
-- Indexes for table `membership_roster`
--
ALTER TABLE `membership_roster`
  ADD PRIMARY KEY (`member_number`),
  ADD KEY `council_id_fk_roaster` (`council_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `token` (`token`);

--
-- Indexes for table `personal_details`
--
ALTER TABLE `personal_details`
  ADD PRIMARY KEY (`details_id`),
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `pr_user_id_FK` (`user_id`);

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
-- Indexes for table `plan_rate_tables`
--
ALTER TABLE `plan_rate_tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qouta`
--
ALTER TABLE `qouta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_quota_status` (`status`),
  ADD KEY `idx_quota_user_status` (`user_id`,`status`);

--
-- Indexes for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id_fk` (`user_id`),
  ADD KEY `applicant_id_fk` (`applicant_id`),
  ADD KEY `plan_id_fk` (`plan_id`);

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
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `applicant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `beneficiary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `benefit_rates`
--
ALTER TABLE `benefit_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `benefit_rate_categories`
--
ALTER TABLE `benefit_rate_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `council`
--
ALTER TABLE `council`
  MODIFY `council_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employment`
--
ALTER TABLE `employment`
  MODIFY `employment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `family_background`
--
ALTER TABLE `family_background`
  MODIFY `family_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `family_health`
--
ALTER TABLE `family_health`
  MODIFY `health_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `fraternal_benefits`
--
ALTER TABLE `fraternal_benefits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `health_questions`
--
ALTER TABLE `health_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `medical_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `membership`
--
ALTER TABLE `membership`
  MODIFY `membership_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `personal_details`
--
ALTER TABLE `personal_details`
  MODIFY `details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `physician`
--
ALTER TABLE `physician`
  MODIFY `physician_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `plan_rate_tables`
--
ALTER TABLE `plan_rate_tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qouta`
--
ALTER TABLE `qouta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sms_logs`
--
ALTER TABLE `sms_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `applicants`
--
ALTER TABLE `applicants`
  ADD CONSTRAINT `fk_applicants_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fraternal_counserlor_FKs` FOREIGN KEY (`fraternal_counselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id_applicant_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD CONSTRAINT `bene_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `beneficiaries_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `benefit_rates`
--
ALTER TABLE `benefit_rates`
  ADD CONSTRAINT `benefit_rates_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `benefit_rate_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `benefit_rate_categories`
--
ALTER TABLE `benefit_rate_categories`
  ADD CONSTRAINT `benefit_rate_categories_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `fraternal_benefits` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fk_uploadedby_userid` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `membership_roster`
--
ALTER TABLE `membership_roster`
  ADD CONSTRAINT `council_id_fk_roaster` FOREIGN KEY (`council_id`) REFERENCES `council` (`council_id`);

--
-- Constraints for table `personal_details`
--
ALTER TABLE `personal_details`
  ADD CONSTRAINT `personal_details_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`),
  ADD CONSTRAINT `pr_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Constraints for table `qouta`
--
ALTER TABLE `qouta`
  ADD CONSTRAINT `qouta_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `applicant_id_fk` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `plan_id_fk` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`plan_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
