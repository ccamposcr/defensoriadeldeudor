-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2021 at 05:51 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `defensoriadeldeudor`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrativestatuslist`
--

CREATE TABLE `administrativestatuslist` (
  `id` int(11) NOT NULL,
  `administrativeStatus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `administrativestatuslist`
--

INSERT INTO `administrativestatuslist` (`id`, `administrativeStatus`) VALUES
(1, 'Moroso'),
(2, 'Cancelado'),
(3, 'Al día'),
(4, 'Al día (pendiente)');

-- --------------------------------------------------------

--
-- Table structure for table `judicialstatuslist`
--

CREATE TABLE `judicialstatuslist` (
  `id` int(11) NOT NULL,
  `judicialStatus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `judicialstatuslist`
--

INSERT INTO `judicialstatuslist` (`id`, `judicialStatus`) VALUES
(1, 'Revisión de expediente'),
(2, 'Audiencia'),
(3, 'Señalamiento de remate'),
(4, 'Prevención'),
(5, 'Revocatoria'),
(6, 'Apelación'),
(7, 'Apelación por inadmisión'),
(8, 'Expresión de agravios'),
(9, 'Aclaración y Adición'),
(10, 'Oposición a intereses'),
(11, 'Actividad procesal defectuosa'),
(12, 'Incidente de anulidad de remate'),
(13, 'Recurso de revisión (Sala de casación)'),
(14, 'Incidente de cobro de honorarios'),
(15, 'Varios'),
(16, 'Información al cliente'),
(17, 'Archivo'),
(18, 'Archivo definitivo');

-- --------------------------------------------------------

--
-- Table structure for table `legalcase`
--

CREATE TABLE `legalcase` (
  `id` int(11) NOT NULL,
  `internalCode` varchar(50) NOT NULL,
  `subjectID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `judicialStatusID` int(11) NOT NULL,
  `administrativeStatusID` int(11) NOT NULL,
  `locationID` int(11) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `nextNotification` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `legalcase`
--

INSERT INTO `legalcase` (`id`, `internalCode`, `subjectID`, `userID`, `judicialStatusID`, `administrativeStatusID`, `locationID`, `dateCreated`, `nextNotification`) VALUES
(27, '1', 2, 28, 2, 2, 999, '2021-03-11 03:08:22', '2021-03-31'),
(28, '2', 2, 50, 1, 3, 999, '2021-03-11 03:08:45', '2021-03-31'),
(29, '12', 1, 28, 2, 2, 999, '2021-03-12 04:14:56', '2021-03-31'),
(30, '13', 2, 28, 2, 2, 999, '2021-03-12 04:18:16', '2021-03-31'),
(31, '13', 2, 28, 2, 2, 999, '2021-03-12 04:18:17', '2021-03-31'),
(32, '13', 2, 28, 2, 2, 999, '2021-03-12 04:18:17', '2021-03-31'),
(33, '13', 2, 28, 2, 2, 999, '2021-03-12 04:18:18', '2021-03-31'),
(34, '03299234', 2, 50, 3, 1, 999, '2021-03-12 04:43:18', '2021-03-31'),
(35, '1', 1, 51, 2, 3, 28, '2021-03-12 21:24:15', '2021-03-31'),
(36, '2', 2, 51, 2, 2, 999, '2021-03-12 21:49:36', '2021-03-31'),
(37, '3', 6, 51, 8, 4, 999, '2021-03-13 00:09:59', '2021-03-31');

-- --------------------------------------------------------

--
-- Table structure for table `legalcasenoteshistory`
--

CREATE TABLE `legalcasenoteshistory` (
  `id` int(11) NOT NULL,
  `note` varchar(150) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `legalCaseID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `legalcasenoteshistory`
--

INSERT INTO `legalcasenoteshistory` (`id`, `note`, `date`, `legalCaseID`, `userID`) VALUES
(9, 'una nota', '2021-03-12 04:42:42', 28, 28),
(10, 'otra nota de chris', '2021-03-12 04:43:18', 34, 28),
(11, 'una nueva nota', '2021-03-12 21:24:15', 35, 28),
(12, 'otr anota mia', '2021-03-12 21:49:36', 36, 28),
(13, 'en archivo fisico', '2021-03-13 00:09:59', 37, 28);

-- --------------------------------------------------------

--
-- Table structure for table `rolelist`
--

CREATE TABLE `rolelist` (
  `id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL,
  `privilege` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rolelist`
--

INSERT INTO `rolelist` (`id`, `role`, `privilege`) VALUES
(1, 'Administrador', 1),
(2, 'Legal', 2),
(3, 'Financiero', 3),
(4, 'Servicio Cliente', 4),
(5, 'Cliente', 99);

-- --------------------------------------------------------

--
-- Table structure for table `subjectlist`
--

CREATE TABLE `subjectlist` (
  `id` int(11) NOT NULL,
  `subject` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subjectlist`
--

INSERT INTO `subjectlist` (`id`, `subject`) VALUES
(1, 'Monitorio'),
(2, 'Hipotecario'),
(3, 'Notariado'),
(4, 'Contencioso Administrativo'),
(5, 'Cobro Efectivo'),
(6, 'Penal'),
(7, 'Familia'),
(8, 'Laboral');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `personalID` varchar(150) NOT NULL,
  `name` varchar(150) NOT NULL,
  `lastName1` varchar(150) NOT NULL,
  `lastName2` varchar(150) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(150) NOT NULL,
  `roleID` int(11) NOT NULL,
  `verificationKey` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `personalID`, `name`, `lastName1`, `lastName2`, `status`, `dateCreated`, `phone`, `email`, `password`, `address`, `roleID`, `verificationKey`) VALUES
(28, '113100938', 'Christian', 'Campos', 'Olivares', 0, '2021-01-20 04:37:29', '83180160', 'ccamposcr@gmail.com', '$2y$10$noVtfVJq7ZuRvY8qQKDQ8OYKB6bxGzsY8mDvrsyGVd79xfjMo4xdC', '125m al oeste y 25 al sur de la Cruz Roja de Vazquez de Coronado', 1, 'f1dd491af6c0ef9337d9a77f5618bcf4'),
(50, '801020133', 'Carolina', 'Borge', 'Espinoza', 1, '2021-03-11 02:19:39', '83310361', 'carobe18685@gmail.com', '', '125m al oeste y 25 al sur de la Cruz Roja de Vazquez de Coronado', 2, ''),
(51, '302340045', 'Martin', 'Campos', 'Arias', 1, '2021-03-12 04:59:21', '83180160', 'martincamposarias@gmail.com', '', '125m al oeste y 25 al sur de la Cruz Roja de Vazquez de Coronado', 99, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrativestatuslist`
--
ALTER TABLE `administrativestatuslist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `judicialstatuslist`
--
ALTER TABLE `judicialstatuslist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `legalcase`
--
ALTER TABLE `legalcase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `legalcasenoteshistory`
--
ALTER TABLE `legalcasenoteshistory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rolelist`
--
ALTER TABLE `rolelist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjectlist`
--
ALTER TABLE `subjectlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrativestatuslist`
--
ALTER TABLE `administrativestatuslist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `judicialstatuslist`
--
ALTER TABLE `judicialstatuslist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `legalcase`
--
ALTER TABLE `legalcase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `legalcasenoteshistory`
--
ALTER TABLE `legalcasenoteshistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `rolelist`
--
ALTER TABLE `rolelist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subjectlist`
--
ALTER TABLE `subjectlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
