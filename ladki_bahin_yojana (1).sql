-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2024 at 08:57 AM
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
-- Database: `ladki_bahin_yojana`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'password123');

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `husband_name` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `aadhaar_number` varchar(12) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `email_id` varchar(500) NOT NULL,
  `aadhaar_address` varchar(500) NOT NULL,
  `district` varchar(255) NOT NULL,
  `village` varchar(255) NOT NULL,
  `panchayat` varchar(255) NOT NULL,
  `benefit_status` enum('yes','no') NOT NULL,
  `scheme` enum('scheme1','scheme2') NOT NULL,
  `marital_status` enum('married','unmarried') NOT NULL,
  `birth_place_status` enum('yes','no') NOT NULL,
  `bank_name` varchar(255) DEFAULT 'Not Provided',
  `account_name` varchar(255) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `ifsc_code` varchar(11) NOT NULL,
  `aadhaar_linked_to_bank` enum('yes','no') NOT NULL,
  `aadhar_card_path` varchar(255) DEFAULT NULL,
  `id_card_path` varchar(255) DEFAULT NULL,
  `income_card_path` varchar(255) DEFAULT NULL,
  `acceptance_card_path` varchar(255) DEFAULT NULL,
  `passbook_path` varchar(255) DEFAULT NULL,
  `other_state_path` varchar(255) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `situation` varchar(50) DEFAULT 'Pending',
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`id`, `full_name`, `husband_name`, `birth_date`, `aadhaar_number`, `mobile_number`, `email_id`, `aadhaar_address`, `district`, `village`, `panchayat`, `benefit_status`, `scheme`, `marital_status`, `birth_place_status`, `bank_name`, `account_name`, `account_number`, `ifsc_code`, `aadhaar_linked_to_bank`, `aadhar_card_path`, `id_card_path`, `income_card_path`, `acceptance_card_path`, `passbook_path`, `other_state_path`, `photo_path`, `created_at`, `situation`, `rejection_reason`) VALUES
(15, 'Anita Dilip Bhosale', 'Dilip Hanmantrao Bhosale', '1982-06-02', '516551480480', '9403827471', '', 'srno 2978, rh no 4, swami samrth appt ,  talegaon dabhade', 'pune', 'talegaon dabhade ', 'nagar_panchayat', 'no', 'scheme1', 'married', 'no', 'Idib', 'anita bhosale', '6975264444', '1332334', 'yes', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211129_194506.jpg', 'uploads/IMG_20220203_180613.jpg', 'uploads/IMG_20211203_214019.jpg', 'uploads/IMG_20211116_211659.jpg', NULL, 'uploads/IMG_20220607_181643.jpg', '2024-09-01 15:27:09', 'Rejected', 'adsss'),
(16, 'Yojana Hile ', 'Dilip Hanmantrao Bhosale', '2003-05-02', '789878572475', '9359427445', '', 'srno 2978, rh no 4, swami samrth appt ,  talegaon dabhade', 'pune', 'Dadar', 'nagar_palika', 'no', 'scheme1', 'unmarried', 'no', 'Idib', 'Yojana Hile', '6975264444', '1332334', 'no', 'uploads/IMG_20220203_180613.jpg', 'uploads/IMG_20220124_103921.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211015_182432.jpg', 'uploads/IMG_20211116_211659.jpg', NULL, 'uploads/IMG_20211015_182432.jpg', '2024-09-01 15:44:05', 'Rejected', 'Name is Invalid'),
(17, 'Anita Dilip Bhosale', 'Dilip Hanmantrao Bhosale', '1990-06-05', '454502020505', '9403827471', '', 'srno 2978, rh no 4, swami samrth appt ,  talegaon dabhade', 'pune', 'pimpari', 'nagar_palika', 'yes', 'scheme1', 'married', 'no', 'Idib', 'Yojana Hile', '6975264444', '1332334', 'no', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211015_182432.jpg', 'uploads/IMG_20211203_214019.jpg', 'uploads/IMG_20220308_092508.jpg', 'uploads/IMG_20211027_120529.jpg', NULL, 'uploads/IMG_20211015_182432.jpg', '2024-09-02 15:04:24', 'Pending', NULL),
(18, 'Anita Dilip Bhosale', 'Dilip Hanmantrao Bhosale', '1994-02-05', '516551480480', '9403827471', '', 'srno 2978, rh no 4, swami samrth appt ,  talegaon dabhade', 'pune', 'pimpari', 'nagar_panchayat', 'yes', 'scheme1', 'married', 'no', 'Idib', 'Yojana Hile', '6975264444', '1332334', 'yes', 'uploads/IMG_20220203_180613.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20220308_092508.jpg', 'uploads/IMG_20220203_180613.jpg', 'uploads/IMG_20220308_092508.jpg', NULL, 'uploads/IMG_20211116_211659.jpg', '2024-09-02 15:25:05', 'Pending', NULL),
(19, 'Yojana Hile ', 'Dilip Hanmantrao Bhosale', '2003-03-02', '898956562525', '4544474745', '', 'srno 2978, rh no 4, swami samrth appt ,  talegaon dabhade', 'pune', 'pimpari', 'nagar_palika', 'yes', 'scheme1', 'married', 'no', 'Idib', 'Yojana Hile', '6975264444', '1332334', 'no', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', NULL, 'uploads/IMG_20211027_120529.jpg', '2024-09-02 16:58:57', 'Pending', NULL),
(20, 'Anita Dilip Bhosale', 'Dilip Hanmantrao Bhosale', '2001-03-02', '804140975059', '9130084197', '', 'srno 2978, rh no 4, swami samrth appt ,  talegaon dabhade', 'pune', ' talegaon dabhade', 'Municipal corporation', 'no', 'scheme1', 'unmarried', 'no', 'Idib', 'Yojana Hile', '6975264444', '1332334', 'yes', 'uploads/IMG_20211203_214019.jpg', 'uploads/IMG_20211116_211659.jpg', 'uploads/IMG_20220308_092508.jpg', 'uploads/IMG_20220203_180613.jpg', 'uploads/IMG_20211015_182432.jpg', NULL, 'uploads/IMG_20211027_120529.jpg', '2024-09-02 17:40:09', 'Approved', 'Approved successfully'),
(21, 'Anita Dilip Bhosale', 'Dilip Hanmantrao Bhosale', '1975-06-05', '121515141515', '9403827471', '', 'srno 2978, rh no 4, swami samrth appt ,  talegaon dabhade', 'pune', 'Talegaon Dabhade', 'Municipal corporation', 'no', 'scheme1', 'married', 'no', 'Idib', 'Yojana Hile', '6975264444', '1332334', 'yes', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', NULL, 'uploads/IMG_20211027_120529.jpg', '2024-09-03 04:05:10', 'Pending', NULL),
(22, 'Anita Dilip Bhosale', 'Dilip Hanmantrao Bhosale', '1990-06-05', '898956562524', '9403827471', 'adibhosale1013@gmail.com', 'srno 2978, rh no 4, swami samrth appt ,  talegaon dabhade', 'pune', 'Talegaon Dabhade', 'Municipality', 'no', 'scheme1', 'married', 'no', 'Idib', 'Yojana Hile', '6975264444', '1332334', 'yes', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', NULL, 'uploads/IMG_20211027_120529.jpg', '2024-09-03 12:01:21', 'Rejected', 'documents are not valid\r\n'),
(23, 'Aditi Vishwas Pawar', 'Vishwas Ajit Pawar', '1987-06-05', '787978797879', '9529907872', 'adiatharva7471@gmail.com', 'srno 2978, rh no 4, swami samrth appt ,  talegaon dabhade', 'pune', 'Talegaon Dabhade', 'Municipality', 'no', 'scheme1', 'married', 'no', 'Idib', 'Yojana Hile', '6975264444', '1332334', 'yes', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', NULL, 'uploads/IMG_20211027_120529.jpg', '2024-09-03 15:06:48', 'Rejected', 'Documents Are not valid'),
(24, 'Aditi Vishwas Pawar', 'Vishwas Ajit Pawar', '1976-05-02', '787474784141', '9529907872', 'adiatharva7471@gmail.com', 'srno 2978, rh no 4, swami samrth appt ,  talegaon dabhade', 'pune', 'Talegaon Dabhade', 'Municipality', 'no', 'scheme1', 'married', 'no', 'Idib', 'Yojana Hile', '6975264444', '1332334', 'no', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', 'uploads/IMG_20211027_120529.jpg', NULL, 'uploads/IMG_20211027_120529.jpg', '2024-09-03 15:17:05', 'Rejected', 'Documents are not valid');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `adharNo` varchar(12) NOT NULL,
  `mobileNo` varchar(10) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `taluka` varchar(255) NOT NULL,
  `village` varchar(255) NOT NULL,
  `municipalCorporation` varchar(255) NOT NULL,
  `authorizedPerson` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullName`, `email`, `adharNo`, `mobileNo`, `pass`, `district`, `taluka`, `village`, `municipalCorporation`, `authorizedPerson`, `created_at`, `profile_pic`, `reset_token`, `token_expiry`) VALUES
(6, 'Aditya Dilip Bhosale', 'adibhosale1013@gmail.com', '516551480480', '9403827471', '$2y$10$gwiQYiZ1gUsl7ykZ0IQPvujz2OYGGLECZ/zQIw.zVWmqJk11DpXzu', 'Mumbai', 'maval', 'talegaon dabhade', 'Municipal corporation', 'Anganwadi-Sevika', '2024-08-28 15:00:54', 'uploads/win moment fortune fest.jpg', '63f1fca7c99a5107aa470a74d491cb25bf3c61931dda9f39a16fd20a384e9ffc', '2024-09-01 10:58:25'),
(7, 'Yojana Hile', 'yojana956@gmail.com', '685656265632', '9359427445', '$2y$10$JLYZWdCXibCDVW.V6iJ5Q.kKLunaHz4VlAjaU8YWYxiR7pp2.zqgq', 'pune', 'maval', 'vadgaon', 'Nagarpalika', 'Anganwadi worker', '2024-08-31 07:43:08', 'uploads/aditya.jpg', NULL, NULL),
(8, 'Atharva Dilip Bhosale', 'atharva7471@gmail.com', '514551235687', '9689772221', '$2y$10$DNlWeq.ExL43IRip8w.bDeC1dkQM/rNbPwmt/dxaw7ixHidlPiCdS', 'pune', 'maval', 'talegaon dabhade', 'Nagarpalika', 'Anganwadi worker', '2024-09-01 08:04:06', 'uploads/IMG-20220721-WA0020.jpg', NULL, NULL),
(9, 'rohit bhote', 'rohitbhote8275@gmail.com', '804140975059', '9130084197', '$2y$10$Nhm3glYBOqaLLmQFlOGSLOA1qmwLfUFmdFCs7.HPbhP9Ad2BqukDS', 'pune', 'maval', 'Talegaon Dabhade', 'Municipal corporation', 'Setu-Sahitya-Kendra', '2024-09-02 17:30:28', 'uploads/IMG_20211015_182432.jpg', NULL, NULL),
(10, 'Aditya Dilip Bhosale', 'adiatharva7471@gmail.com', '787978797879', '9529907872', '$2y$10$LwO1qlSDomuwzn3MxfLan.KRjGzjHthVy563mW4yLZ89k7NGO69NO', 'Pune', 'Maval', 'Talegaon Dabhade', 'Municipality', 'Setu-Sahitya-Kendra', '2024-09-03 15:25:13', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `adharNo` (`adharNo`),
  ADD UNIQUE KEY `mobileNo` (`mobileNo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
