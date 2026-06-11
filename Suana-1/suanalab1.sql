-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2026 at 11:25 AM
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
-- Database: `suanalab1`
--

-- --------------------------------------------------------

--
-- Table structure for table `s_student`
--

CREATE TABLE `s_student` (
  `idname` int(11) NOT NULL,
  `Sname` varchar(100) NOT NULL,
  `Scourse` varchar(50) NOT NULL,
  `Syear` int(11) NOT NULL,
  `Screated` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `s_student`
--

INSERT INTO `s_student` (`idname`, `Sname`, `Scourse`, `Syear`, `Screated`, `email`, `phone`, `address`) VALUES
(1, 'Ginny Natnicha', 'BSIT', 2, '2026-06-10 09:27:06', NULL, NULL, NULL),
(2, 'Jayna Angelina Stevens', 'BSCS', 2, '2026-06-10 09:27:06', '', '', ''),
(3, 'Liamxian Danvers', 'BSIT', 1, '2026-06-10 09:27:06', NULL, NULL, NULL),
(4, 'Sunny Danvers', 'BSIT', 3, '2026-06-10 09:27:06', NULL, NULL, NULL),
(5, 'Carlos Lopez', 'BSCS', 1, '2026-06-10 09:27:06', NULL, NULL, NULL),
(6, 'Sofia Reyes', 'BSIT', 2, '2026-06-10 10:14:28', NULL, NULL, NULL),
(7, 'Marco Antonio', 'BSCS', 3, '2026-06-10 10:14:28', NULL, NULL, NULL),
(8, 'Isabella Cruz', 'BSIT', 1, '2026-06-10 10:14:28', NULL, NULL, NULL),
(9, 'Billie Eilish', 'BSCS', 4, '2026-06-10 10:14:28', NULL, NULL, NULL),
(10, 'Selena Gomez', 'BSIT', 2, '2026-06-10 10:14:28', NULL, NULL, NULL),
(21, 'Lixie', 'BSCS', 1, '2026-06-10 11:06:52', 'wabbah@gmail.com', '22211333', 'brookiestrt');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `s_student`
--
ALTER TABLE `s_student`
  ADD PRIMARY KEY (`idname`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `s_student`
--
ALTER TABLE `s_student`
  MODIFY `idname` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
