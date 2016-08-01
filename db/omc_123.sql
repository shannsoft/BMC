-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2016 at 08:16 AM
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
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `district_master`
--

INSERT INTO `district_master` (`district_id`, `name`) VALUES
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
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `mobile` int(15) DEFAULT NULL,
  `email` text,
  `ulb_id` int(11) NOT NULL,
  `dob` date NOT NULL,
  `doj` date NOT NULL,
  `dor` date NOT NULL,
  `emp_status` text NOT NULL,
  `createdDate` date NOT NULL,
  `modifiedDate` date DEFAULT NULL,
  `isDeleted` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pension_history`
--

CREATE TABLE `pension_history` (
  `history_id` int(11) NOT NULL,
  `pension_id` int(11) NOT NULL,
  `ulb_ref_no` text NOT NULL,
  `ulb_ref_date` date NOT NULL,
  `department_ref_no` text NOT NULL,
  `department_ref_date` date NOT NULL,
  `section_ref_no` text NOT NULL,
  `section_ref_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `remarks` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `name` text NOT NULL,
  `district_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ulb_master`
--

INSERT INTO `ulb_master` (`ulb_id`, `name`, `district_id`) VALUES
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
  `mobile` int(15) NOT NULL,
  `address` text NOT NULL,
  `roll_id` int(11) NOT NULL,
  `ulb_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `user_token` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_tbl`
--

INSERT INTO `user_tbl` (`user_id`, `email`, `user_name`, `password`, `mobile`, `address`, `roll_id`, `ulb_id`, `district_id`, `user_token`) VALUES
(1, 'admin@gmail.com', 'Admin', 'E10ADC3949BA59ABBE56E057F20F883E', 0, '', 0, 0, 0, ''),
(2, 'uibno@gmail.com', 'uib', 'E10ADC3949BA59ABBE56E057F20F883E', 0, '', 3, 1, 0, 'O11GpIXXBU1mldWd8HWmUYKa98ysPM5ApYAoSlKdFYNUMrsOFRWLiOY69oLH');

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
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_table`
--
ALTER TABLE `employee_table`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pension_history`
--
ALTER TABLE `pension_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `retirement_pension`
--
ALTER TABLE `retirement_pension`
  MODIFY `pension_id` int(11) NOT NULL AUTO_INCREMENT;
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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
