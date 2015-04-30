-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2015 at 12:22 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ubi_traffic`
--
USE `ubi_traffic`;

-- --------------------------------------------------------

--
-- Table structure for table `statistic_data`
--

DROP TABLE IF EXISTS `statistic_data`;
CREATE TABLE IF NOT EXISTS `statistic_data` (
  `time` bigint(20) NOT NULL,
  `action_code` varchar(50) NOT NULL,
  `data_1` varchar(100) NOT NULL,
  `data_2` varchar(100) NOT NULL,
  `data_3` varchar(100) NOT NULL,
  `data_4` varchar(100) NOT NULL,
  `faces_count` int(11) NOT NULL,
  `instance_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ubi_instances`
--

DROP TABLE IF EXISTS `ubi_instances`;
CREATE TABLE IF NOT EXISTS `ubi_instances` (
  `instance_id` varchar(50) NOT NULL,
  `instance_lat` varchar(50) NOT NULL,
  `instance_lon` varchar(50) NOT NULL,
  `instance_address` varchar(200) NOT NULL,
  `instance_description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `statistic_data`
--
ALTER TABLE `statistic_data`
 ADD KEY `time` (`time`,`action_code`), ADD KEY `instance_id` (`instance_id`);

--
-- Indexes for table `ubi_instances`
--
ALTER TABLE `ubi_instances`
 ADD PRIMARY KEY (`instance_id`), ADD KEY `instance_id` (`instance_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
