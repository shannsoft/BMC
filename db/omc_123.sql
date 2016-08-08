-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2016 at 09:27 AM
-- Server version: 10.0.17-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omc_123`
--

-- --------------------------------------------------------

--
-- Table structure for table `designation_master`
--

CREATE TABLE `designation_master` (
  `designation_id` int(11) NOT NULL,
  `designation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `designation_master`
--

INSERT INTO `designation_master` (`designation_id`, `designation`) VALUES
(1, 'Office Superitendent'),
(3, 'Head Clerk'),
(4, 'Manager'),
(5, 'Upper Division Clerk'),
(6, 'Tax Daroga'),
(7, 'Accountant'),
(8, 'Octroi Superitendent'),
(9, 'Lower Division Clerk'),
(10, 'Cashier'),
(11, 'Typist'),
(12, 'Carriage Inspector'),
(13, 'Warrent Officer'),
(14, 'Light Inspector'),
(15, 'Librerian'),
(16, 'Birth/Death Registration Clerk');

-- --------------------------------------------------------

--
-- Table structure for table `district_master`
--

CREATE TABLE `district_master` (
  `district_id` int(11) NOT NULL,
  `district_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `district_master`
--

INSERT INTO `district_master` (`district_id`, `district_name`) VALUES
(1, 'Anugul'),
(2, 'Bargarh'),
(3, 'Bhadrak'),
(4, 'Balasore'),
(5, 'Balangir'),
(6, 'Boudha'),
(7, 'Cuttack'),
(8, 'Deogarh'),
(9, 'Dhekhanal'),
(10, 'Gajapati'),
(11, 'Ganjam'),
(12, 'Jagatsinghpur'),
(13, 'jajpur'),
(14, 'Jharsuguda'),
(15, 'Kalahandi'),
(16, 'Kandhamal'),
(17, 'Kendrapara'),
(18, 'Keonjhar'),
(19, 'Khurda'),
(20, 'Koraput'),
(21, 'Malkangiri'),
(22, 'Mayurbhanj'),
(23, 'Nuapada'),
(24, 'Nabarangpur'),
(25, 'Nayagarh'),
(26, 'Puri'),
(27, 'Rayagada'),
(28, 'Sambalpur'),
(29, 'Subarnapur'),
(30, 'Sundargarh');

-- --------------------------------------------------------

--
-- Table structure for table `document_master`
--

CREATE TABLE `document_master` (
  `document_id` int(11) NOT NULL,
  `document_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `document_master`
--

INSERT INTO `document_master` (`document_id`, `document_name`) VALUES
(1, 'Xerox copy with attestation of death Certificate and legal heir certificate in Case of Family pension.'),
(2, 'Periodical Service Verification Certificate with seal and signature'),
(3, 'Period of suspension and its result to be recorded in Service Book'),
(4, 'The duly attested and counter checked Pay fixation statement by the District Audit office L.F.A'),
(5, 'Condomation of overage wanting Approved by the Council Resolution and approval of D.M.A'),
(6, 'Service interruption Certificate '),
(7, 'Attestation of joint single photograph'),
(8, 'No dues Certificate'),
(9, 'A Certificate as whether any Depil/Disiplinary/Vilgilance or criminal Proceeding if any may be required as per GO No 175/HUD. Dt 02.01.2003'),
(10, 'The last Pay certificate in OTC form'),
(11, 'Form-I and Form-II relating to Nomination for family pension and List of family members should be filled.'),
(12, 'In service death Form-XI addressed To the widow/widower.'),
(13, 'In case family pension SL.No 10 and SL.No 11 of Form XII i.e Attestation of two Gazetted Govt Servants or two more person of respectability of the area.'),
(14, 'The remarks of the Chairperson in Case of family pension with seal and Signature');

-- --------------------------------------------------------

--
-- Table structure for table `employee_table`
--

CREATE TABLE `employee_table` (
  `emp_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `designation_id` int(11) NOT NULL,
  `villege_town` text NOT NULL,
  `city` text NOT NULL,
  `post` text NOT NULL,
  `police_station` text NOT NULL,
  `district_id` int(11) NOT NULL,
  `pin` int(11) NOT NULL,
  `mobile` text,
  `email` text,
  `ulb_id` int(11) NOT NULL,
  `dob` date NOT NULL,
  `doj` date NOT NULL,
  `dor` date NOT NULL,
  `emp_status` text NOT NULL,
  `createdDate` date NOT NULL,
  `modifiedDate` date DEFAULT NULL,
  `isDeleted` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_table`
--

INSERT INTO `employee_table` (`emp_id`, `name`, `designation_id`, `villege_town`, `city`, `post`, `police_station`, `district_id`, `pin`, `mobile`, `email`, `ulb_id`, `dob`, `doj`, `dor`, `emp_status`, `createdDate`, `modifiedDate`, `isDeleted`, `created_by`) VALUES
(1, 'Santosh Majhi', 1, 'bhubaneswar', 'bhubaneswar', 'bbsr', 'test', 1, 751001, '9438753143', 'santoshmajhi99@gmail.com', 2, '2016-08-03', '2016-08-04', '2016-08-03', 'Retired', '2016-08-01', '2016-08-04', 0, 2),
(2, 'Test', 3, 'Bhubaneswar', 'Bhubaneswar', 'sahid nagar', 'test', 1, 751001, '9078640778', 'santoshmajhi99@gmail.com', 2, '1981-05-14', '2016-08-11', '2017-03-18', 'Active', '2016-08-05', NULL, 0, 2),
(3, 'pradeep', 3, 'kdp', 'bhadrak', 'bdk', 'bdk', 9, 456789, '9876545678', 'hg@gmail.com', 5, '1972-11-03', '1993-02-09', '2032-11-03', 'Retired', '2016-08-08', NULL, 0, 4),
(4, 'seema', 1, 'kdp', 'bls', 'bdk', 'bdk', 9, 456778, '9876545678', 'hgg@gmail.com', 4, '2016-08-03', '2016-08-05', '2076-08-03', 'Retired', '2016-08-08', NULL, 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `pension_history`
--

CREATE TABLE `pension_history` (
  `history_id` int(11) NOT NULL,
  `pension_id` int(11) NOT NULL,
  `ulb_ref_no` text NOT NULL,
  `ulb_ref_date` date DEFAULT NULL,
  `department_ref_no` text,
  `department_ref_date` date DEFAULT NULL,
  `section_ref_no` text,
  `section_ref_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pension_history`
--

INSERT INTO `pension_history` (`history_id`, `pension_id`, `ulb_ref_no`, `ulb_ref_date`, `department_ref_no`, `department_ref_date`, `section_ref_no`, `section_ref_date`) VALUES
(1, 1, '123', '2016-08-13', '', '0000-00-00', '', '0000-00-00'),
(2, 1, '123', '2016-08-10', '', '0000-00-00', '', '0000-00-00'),
(3, 1, '123', '2016-08-05', '', '0000-00-00', '', '0000-00-00'),
(4, 1, '123', '2016-08-12', NULL, NULL, NULL, NULL),
(5, 1, '12313', '2016-08-12', NULL, NULL, NULL, NULL),
(6, 1, '12313', '2016-08-11', NULL, NULL, NULL, NULL),
(7, 2, '123', '2016-08-12', NULL, NULL, NULL, NULL),
(8, 3, '123', '2016-08-12', NULL, NULL, NULL, NULL),
(9, 4, '123', '2016-08-12', NULL, NULL, NULL, NULL),
(10, 5, '123', '2016-08-12', NULL, NULL, NULL, NULL),
(11, 6, '123', '2016-08-12', NULL, NULL, NULL, NULL),
(12, 7, '123', '2016-08-12', NULL, NULL, NULL, NULL),
(13, 8, '123', '2016-08-12', NULL, NULL, NULL, NULL),
(14, 9, '123', '2016-08-12', NULL, NULL, NULL, NULL),
(15, 10, '123', '2016-08-12', NULL, NULL, NULL, NULL),
(16, 11, '123', '2016-08-12', NULL, NULL, NULL, NULL),
(17, 2, '123', '2016-08-25', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `retirement_pension`
--

CREATE TABLE `retirement_pension` (
  `pension_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `pension_type` text NOT NULL,
  `pension_category` text NOT NULL,
  `documents` text,
  `pending_at` text,
  `file_no` text,
  `dod` date DEFAULT NULL,
  `remarks` text,
  `nominee` text,
  `relation` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `retirement_pension`
--

INSERT INTO `retirement_pension` (`pension_id`, `emp_id`, `pension_type`, `pension_category`, `documents`, `pending_at`, `file_no`, `dod`, `remarks`, `nominee`, `relation`) VALUES
(1, 1, 'Family Pension', 'NON LFS', '1,2,3,4,7,9,11,12,13,14', 'ULB', NULL, '0000-00-00', 'Send to central officeasdfaasdfasdf', 'asdfd', 'asdfasd'),
(2, 3, 'Pension', 'LFS', '1,2,8,12', 'ULB', NULL, '0000-00-00', 'gfhf', '', ''),
(3, 3, 'Pension', 'LFS', '1,2,8', 'ULB', NULL, '0000-00-00', 'gfhf', '', ''),
(4, 3, 'Pension', 'LFS', '1,2,8', 'ULB', NULL, '0000-00-00', 'gfhf', '', ''),
(5, 3, 'Pension', 'LFS', '1,2,8', 'ULB', NULL, '0000-00-00', 'gfhf', '', ''),
(6, 3, 'Pension', 'LFS', '1,2,8', 'ULB', NULL, '0000-00-00', 'gfhf', '', ''),
(7, 3, 'Pension', 'LFS', '1,2,8', 'ULB', NULL, '0000-00-00', 'gfhf', '', ''),
(8, 3, 'Pension', 'LFS', '1,2,8', 'ULB', NULL, '0000-00-00', 'gfhf', '', ''),
(9, 3, 'Pension', 'LFS', '1,2,8', 'ULB', NULL, '0000-00-00', 'gfhf', '', ''),
(10, 3, 'Pension', 'LFS', '1,2,8', 'ULB', NULL, '0000-00-00', 'gfhf', '', ''),
(11, 3, 'Pension', 'LFS', '1,2,8', 'ULB', NULL, '0000-00-00', 'gfhf', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `role_master`
--

CREATE TABLE `role_master` (
  `roll_id` int(11) NOT NULL,
  `rolle_type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_master`
--

INSERT INTO `role_master` (`roll_id`, `rolle_type`) VALUES
(1, 'Central Office'),
(2, 'Audit'),
(3, 'ULB/NAC'),
(5, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `section_master`
--

CREATE TABLE `section_master` (
  `section_id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ulb_master`
--

CREATE TABLE `ulb_master` (
  `ulb_id` int(11) NOT NULL,
  `ulb_name` text NOT NULL,
  `district_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ulb_master`
--

INSERT INTO `ulb_master` (`ulb_id`, `ulb_name`, `district_id`) VALUES
(1, 'Angul (M)', 1),
(2, 'Athamallik (N)', 1),
(3, 'Talcher (M)', 1),
(4, 'Bhuban (N)', 9),
(5, 'Dhekanal (M)', 9),
(6, 'Kamakhyanagar (N)', 9);

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `user_id` int(11) NOT NULL,
  `email` text NOT NULL,
  `user_name` text NOT NULL,
  `password` text NOT NULL,
  `mobile` text,
  `address` text,
  `roll_id` int(11) NOT NULL,
  `ulb_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `user_token` text,
  `otp` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_tbl`
--

INSERT INTO `user_tbl` (`user_id`, `email`, `user_name`, `password`, `mobile`, `address`, `roll_id`, `ulb_id`, `district_id`, `user_token`, `otp`) VALUES
(2, 'santoshmajhi99@gmail.com', 'ulb_angul', 'e10adc3949ba59abbe56e057f20f883e', '9438753143', 'Anugul', 3, 1, 1, '', '831797'),
(3, 'info@ulbpension.com', 'ulb_pension', 'e10adc3949ba59abbe56e057f20f883e', '9876565656', 'bbsr', 1, 1, 1, '', ''),
(4, 'biswal@gmail.com', 'ulb_bhuban', 'e10adc3949ba59abbe56e057f20f883e', '9853212234', 'dhenkanal', 3, 4, 9, 'FFrllCRA41rctAyECx1U8Bcs40Wf3rzLa8WX0OojFHLMJvqQjsctZfq7Q7XM', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `designation_master`
--
ALTER TABLE `designation_master`
  ADD PRIMARY KEY (`designation_id`);

--
-- Indexes for table `district_master`
--
ALTER TABLE `district_master`
  ADD PRIMARY KEY (`district_id`);

--
-- Indexes for table `document_master`
--
ALTER TABLE `document_master`
  ADD PRIMARY KEY (`document_id`);

--
-- Indexes for table `employee_table`
--
ALTER TABLE `employee_table`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `pension_history`
--
ALTER TABLE `pension_history`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `retirement_pension`
--
ALTER TABLE `retirement_pension`
  ADD PRIMARY KEY (`pension_id`);

--
-- Indexes for table `role_master`
--
ALTER TABLE `role_master`
  ADD PRIMARY KEY (`roll_id`);

--
-- Indexes for table `section_master`
--
ALTER TABLE `section_master`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `ulb_master`
--
ALTER TABLE `ulb_master`
  ADD PRIMARY KEY (`ulb_id`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `designation_master`
--
ALTER TABLE `designation_master`
  MODIFY `designation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `district_master`
--
ALTER TABLE `district_master`
  MODIFY `district_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `document_master`
--
ALTER TABLE `document_master`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `employee_table`
--
ALTER TABLE `employee_table`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `pension_history`
--
ALTER TABLE `pension_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `retirement_pension`
--
ALTER TABLE `retirement_pension`
  MODIFY `pension_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `role_master`
--
ALTER TABLE `role_master`
  MODIFY `roll_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `section_master`
--
ALTER TABLE `section_master`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ulb_master`
--
ALTER TABLE `ulb_master`
  MODIFY `ulb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
