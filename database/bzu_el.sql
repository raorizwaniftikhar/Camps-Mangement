-- phpMyAdmin SQL Dump
-- version 2.11.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 10, 2009 at 12:38 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `bzu_el`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `AID` int(200) NOT NULL auto_increment,
  `Title` varchar(100) NOT NULL default 'NONE',
  `Type` varchar(2) NOT NULL,
  `Marks` int(40) default NULL,
  `Description` varchar(500) default NULL,
  `File` varchar(200) default NULL,
  `LID` int(30) NOT NULL,
  `Against` int(30) NOT NULL default '0',
  `SID` int(30) default NULL,
  PRIMARY KEY  (`AID`),
  KEY `AID` (`AID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`AID`, `Title`, `Type`, `Marks`, `Description`, `File`, `LID`, `Against`, `SID`) VALUES
(1, 'new hardwares', 'G', 3, 'find the detail of new types of computor hardwares', 'files/courses/7/bzu_el.sql', 7, 0, NULL),
(2, 'NONE', 'T', NULL, NULL, 'files/courses/7/Report-format.doc', 0, 1, 5),
(3, 'NONE', 'T', NULL, NULL, 'files/courses/7/Report-format.doc', 0, 1, 5),
(4, 'Types of hardwares', 'G', 3, 'give detail about the types of hardware', 'files/courses/8/1_84_0.jpg', 8, 0, NULL),
(5, 'types of operating software', 'G', 3, 'detail about the types of operating sotfwares', 'files/courses/8/1_6418_0.jpg', 8, 0, NULL),
(6, 'NONE', 'T', NULL, NULL, 'files/courses/8/refer.doc', 0, 4, 16),
(7, 'types of application softwares', 'G', 3, 'give the detail on the types of\r\napplication softwares ', 'files/courses/14/types of as.doc', 14, 0, NULL),
(8, 'NONE', 'T', NULL, NULL, 'files/courses/14/assignment.doc', 0, 7, 9);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `ATID` int(30) NOT NULL auto_increment,
  `SID` int(30) NOT NULL,
  `LRID` int(30) NOT NULL,
  `Daate` varchar(29) NOT NULL,
  `Present` varchar(20) NOT NULL,
  PRIMARY KEY  (`ATID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Dumping data for table `attendance`
--


-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `CID` varchar(30) NOT NULL,
  `Session` varchar(30) NOT NULL,
  `Semester` varchar(30) NOT NULL,
  `Subjects` varchar(30) NOT NULL,
  PRIMARY KEY  (`CID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`CID`, `Session`, `Semester`, `Subjects`) VALUES
('BBAIT05', '2005-09', '8', '5'),
('BBAIT06', '2006-10', '6', '5'),
('BBAIT07', '2007-11', '4', '5'),
('BBAIT08', '2008-12', '2', '5'),
('BBA05', '2005-09', '8', '5'),
('BBA06', '2006-10', '6', '5'),
('BBA07', '2007-11', '4', '5'),
('BBA08', '2008-12', '2', '5');

-- --------------------------------------------------------

--
-- Table structure for table `lectures`
--

CREATE TABLE `lectures` (
  `LID` int(30) NOT NULL auto_increment,
  `CID` varchar(30) NOT NULL,
  `TID` int(30) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Continue` varchar(30) NOT NULL default '1',
  `Type` varchar(30) NOT NULL,
  `Start` date NOT NULL,
  `End` date NOT NULL,
  `Time` varchar(10) NOT NULL,
  `Room` varchar(10) NOT NULL,
  `Duration` varchar(30) NOT NULL,
  `About` varchar(500) NOT NULL,
  `Announce` varchar(250) NOT NULL,
  PRIMARY KEY  (`LID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `lectures`
--

INSERT INTO `lectures` (`LID`, `CID`, `TID`, `Name`, `Continue`, `Type`, `Start`, `End`, `Time`, `Room`, `Duration`, `About`, `Announce`) VALUES
(13, 'BBAIT06', 4, 'SAD', 'Y', 'R', '2009-01-01', '2009-05-25', '8:00am', '3', '01:15', 'about the system analysis and design', ''),
(12, 'BBAIT05', 9, 'International business', 'Y', 'R', '2009-01-01', '2009-05-25', '04:00pm', '2', '01:15', 'about the latest trend of business in the international market', ''),
(11, 'BBAIT05', 3, 'system project', 'Y', 'R', '2009-06-01', '2009-08-10', '02:00pm', 'lab', '01:15', 'about the system project', ''),
(10, 'BBAIT05', 7, 'business research method', 'Y', 'R', '2009-01-01', '2009-05-25', '12:00pm', '2', '01:15', 'about the business research methods and techniques', ''),
(8, 'BBAIT05', 5, 'Latest Trend in IT', 'Y', 'R', '2009-01-01', '2009-05-25', '08:00am', '2', '01:15', 'about the latest trend in the information technology', 'There will be a surprise quiz on the next coming lecture'),
(9, 'BBAIT05', 11, 'operational management', 'Y', 'R', '2009-01-01', '2009-05-25', '10:00am', '2', '01:15', 'about the operational strategies of the organization', ''),
(14, 'BBAIT06', 5, 'MIS', 'Y', 'R', '2009-01-01', '2009-05-25', '10:00am', '3', '01:15', 'about the management information system', ''),
(15, 'BBAIT06', 8, 'consumer behavior', 'Y', 'R', '2009-01-01', '2009-05-25', '12:00pm', '3', '01:15', 'about the consumer demand and want study', ''),
(16, 'BBAIT06', 9, 'marketing', 'Y', 'R', '2009-01-01', '2009-05-25', '02:00pm', '3', '01:15', 'about the principles of marketing', ''),
(17, 'BBAIT06', 12, 'hrm', 'Y', 'R', '2009-01-01', '2009-05-25', '04:00', '3', '01:15', 'about the human relationship management', ''),
(18, 'BBAIT07', 4, 'Software engg', 'Y', 'R', '2009-01-01', '2009-05-25', '08:00am', '4', '01:15', 'about the softwares', ''),
(19, 'BBAIT07', 8, 'organizational behavior', 'Y', 'R', '2009-01-01', '2009-05-25', '10:00am', '4', '01:15', 'about the behavior and norms, values of an organization', ''),
(20, 'BBAIT07', 10, 'marketing management', 'Y', 'R', '2009-01-01', '2009-05-25', '12:00pm', '4', '01:00', 'about the marketing and managenment', ''),
(21, 'BBAIT07', 6, 'HTML', 'Y', 'R', '2009-01-01', '2009-05-25', '02:00pm', '4', '01:15', 'about hoe to develop a web site', ''),
(22, 'BBAIT07', 12, 'financail management', 'Y', 'R', '2009-01-01', '2009-05-25', '04:00pm', '4', '01:15', 'about the finance', '');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `SID` int(30) NOT NULL auto_increment,
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
  PRIMARY KEY  (`SID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`SID`, `CID`, `RNUM`, `Full_Name`, `UID`, `Status`, `L1`, `L2`, `L3`, `L4`, `L5`, `L6`, `L7`) VALUES
(15, 'BBAIT05', '05', 'irfan haider', 'bt0505', 'OK', '12', '11', '10', '8', '9', '0', '0'),
(14, 'BBAIT05', '04', 'zahid attique', 'bt0504', 'OK', '12', '11', '10', '8', '9', '0', '0'),
(13, 'BBAIT05', '03', 'omer zia', 'bt0503', 'OK', '12', '11', '10', '8', '9', '0', '0'),
(12, 'BBAIT05', '02', 'faryal haider', 'bt0502', 'OK', '12', '11', '10', '8', '9', '0', '0'),
(9, 'BBAIT06', '1', 'asif rehman', 'bt0601', 'OK', '13', '14', '15', '16', '17', '0', '0'),
(10, 'BBAIT06', '02', 'ahmed raza', 'bt0602', 'OK', '13', '14', '15', '16', '17', '0', '0'),
(11, 'BBAIT06', '03', 'shaszad ahmed', 'bt0603', 'OK', '13', '14', '15', '16', '17', '0', '0'),
(16, 'BBAIT05', '06', 'kashif ashfaq', 'bt0506', 'OK', '12', '11', '10', '8', '9', '0', '0'),
(17, 'BBAIT07', '01', 'khaleeq-uz-zaman', 'bt0701', 'OK', '18', '19', '20', '21', '22', '0', '0'),
(18, 'BBAIT07', '02', 'sara khan', 'bt0702', 'OK', '18', '19', '20', '21', '22', '0', '0'),
(19, 'BBAIT07', '03', 'zain rehman', 'bt0703', 'OK', '18', '19', '20', '21', '22', '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `TID` int(30) NOT NULL auto_increment,
  `Full_Name` varchar(30) NOT NULL,
  `UID` varchar(30) NOT NULL,
  `Designation` varchar(50) NOT NULL,
  `Phone` varchar(30) NOT NULL,
  `Address` varchar(50) NOT NULL,
  PRIMARY KEY  (`TID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`TID`, `Full_Name`, `UID`, `Designation`, `Phone`, `Address`) VALUES
(4, 'sir.zubair ahmed', 'sir.zubair', 'lecturer', '0333-9988776', 'gulghast multan'),
(3, 'sir.Mushtaq khan', 'sir.mushtaq', 'lecturer', '0333-6677889', 'gulghast multan'),
(5, 'sir.Farhan azmat mir', 'sir.farhan', 'lecturer', '0300-1122334', 'cantt multan'),
(6, 'sir.Liaqat javed', 'sir.liaqatjaved', 'lecturer', '0345-2233445', 'mumtazabad multan'),
(7, 'sir.Raza ali', 'sir.razaali', 'lecturer', '0334-6655447', 'shahlimar multan'),
(8, 'sir.Haroon hafiz', 'sir.haroon', 'lecturer', '0334-3344551', 'b.z.u multan'),
(9, 'sir.Khurram shahzad', 'sir.khurram', 'lecturer', '0321-2233446', 'gol bag multan'),
(10, 'sir.Noman abbasi', 'sir.nomanabbasi', 'lecturer', '0321-6547651', 'staff colony b.z.u multan'),
(11, 'sir.Muhammad hayat awan', 'sir.hayatawan', 'professor', '0300-4563452', 'staff colony b.z.u multan'),
(12, 'sir.Rzwan ahmed', 'sir.rizwan', 'lecturer', '0345-9876543', 'zakariya town multan');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(30) NOT NULL auto_increment,
  `UID` varchar(30) NOT NULL,
  `PWD` varchar(30) NOT NULL,
  `Type` int(11) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=59 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `UID`, `PWD`, `Type`) VALUES
(3, 'admin', 'admin', 0),
(30, 'sir.zubair', '123', 2),
(29, 'sir.mushtaq', '123', 2),
(31, 'sir.farhan', '123', 2),
(32, 'sir.liaqatjaved', '123', 2),
(33, 'sir.razaali', '123', 2),
(34, 'sir.haroon', '123', 2),
(35, 'sir.khurram', '123', 2),
(36, 'sir.nomanabbasi', '123', 2),
(37, 'sir.hayatawan', '123', 2),
(38, 'sir.rizwan', '123', 2),
(53, 'bt0505', '123', 1),
(52, 'bt0504', '123', 1),
(50, 'bt0502', '123', 1),
(51, 'bt0503', '123', 1),
(46, 'bt0601', '123', 1),
(47, 'bt0602', '123', 1),
(48, 'bt0603', '123', 1),
(54, 'bt0506', '123', 1),
(55, 'bt0701', '123', 1),
(57, 'bt0702', '123', 1),
(58, 'bt0703', '123', 1);
