-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2016 at 03:41 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `racingapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `raceheader`
--

CREATE TABLE `raceheader` (
  `ID` int(3) NOT NULL,
  `StartTime` time(2) NOT NULL,
  `FinishTime` time(2) NOT NULL,
  `CurrentTime` time(2) NOT NULL,
  `Runner1` varchar(30) NOT NULL,
  `Runner2` varchar(30) NOT NULL DEFAULT '',
  `Runner3` varchar(30) NOT NULL,
  `Runner4` varchar(30) NOT NULL,
  `Runner5` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `raceheader`
--

INSERT INTO `raceheader` (`ID`, `StartTime`, `FinishTime`, `CurrentTime`, `Runner1`, `Runner2`, `Runner3`, `Runner4`, `Runner5`) VALUES
(1, '14:33:15.00', '00:00:19.18', '14:34:57.00', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `raceheader`
--
ALTER TABLE `raceheader`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `raceheader`
--
ALTER TABLE `raceheader`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
