-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2026 at 11:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dopas`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `alertId` int(255) NOT NULL,
  `mukimId` int(5) NOT NULL,
  `cityName` varchar(255) DEFAULT NULL,
  `Week` int(2) DEFAULT NULL,
  `Year` int(4) DEFAULT NULL,
  `riskLevel` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alerts`
--

-- --------------------------------------------------------

--
-- Table structure for table `climatedata`
--

CREATE TABLE `climatedata` (
  `climateId` int(255) NOT NULL,
  `mukimId` int(5) NOT NULL,
  `Week` int(2) DEFAULT NULL,
  `Year` int(4) DEFAULT NULL,
  `Temperature` decimal(4,1) DEFAULT NULL,
  `Rainfall` decimal(4,1) DEFAULT NULL,
  `Humidity` decimal(4,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `climatedata`
--

-- --------------------------------------------------------

--
-- Table structure for table `denguerecords`
--

CREATE TABLE `denguerecords` (
  `outbreakId` int(10) NOT NULL,
  `mukimId` int(5) NOT NULL,
  `dengueCases` int(255) DEFAULT NULL,
  `Week` int(2) DEFAULT NULL,
  `Year` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `denguerecords`
--

-- --------------------------------------------------------

--
-- Table structure for table `locationrecords`
--

CREATE TABLE `locationrecords` (
  `locationId` int(10) NOT NULL,
  `mukimId` int(5) NOT NULL,
  `cityName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locationrecords`
--

-- --------------------------------------------------------

--
-- Table structure for table `mukim`
--

CREATE TABLE `mukim` (
  `mukimId` int(5) NOT NULL,
  `mukimName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mukim`
--

-- --------------------------------------------------------

--
-- Table structure for table `predictionresults`
--

CREATE TABLE `predictionresults` (
  `predictionId` int(20) NOT NULL,
  `mukimId` int(5) NOT NULL,
  `Week` int(2) DEFAULT NULL,
  `Year` int(4) DEFAULT NULL,
  `predictedCases` int(255) DEFAULT NULL,
  `riskLevel` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `predictionresults`
--

-- --------------------------------------------------------

--
-- Table structure for table `userdetails`
--

CREATE TABLE `userdetails` (
  `userId` int(10) NOT NULL,
  `mukimId` int(5) NOT NULL,
  `locationId` int(10) NOT NULL,
  `fullName` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Postcode` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userdetails`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alertId`),
  ADD KEY `alerts_mukimId` (`mukimId`);

--
-- Indexes for table `climatedata`
--
ALTER TABLE `climatedata`
  ADD PRIMARY KEY (`climateId`),
  ADD KEY `climate_mukimId` (`mukimId`);

--
-- Indexes for table `denguerecords`
--
ALTER TABLE `denguerecords`
  ADD PRIMARY KEY (`outbreakId`),
  ADD KEY `dengue_mukimId` (`mukimId`);

--
-- Indexes for table `locationrecords`
--
ALTER TABLE `locationrecords`
  ADD PRIMARY KEY (`locationId`),
  ADD KEY `location_mukim` (`mukimId`);

--
-- Indexes for table `mukim`
--
ALTER TABLE `mukim`
  ADD PRIMARY KEY (`mukimId`);

--
-- Indexes for table `predictionresults`
--
ALTER TABLE `predictionresults`
  ADD PRIMARY KEY (`predictionId`),
  ADD KEY `prediction_mukimId` (`mukimId`);

--
-- Indexes for table `userdetails`
--
ALTER TABLE `userdetails`
  ADD PRIMARY KEY (`userId`),
  ADD KEY `user_mukim` (`mukimId`),
  ADD KEY `user_locationId` (`locationId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alertId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `climatedata`
--
ALTER TABLE `climatedata`
  MODIFY `climateId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=325;

--
-- AUTO_INCREMENT for table `denguerecords`
--
ALTER TABLE `denguerecords`
  MODIFY `outbreakId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `locationrecords`
--
ALTER TABLE `locationrecords`
  MODIFY `locationId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `predictionresults`
--
ALTER TABLE `predictionresults`
  MODIFY `predictionId` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `userdetails`
--
ALTER TABLE `userdetails`
  MODIFY `userId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_mukimId` FOREIGN KEY (`mukimId`) REFERENCES `mukim` (`mukimId`) ON UPDATE CASCADE;

--
-- Constraints for table `climatedata`
--
ALTER TABLE `climatedata`
  ADD CONSTRAINT `climate_mukimId` FOREIGN KEY (`mukimId`) REFERENCES `mukim` (`mukimId`) ON UPDATE CASCADE;

--
-- Constraints for table `denguerecords`
--
ALTER TABLE `denguerecords`
  ADD CONSTRAINT `dengue_mukimId` FOREIGN KEY (`mukimId`) REFERENCES `mukim` (`mukimId`) ON UPDATE CASCADE;

--
-- Constraints for table `locationrecords`
--
ALTER TABLE `locationrecords`
  ADD CONSTRAINT `location_mukim` FOREIGN KEY (`mukimId`) REFERENCES `mukim` (`mukimId`) ON UPDATE CASCADE;

--
-- Constraints for table `predictionresults`
--
ALTER TABLE `predictionresults`
  ADD CONSTRAINT `prediction_mukimId` FOREIGN KEY (`mukimId`) REFERENCES `mukim` (`mukimId`) ON UPDATE CASCADE;

--
-- Constraints for table `userdetails`
--
ALTER TABLE `userdetails`
  ADD CONSTRAINT `user_locationId` FOREIGN KEY (`locationId`) REFERENCES `locationrecords` (`locationId`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_mukim` FOREIGN KEY (`mukimId`) REFERENCES `mukim` (`mukimId`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
