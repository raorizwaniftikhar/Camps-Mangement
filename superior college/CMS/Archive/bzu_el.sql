-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 27, 2009 at 10:28 AM
-- Server version: 5.1.33
-- PHP Version: 5.2.9-2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bzu_el`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE IF NOT EXISTS `assignments` (
  `AID` int(200) NOT NULL AUTO_INCREMENT,
  `Title` varchar(100) NOT NULL DEFAULT 'NONE',
  `Type` varchar(2) NOT NULL,
  `Marks` int(40) DEFAULT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `File` varchar(200) DEFAULT NULL,
  `LID` int(30) NOT NULL,
  `Against` int(30) NOT NULL DEFAULT '0',
  `SID` int(30) DEFAULT NULL,
  PRIMARY KEY (`AID`),
  KEY `AID` (`AID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`AID`, `Title`, `Type`, `Marks`, `Description`, `File`, `LID`, `Against`, `SID`) VALUES
(1, '213123', 'G', 1432342, '2314321', 'courses/1/1.jpg', 1, 0, NULL),
(2, '213123', 'G', 1432342, '2314321', 'courses/1/3.jpg', 1, 0, NULL),
(3, 'NONE', 'T', NULL, NULL, 'courses//SIMD.pdf', 0, 1, 0),
(4, 'NONE', 'T', NULL, NULL, 'courses/1/SIMD.pdf', 0, 1, 0),
(5, 'NONE', 'T', NULL, NULL, 'courses/1/loveaajkal03.rm', 0, 1, 0),
(7, 'Somehirtn', 'G', 100, 'lkjllklk', 'courses/1/LivingwHIVBanner1s.jpg', 1, 0, NULL),
(8, 'Somehirtn', 'G', 100, 'lkjllklk', 'files/courses/1/LivingwHIVBanner1s.jpg', 1, 0, NULL),
(9, 'NONE', 'T', NULL, NULL, 'files/courses/1/LivingwHIVBanner1s.jpg', 0, 8, 0),
(10, 'NONE', 'T', NULL, NULL, 'files/courses/1/LivingwHIVBanners.jpg', 0, 1, 0),
(11, 'NONE', 'T', NULL, NULL, 'files/courses/1/shahrukh-khan-house-palace-14.jpg', 0, 8, 4);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE IF NOT EXISTS `attendance` (
  `ATID` int(30) NOT NULL AUTO_INCREMENT,
  `SID` int(30) NOT NULL,
  `LRID` int(30) NOT NULL,
  `Daate` varchar(29) NOT NULL,
  `Present` varchar(20) NOT NULL,
  PRIMARY KEY (`ATID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`ATID`, `SID`, `LRID`, `Daate`, `Present`) VALUES
(2, 4, 4, '2009-06-12', 'YES'),
(14, 7, 1, '0000-00-00', 'Y'),
(15, 4, 1, '0000-00-00', 'Y'),
(16, 6, 1, '0000-00-00', 'Y'),
(17, 7, 1, 'July 27 , 2009', 'Y'),
(18, 4, 1, 'July 27 , 2009', 'Y'),
(19, 6, 1, 'July 27 , 2009', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `CID` varchar(30) NOT NULL,
  `Session` varchar(30) NOT NULL,
  `Semester` varchar(30) NOT NULL,
  `Subjects` varchar(30) NOT NULL,
  PRIMARY KEY (`CID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`CID`, `Session`, `Semester`, `Subjects`) VALUES
('BBIT07', '2005-09', '4th', '6');

-- --------------------------------------------------------

--
-- Table structure for table `lectures`
--

CREATE TABLE IF NOT EXISTS `lectures` (
  `LID` int(30) NOT NULL AUTO_INCREMENT,
  `CID` varchar(30) NOT NULL,
  `TID` int(30) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Continue` varchar(30) NOT NULL,
  `Type` varchar(30) NOT NULL,
  `Start` date NOT NULL,
  `End` date NOT NULL,
  `Time` varchar(10) NOT NULL,
  `Room` varchar(10) NOT NULL,
  `Duration` varchar(30) NOT NULL,
  `About` varchar(500) NOT NULL,
  `Announce` varchar(250) NOT NULL,
  PRIMARY KEY (`LID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `lectures`
--

INSERT INTO `lectures` (`LID`, `CID`, `TID`, `Name`, `Continue`, `Type`, `Start`, `End`, `Time`, `Room`, `Duration`, `About`, `Announce`) VALUES
(1, 'BBIT07', 1, 'Neuclear Theory', '1', 'Regular', '2009-06-12', '2009-06-20', '02:30:00', '214', '2', 'The liquid drop model of the nucleus and how to apply the semi-empirical     mass formula to radioactive decays and fission and fusion.The definition of cross section and how to measure it experimentally and     calculate it theoretically for some simple cases.Simple theories of radioactive decays.Qualitative understanding of particle interactions in matter and how these     are used in particle detectors.', 'iuhykjlhljkhjkhklj'),
(2, 'BIT05', 2, 'International Relations', '1', 'Regular', '2009-06-12', '2009-06-27', '02:30:00', '777', '2', 'w to measure it experimentally and     calculate it theoretically for some simple cases.', ''),
(3, 'BIT05', 3, 'Multimedia Programming', '1', 'Regular', '2009-06-12', '2009-06-30', '20:00:00', '424', '1', 'w to measure it experimentally and     calculate it theoretically for some simple cases.', ''),
(4, 'BIT05', 4, 'Data Base', '1', 'Regular', '2009-06-12', '2009-06-13', '14:43:00', '445', '3', 'w to measure it experimentally and     calculate it theoretically for some simple cases.', ''),
(5, 'BIT05', 5, 'Something Somthing', '1', 'Regular', '1910-02-05', '1938-03-26', '02:30:00', '234', '2', 'w to measure it experimentally and     calculate it theoretically for some simple cases.', '');

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE IF NOT EXISTS `marks` (
  `MID` int(30) NOT NULL AUTO_INCREMENT,
  `SID` int(30) NOT NULL,
  `LID` int(30) NOT NULL,
  `SESSINAL` int(30) DEFAULT NULL,
  `MIDTERM` int(30) DEFAULT NULL,
  `FINAL` int(30) DEFAULT NULL,
  PRIMARY KEY (`MID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`MID`, `SID`, `LID`, `SESSINAL`, `MIDTERM`, `FINAL`) VALUES
(1, 4, 1, 17, 20, 20);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `SID` int(30) NOT NULL AUTO_INCREMENT,
  `CID` varchar(30) NOT NULL,
  `RNUM` varchar(30) NOT NULL,
  `Full_Name` varchar(30) NOT NULL,
  `UID` varchar(30) NOT NULL,
  `Status` varchar(30) NOT NULL,
  `L1` varchar(30) NOT NULL,
  `L2` varchar(30) NOT NULL,
  `L3` varchar(30) NOT NULL,
  `L4` varchar(30) NOT NULL,
  `L5` varchar(30) NOT NULL,
  `L6` varchar(30) NOT NULL,
  `L7` varchar(30) NOT NULL,
  PRIMARY KEY (`SID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`SID`, `CID`, `RNUM`, `Full_Name`, `UID`, `Status`, `L1`, `L2`, `L3`, `L4`, `L5`, `L6`, `L7`) VALUES
(4, 'BBIT07', '66', 'Faizan Tahir', 'fiz', 'OK', '2', '1', '5', '4', '3', '0', '0'),
(6, 'BBIT05', '9', 'Someone', '9', '9', '9', '9', '9', '9', '9', '9', '9'),
(7, 'BBIT05', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE IF NOT EXISTS `teachers` (
  `TID` int(30) NOT NULL AUTO_INCREMENT,
  `Full_Name` varchar(30) NOT NULL,
  `UID` varchar(30) NOT NULL,
  `Designation` varchar(50) NOT NULL,
  `Phone` varchar(30) NOT NULL,
  `Address` varchar(50) NOT NULL,
  PRIMARY KEY (`TID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`TID`, `Full_Name`, `UID`, `Designation`, `Phone`, `Address`) VALUES
(1, 'Albert Einstien', 'tisman', 'Scientist', '03000300300', 'Somewhere'),
(2, 'Barak Husain Obama', 'fdsa', 'Prisident Of the United States', '111-WHITE-HOUSE', 'WHITE House Washington'),
(3, 'Micheal Jackson', 'qq', 'Singer and Dancer', '03333333333', 'USA'),
(4, 'William Strker', 'wil', 'Mutant Killer', '0900293009', 'The Island'),
(5, 'Superman', 'superman', 'Super Hero', '029130912039', 'Manhaton');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(30) NOT NULL AUTO_INCREMENT,
  `UID` varchar(30) NOT NULL,
  `PWD` varchar(30) NOT NULL,
  `Type` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `UID`, `PWD`, `Type`) VALUES
(3, 'admin', 'admin', 0),
(12, 'tisman', 'tisman', 2),
(15, 'fiz', 'fiz', 1),
(24, 'kashi', 'kashi', 1);
