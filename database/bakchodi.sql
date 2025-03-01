-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2025 at 01:50 AM
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
-- Database: `bakchodi`
--

-- --------------------------------------------------------

--
-- Table structure for table `bakchod`
--

CREATE TABLE `bakchod` (
  `_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(256) NOT NULL,
  `tag` varchar(100) NOT NULL,
  `quote` text NOT NULL,
  `image` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `word`
--

CREATE TABLE `word` (
  `_id` int(11) NOT NULL,
  `bakchod` varchar(100) NOT NULL,
  `dateNow` varchar(10) NOT NULL,
  `w1` varchar(50) NOT NULL,
  `s1` text NOT NULL,
  `w2` varchar(50) NOT NULL,
  `s2` text NOT NULL,
  `w3` varchar(50) NOT NULL,
  `s3` text NOT NULL,
  `w4` varchar(50) NOT NULL,
  `s4` text NOT NULL,
  `w5` varchar(50) NOT NULL,
  `s5` text NOT NULL,
  `m1` varchar(100) NOT NULL,
  `m2` varchar(100) NOT NULL,
  `m3` varchar(100) NOT NULL,
  `m4` varchar(100) NOT NULL,
  `m5` varchar(100) NOT NULL,
  `addedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bakchod`
--
ALTER TABLE `bakchod`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `word`
--
ALTER TABLE `word`
  ADD PRIMARY KEY (`_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bakchod`
--
ALTER TABLE `bakchod`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `word`
--
ALTER TABLE `word`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
