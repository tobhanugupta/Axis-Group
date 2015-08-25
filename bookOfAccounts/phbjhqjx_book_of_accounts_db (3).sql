-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2014 at 01:44 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phbjhqjx_book_of_accounts_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetCompanyDetails`(IN `id` INT(10))
BEGIN
    SELECT company_name, company_pan_no
    FROM company_details_t where company_id=id;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getListDetails`(IN `id` VARCHAR(50), IN `name` VARCHAR(50), IN `tableName` VARCHAR(50))
BEGIN
    SELECT id, name
    FROM tableName;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getSpecificDetails`(IN `tableName` VARCHAR(50))
BEGIN
 SET @t1 =CONCAT('SELECT * FROM ',tableName );
 PREPARE stmt3 FROM @t1;
 EXECUTE stmt3;
 DEALLOCATE PREPARE stmt3;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bank_details_t`
--

CREATE TABLE IF NOT EXISTS `bank_details_t` (
`bank_id` int(10) NOT NULL,
  `company_id` int(10) NOT NULL,
  `account_holder_name` varchar(50) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `bank_name` varchar(80) NOT NULL,
  `bank_ifsc` varchar(20) NOT NULL,
  `bank_micr` varchar(20) NOT NULL,
  `account_type` varchar(20) NOT NULL,
  `initial_bank_balance` decimal(10,0) NOT NULL,
  `bank_created_date` datetime NOT NULL,
  `abbrev` varchar(50) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `bank_details_t`
--

INSERT INTO `bank_details_t` (`bank_id`, `company_id`, `account_holder_name`, `account_number`, `bank_name`, `bank_ifsc`, `bank_micr`, `account_type`, `initial_bank_balance`, `bank_created_date`, `abbrev`) VALUES
(22, 10, 'shamsad', '100ld01', 'SBI', '1455dfd', '125dsdf', 'SBA', '50000', '2014-09-25 11:30:56', ''),
(21, 10, 'shamsad', '100ld01', 'SBI', '1455dfd', 'NA', 'SBA', '50000', '2014-09-25 11:30:18', ''),
(23, 10, 'shamsad', '100ld01', 'SBI', '1455dfd', '125dsdf', 'SBA', '50000', '2014-09-25 17:04:13', ''),
(24, 10, 'shamsad', '100ld01', 'SBI', '1455dfd', '', 'SBA', '50000', '2014-09-25 17:09:52', ''),
(25, 10, 'shamsad', '100ld01', 'SBI', '1455dfd', '', 'SBA', '50000', '2014-09-26 15:42:02', ''),
(26, 10, 'shamsad', '100ld01', 'SBI', '1455dfd', '', 'SBA', '50000', '2014-09-29 10:21:31', ''),
(27, 10, 'shamsad', '100ld01', 'SBI', '1455dfd', '', 'SBA', '50000', '2014-09-29 10:22:23', ''),
(28, 45, 'raj', '009287988668', 'nasvnbk', '7739nn', '', 'current', '4567', '2014-09-29 10:26:58', ''),
(29, 32, 'raghulk chohiurahb', '9728', '0skbsbakPBSAKBV', '8asbksvb', '', 'ahbiakb', '7738', '2014-09-29 17:33:31', ''),
(30, 10, 'shamsad', '100ld01', 'SBI', '1455dfd', '', 'SBA', '50000', '2014-09-29 17:34:25', ''),
(31, 10, 'shamsad', '100ld01', 'SBI', '1455dfd', '', 'SBA', '50000', '2014-09-29 17:35:57', ''),
(32, 10, 'shamsad', '100ld01', 'SBI', '1455dfd', '', 'SBA', '50000', '2014-09-29 17:36:25', '');

-- --------------------------------------------------------

--
-- Table structure for table `cheque_details_t`
--

CREATE TABLE IF NOT EXISTS `cheque_details_t` (
`cheque_id` int(10) NOT NULL,
  `payment_id` int(10) NOT NULL,
  `cheque_number` varchar(20) NOT NULL,
  `cheque_date` datetime NOT NULL,
  `to_whom_issued` varchar(50) NOT NULL,
  `cheque_amount` decimal(10,0) NOT NULL,
  `cheque_created_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `company_details_t`
--

CREATE TABLE IF NOT EXISTS `company_details_t` (
`company_id` int(10) NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `company_tan_no` varchar(20) NOT NULL,
  `company_pan_no` varchar(20) NOT NULL,
  `company_address` varchar(200) NOT NULL,
  `company_created_date` datetime NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `company_details_t`
--

INSERT INTO `company_details_t` (`company_id`, `company_name`, `company_tan_no`, `company_pan_no`, `company_address`, `company_created_date`) VALUES
(46, 'shamsad', 'test', 'pandd', 'ranchi', '2014-09-29 14:46:15'),
(45, 'techila2', 'bhdb9869', 'bavb23oqbho', 'hadapsar', '2014-09-29 10:26:30'),
(44, 'shamsad', 'test', 'pandd', 'ranchi', '2014-09-29 10:20:20'),
(43, 'techila1', '77yahu', 'HA25hn', 'jsjbs;nslb', '2014-09-29 10:20:06'),
(42, 'techila', '66537', '652hf', 'harmu ranchi', '2014-09-29 10:17:50'),
(41, 'shamsad', 'test', 'pandd', 'ranchi', '2014-09-26 15:54:51'),
(40, 'shamsad', 'test', 'pandd', 'ranchi', '2014-09-26 15:54:49'),
(39, 'shamsad', 'test', 'pandd', 'ranchi', '2014-09-26 15:17:53'),
(38, 'shamsad', 'test', 'pandd', 'ranchi', '2014-09-26 15:07:25'),
(37, 'shamsad', 'test', 'pandd', 'ranchi', '2014-09-26 12:22:36'),
(36, 'shamsad', 'test', 'pandd', 'ranchi', '2014-09-26 12:13:55'),
(35, 'shamsad', 'test', 'pandd', 'ranchi', '2014-09-26 12:09:42'),
(32, 'shamsad', 'test', 'pandd', 'ranchi', '2014-09-25 09:25:02'),
(34, 'shamsad', 'test', 'pandd', 'ranchi', '2014-09-26 12:08:18');

-- --------------------------------------------------------

--
-- Table structure for table `deposit_details_t`
--

CREATE TABLE IF NOT EXISTS `deposit_details_t` (
`deposit_id` int(10) NOT NULL,
  `deposit_bank_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `deposit_date` datetime NOT NULL,
  `cash_amount` decimal(10,0) NOT NULL,
  `deposit_type` varchar(20) NOT NULL,
  `deposit_created_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_t`
--

CREATE TABLE IF NOT EXISTS `login_t` (
`login_user_id` int(11) NOT NULL,
  `emp_id` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `usertype` varchar(20) NOT NULL DEFAULT 'normal'
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `login_t`
--

INSERT INTO `login_t` (`login_user_id`, `emp_id`, `password`, `usertype`) VALUES
(1, 'shamsad', '098f6bcd4621d373cade4e832627b4f6', 'user'),
(23, 'e-023', 'OGQxYzEzZmE=', 'user'),
(22, 'e-022', 'NDUzNmU1NWQ=', 'user'),
(21, 'e-021', 'MTBmZjQ1NjU=', 'user'),
(20, 'e-020', 'N2U5MjNhOTk=', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `net_banking_details_t`
--

CREATE TABLE IF NOT EXISTS `net_banking_details_t` (
`nbd_id` int(10) NOT NULL,
  `payment_id` int(10) NOT NULL,
  `type` varchar(20) NOT NULL,
  `nbd_created_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment_bank_details_t`
--

CREATE TABLE IF NOT EXISTS `payment_bank_details_t` (
`payment_bank_id` int(10) NOT NULL,
  `company_id` int(10) NOT NULL,
  `account_holder_name` varchar(50) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `bank_name` varchar(80) NOT NULL,
  `bank_address` varchar(150) NOT NULL,
  `bank_ifsc` varchar(20) NOT NULL,
  `bank_micr` varchar(20) NOT NULL,
  `account_type` varchar(20) NOT NULL,
  `initial_bank_balance` decimal(10,0) NOT NULL,
  `bank_created_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment_details_t`
--

CREATE TABLE IF NOT EXISTS `payment_details_t` (
`payment_id` int(10) NOT NULL,
  `payment_bank_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `payment_date` datetime NOT NULL,
  `payment_reason` varchar(150) NOT NULL,
  `cash_amount` decimal(10,0) NOT NULL,
  `payment_type` varchar(20) NOT NULL,
  `payment_created_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_details_t`
--

CREATE TABLE IF NOT EXISTS `user_details_t` (
`user_id` int(10) NOT NULL,
  `user_image` varchar(100) NOT NULL,
  `user_fname` varchar(25) NOT NULL,
  `user_lname` varchar(25) NOT NULL,
  `user_age` int(10) NOT NULL,
  `user_sex` varchar(10) NOT NULL,
  `user_email_id` varchar(50) NOT NULL,
  `user_address` varchar(150) NOT NULL,
  `company_id` int(10) NOT NULL,
  `login_user_id` int(10) NOT NULL,
  `user_created_date` varchar(20) NOT NULL,
  `user_created_by` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `user_details_t`
--

INSERT INTO `user_details_t` (`user_id`, `user_image`, `user_fname`, `user_lname`, `user_age`, `user_sex`, `user_email_id`, `user_address`, `company_id`, `login_user_id`, `user_created_date`, `user_created_by`) VALUES
(20, '', 'raj', 'kumar', 26, 'm', 'raj@abc.com', 'ranchi', 0, 23, '2014-09-29 11:00:53', 0),
(18, '', 'raj', 'kumar', 26, 'm', 'raj@abc.com', 'ranchi', 0, 21, '2014-09-29 10:56:56', 0),
(19, '', 'raj', 'kumar', 26, 'm', 'raj@abc.com', 'ranchi', 0, 22, '2014-09-29 10:59:52', 0),
(17, '', 'raj', 'kumar', 26, 'm', 'raj@abc.com', 'ranchi', 0, 20, '2014-09-29 10:55:39', 0),
(12, 'm', 'raj', 'kumar', 26, 'm', 'raj@abc.com', 'ranchi', 0, 15, '2014-09-22 18:14:39', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_details_t`
--
ALTER TABLE `bank_details_t`
 ADD PRIMARY KEY (`bank_id`);

--
-- Indexes for table `cheque_details_t`
--
ALTER TABLE `cheque_details_t`
 ADD PRIMARY KEY (`cheque_id`);

--
-- Indexes for table `company_details_t`
--
ALTER TABLE `company_details_t`
 ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `deposit_details_t`
--
ALTER TABLE `deposit_details_t`
 ADD PRIMARY KEY (`deposit_id`);

--
-- Indexes for table `login_t`
--
ALTER TABLE `login_t`
 ADD PRIMARY KEY (`login_user_id`);

--
-- Indexes for table `net_banking_details_t`
--
ALTER TABLE `net_banking_details_t`
 ADD PRIMARY KEY (`nbd_id`);

--
-- Indexes for table `payment_bank_details_t`
--
ALTER TABLE `payment_bank_details_t`
 ADD PRIMARY KEY (`payment_bank_id`);

--
-- Indexes for table `payment_details_t`
--
ALTER TABLE `payment_details_t`
 ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `user_details_t`
--
ALTER TABLE `user_details_t`
 ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_details_t`
--
ALTER TABLE `bank_details_t`
MODIFY `bank_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `cheque_details_t`
--
ALTER TABLE `cheque_details_t`
MODIFY `cheque_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `company_details_t`
--
ALTER TABLE `company_details_t`
MODIFY `company_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `deposit_details_t`
--
ALTER TABLE `deposit_details_t`
MODIFY `deposit_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `login_t`
--
ALTER TABLE `login_t`
MODIFY `login_user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `net_banking_details_t`
--
ALTER TABLE `net_banking_details_t`
MODIFY `nbd_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_bank_details_t`
--
ALTER TABLE `payment_bank_details_t`
MODIFY `payment_bank_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_details_t`
--
ALTER TABLE `payment_details_t`
MODIFY `payment_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_details_t`
--
ALTER TABLE `user_details_t`
MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
