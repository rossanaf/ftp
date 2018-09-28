-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2018 at 09:36 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scoring`
--

-- --------------------------------------------------------

--
-- Table structure for table `athletes`
--

CREATE TABLE `athletes` (
  `athlete_id` int(10) UNSIGNED NOT NULL,
  `athlete_pos` int(11) NOT NULL DEFAULT '9999',
  `athlete_chip` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `athlete_license` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `athlete_bib` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `athlete_name` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `athlete_firstname` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `athlete_lastname` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `athlete_sex` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `athlete_dob` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `athlete_category` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `athlete_team_id` int(11) NOT NULL,
  `athlete_t0` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '-',
  `athlete_t1` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '-',
  `athlete_t2` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '-',
  `athlete_t3` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '-',
  `athlete_t4` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '-',
  `athlete_t5` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '-',
  `athlete_finishtime` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'chkin',
  `athlete_totaltime` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '-',
  `athlete_race_id` int(11) NOT NULL,
  `athlete_started` int(11) NOT NULL DEFAULT '0',
  `athlete_xtras` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `athlete_arrive_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `athletes`
--

INSERT INTO `athletes` (`athlete_id`, `athlete_pos`, `athlete_chip`, `athlete_license`, `athlete_bib`, `athlete_name`, `athlete_firstname`, `athlete_lastname`, `athlete_sex`, `athlete_dob`, `athlete_category`, `athlete_team_id`, `athlete_t0`, `athlete_t1`, `athlete_t2`, `athlete_t3`, `athlete_t4`, `athlete_t5`, `athlete_finishtime`, `athlete_totaltime`, `athlete_race_id`, `athlete_started`, `athlete_xtras`, `athlete_arrive_order`) VALUES
(1, 9999, 'A1', '', '1', 'John Carter', '', '', 'F', '', '', 5, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 1),
(2, 9999, 'A2', '', '1', 'Abigail Lockhart', '', '', 'M', '', '', 5, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 2),
(3, 9999, 'A3', '', '1', 'Dough Ross', '', '', 'F', '', '', 5, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 3),
(4, 9999, 'A4', '', '1', 'Caroline Hathaway', '', '', 'M', '', '', 5, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 4),
(5, 9999, 'A5', '', '2', 'Ana Felgueiras', '', '', 'F', '', '', 6, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 1),
(6, 9999, 'A6', '', '2', 'João  Rodrigues', '', '', 'M', '', '', 6, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 2),
(7, 9999, 'A7', '', '2', 'Paula Morais', '', '', 'F', '', '', 6, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 3),
(8, 9999, 'A8', '', '2', 'António Costa', '', '', 'M', '', '', 6, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 4),
(9, 9999, 'A9', '', '3', 'Sofia Rigolleto', '', '', 'F', '', '', 7, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 1),
(10, 9999, 'A10', '', '3', 'Leonardo DaVinci', '', '', 'M', '', '', 7, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 2),
(11, 9999, 'A11', '', '3', 'Mara DiCaprio', '', '', 'F', '', '', 7, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 3),
(12, 9999, 'A12', '', '3', 'Pietro Boticcelli', '', '', 'M', '', '', 7, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 4),
(13, 9999, 'A13', '', '4', 'Marie Curie', '', '', 'F', '', '', 8, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 1),
(14, 9999, 'A14', '', '4', 'Louis Pasteur', '', '', 'M', '', '', 8, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 2),
(15, 9999, 'A15', '', '4', 'Anne Victoria', '', '', 'F', '', '', 8, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 3),
(16, 9999, 'A16', '', '4', 'Jeau Paul Gautier', '', '', 'M', '', '', 8, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 4),
(17, 9999, 'A17', '', '5', 'Ruth Spencer', '', '', 'F', '', '', 9, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 1),
(18, 9999, 'A18', '', '5', 'John Lennon', '', '', 'M', '', '', 9, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 2),
(19, 9999, 'A19', '', '5', 'Sarah O\'Connor', '', '', 'F', '', '', 9, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 3),
(20, 9999, 'A20', '', '5', 'Paul  Viral', '', '', 'M', '', '', 9, '-', '-', '-', '-', '-', '-', 'chkin', '-', 1, 0, '', 4);

-- --------------------------------------------------------

--
-- Table structure for table `chips`
--

CREATE TABLE `chips` (
  `Chip` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Pid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cjovem`
--

CREATE TABLE `cjovem` (
  `id` int(11) NOT NULL,
  `clube` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pontos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clubesj`
--

CREATE TABLE `clubesj` (
  `id` int(11) NOT NULL,
  `clube` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `atletas` int(11) NOT NULL,
  `pontos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ftpathletes`
--

CREATE TABLE `ftpathletes` (
  `ftpathlete_id` int(11) NOT NULL,
  `ftpathlete_chip` varbinary(10) DEFAULT NULL,
  `ftpathlete_license` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ftpathlete_name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ftpathlete_bib` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ftpathlete_sex` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ftpathlete_category` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ftpathlete_team_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gunshots`
--

CREATE TABLE `gunshots` (
  `gunshot_id` int(11) NOT NULL,
  `gunshot_benf` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gunshot_benm` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `gunshot_inff` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gunshot_infm` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gunshot_inif` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gunshot_inim` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gunshot_juvf` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gunshot_juvm` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gunshot_race_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live`
--

CREATE TABLE `live` (
  `live_id` int(11) NOT NULL,
  `live_chip` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `live_license` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `live_pos` int(11) DEFAULT '9999',
  `live_firstname` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `live_lastname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `live_sex` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `live_bib` int(11) NOT NULL,
  `live_team_id` int(11) NOT NULL,
  `live_t1` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'time',
  `live_t2` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'time',
  `live_t3` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'time',
  `live_t4` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'time',
  `live_t5` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'time',
  `live_finishtime` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'time',
  `live_race` int(11) NOT NULL,
  `live_started` int(11) NOT NULL DEFAULT '0',
  `live_category` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `live`
--

INSERT INTO `live` (`live_id`, `live_chip`, `live_license`, `live_pos`, `live_firstname`, `live_lastname`, `live_sex`, `live_bib`, `live_team_id`, `live_t1`, `live_t2`, `live_t3`, `live_t4`, `live_t5`, `live_finishtime`, `live_race`, `live_started`, `live_category`) VALUES
(1, 'A1', '1', 9999, 'John', 'Carter', 'F', 1, 5, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(2, 'A2', '2', 9999, 'Abigail', 'Lockhart', 'M', 1, 5, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(3, 'A3', '3', 9999, 'Dough', 'Ross', 'F', 1, 5, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(4, 'A4', '4', 9999, 'Caroline', 'Hathaway', 'M', 1, 5, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(5, 'A5', '1', 9999, 'Ana', 'Felgueiras', 'F', 2, 6, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(6, 'A6', '2', 9999, 'João ', 'Rodrigues', 'M', 2, 6, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(7, 'A7', '3', 9999, 'Paula', 'Morais', 'F', 2, 6, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(8, 'A8', '4', 9999, 'António', 'Costa', 'M', 2, 6, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(9, 'A9', '1', 9999, 'Sofia', 'Rigolleto', 'F', 3, 7, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(10, 'A10', '2', 9999, 'Leonardo', 'DaVinci', 'M', 3, 7, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(11, 'A11', '3', 9999, 'Mara', 'DiCaprio', 'F', 3, 7, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(12, 'A12', '4', 9999, 'Pietro', 'Boticcelli', 'M', 3, 7, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(13, 'A13', '1', 9999, 'Marie', 'Curie', 'F', 4, 8, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(14, 'A14', '2', 9999, 'Louis', 'Pasteur', 'M', 4, 8, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(15, 'A15', '3', 9999, 'Anne', 'Victoria', 'F', 4, 8, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(16, 'A16', '4', 9999, 'Jeau Paul', 'Gautier', 'M', 4, 8, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(17, 'A17', '1', 9999, 'Ruth', 'Spencer', 'F', 5, 9, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(18, 'A18', '2', 9999, 'John', 'Lennon', 'M', 5, 9, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(19, 'A19', '3', 9999, 'Sarah', 'O\'Connor', 'F', 5, 9, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, ''),
(20, 'A20', '4', 9999, 'Paul ', 'Viral', 'M', 5, 9, 'time', 'time', 'time', 'time', 'time', 'time', 1, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `markers`
--

CREATE TABLE `markers` (
  `Marker` varchar(64) DEFAULT NULL,
  `Type` varchar(32) DEFAULT NULL,
  `MarkerTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MilliSecs` int(11) NOT NULL,
  `Location` varchar(20) NOT NULL,
  `Device` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `races`
--

CREATE TABLE `races` (
  `race_id` int(11) NOT NULL,
  `race_name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race_namepdf` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race_ranking` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race_segment1` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race_distsegment1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race_segment2` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ciclismo',
  `race_distsegment2` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race_segment3` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'Corrida',
  `race_distsegment3` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race_date` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race_location` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race_gun_m` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT '-',
  `race_gun_f` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT '-',
  `race_type` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race_relay` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race_live` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `races`
--

INSERT INTO `races` (`race_id`, `race_name`, `race_namepdf`, `race_ranking`, `race_segment1`, `race_distsegment1`, `race_segment2`, `race_distsegment2`, `race_segment3`, `race_distsegment3`, `race_date`, `race_location`, `race_gun_m`, `race_gun_f`, `race_type`, `race_relay`, `race_live`) VALUES
(1, 'Mixed Relay', '', '', '', '', 'Ciclismo', '', 'Corrida', '', '', '', '-', '-', 'iturelay', 'X', '0');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `Pid` int(11) NOT NULL,
  `Lap` int(11) DEFAULT NULL,
  `TimeT1` int(11) DEFAULT NULL,
  `TimeT2` int(11) DEFAULT NULL,
  `TimeT3` int(11) DEFAULT NULL,
  `TimeT4` int(11) DEFAULT NULL,
  `TimeT5` int(11) DEFAULT NULL,
  `Timeckeckin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL,
  `session_userid` int(11) NOT NULL,
  `session_token` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_serial` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_date` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `session_userid`, `session_token`, `session_serial`, `session_date`) VALUES
(438, 3, '2s48r89hdsj94a4tmsbanWQQ@8e8d+2B', '+f4294jR748WAQng8k38AWshsesSdsNs', '1528535089'),
(441, 2, '79tsjWafk3SCr0st3eAsg4Nb2490fQa8', '4SetA84RaQb+8s83js8di4k0Nhm820d7', '1528560295'),
(453, 4, 'Adeag8saQaj888+r2W7Cb432BQ0A79f8', 'hfsa+0CAtsQAsB4agb3g8d@20jSRs8ka', '1528899771'),
(571, 1, 'g2QhCdtg3f8ms8W4a4s02R87W03AAaub', 'Was2rsQug84S9f@dR4ik9+42Cg8AtfNd', '1538039548');

-- --------------------------------------------------------

--
-- Table structure for table `teamresults`
--

CREATE TABLE `teamresults` (
  `teamresult_id` int(11) NOT NULL,
  `teamresult_bib` varchar(6) NOT NULL,
  `teamresult_finishtime` varchar(8) NOT NULL,
  `teamresult_team` varchar(60) NOT NULL,
  `teamresult_license` varchar(10) NOT NULL,
  `teamresult_name` varchar(50) NOT NULL,
  `teamresult_category` varchar(5) NOT NULL,
  `teamresult_validate` int(11) NOT NULL,
  `teamresult_teamtime` varchar(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teamresults`
--

INSERT INTO `teamresults` (`teamresult_id`, `teamresult_bib`, `teamresult_finishtime`, `teamresult_team`, `teamresult_license`, `teamresult_name`, `teamresult_category`, `teamresult_validate`, `teamresult_teamtime`) VALUES
(1, '3101', '02:14:43', 'Sporting Clube de Portugal', '101329', 'Nuno Veríssimo', '35-39', 1, '02:14:43'),
(2, '2516', '02:22:04', 'Sporting Clube de Portugal', '101260', 'Bernardo Guerra', '20-24', 2, '04:36:47'),
(3, '2577', '02:22:58', 'Sporting Clube de Portugal', '100462', 'João Canas', '20-24', 3, '06:59:45'),
(4, '2523', '02:03:45', 'AMICICLO GRÂNDOLA', '100425', 'Hugo Baluga', '20-24', 1, '02:03:45'),
(5, '3637', '02:13:32', 'AMICICLO GRÂNDOLA', '102462', 'Filipe Dias', '30-34', 2, '04:17:17'),
(6, '3197', '02:22:17', 'AMICICLO GRÂNDOLA', '104622', 'Fábio Faustino', '30-34', 3, '06:39:34'),
(7, '3540', '02:29:39', 'REPSOL TRIATLO', '105314', 'Jaime Costa ', '30-34', 1, '02:29:39'),
(8, '3577', '02:30:04', 'REPSOL TRIATLO', '105391', 'Rodrigo Silva', '30-34', 2, '04:59:43'),
(9, '3208', '02:37:17', 'REPSOL TRIATLO', '101396', 'Rui Trovão', '35-39', 3, '07:37:00'),
(10, '3346', '02:05:14', 'Núcleo do Sporting da Golegã', '102545', 'Roberto Parra Fernández', '35-39', 1, '02:05:14'),
(11, '3815', '02:08:14', 'Núcleo do Sporting da Golegã', '102222', 'Ricardo Rosado', '30-34', 2, '04:13:28'),
(12, '3918', '02:26:05', 'Núcleo do Sporting da Golegã', '104177', 'Ricardo Laranjinha', '25-29', 3, '06:39:33'),
(13, '3841', '02:04:31', 'CPArmada', '103961', 'João Bragadeste', '30-34', 1, '02:04:31'),
(14, '3369', '02:10:16', 'CPArmada', '103463', 'Ildefonso Mendonça', '30-34', 2, '04:14:47'),
(15, '3047', '02:16:31', 'CPArmada', '104833', 'Fernando Pereira', '35-39', 3, '06:31:18');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL,
  `team_name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_country` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `team_name`, `team_country`) VALUES
(1, 'Não Federado', NULL),
(2, 'Individual', NULL),
(3, 'Estafeta', NULL),
(4, 'United States of America', NULL),
(5, 'USA', 'United States of America'),
(6, 'POR', 'Portugal'),
(7, 'ITA', 'Italy'),
(8, 'FRA', 'France'),
(9, 'GBR', 'Great Britain');

-- --------------------------------------------------------

--
-- Table structure for table `times`
--

CREATE TABLE `times` (
  `Chip` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ChipTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ChipType` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PC` int(11) DEFAULT NULL,
  `Reader` int(11) DEFAULT NULL,
  `Antenna` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MilliSecs` int(11) NOT NULL,
  `Location` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `LapRaw` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_updated_at` timestamp NULL DEFAULT NULL,
  `user_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_updated_at`, `user_status`) VALUES
(1, 'Rossana', 'rf@rf.rf', 'd4v4l0p', NULL, 1),
(2, 'Prada', 'Prada', 'ftp', NULL, 1),
(3, 'Hugo', 'h@h.h', 'scr2018', NULL, 1),
(4, 'Geral', 'Geral', 'ftp', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `youthraces`
--

CREATE TABLE `youthraces` (
  `youthrace_race_id` int(11) NOT NULL,
  `youthrace_name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_namepdf` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_ranking` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_s1_ben` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_d1_ben` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_s2_ben` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ciclismo',
  `youthrace_d2_ben` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_s3_ben` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'Corrida',
  `youthrace_d3_ben` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_s1_inf` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_d1_inf` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_s2_inf` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ciclismo',
  `youthrace_d2_inf` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_s3_inf` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'Corrida',
  `youthrace_d3_inf` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_s1_ini` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_d1_ini` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_s2_ini` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ciclismo',
  `youthrace_d2_ini` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_s3_ini` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'Corrida',
  `youthrace_d3_ini` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_s1_juv` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_d1_juv` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_s2_juv` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ciclismo',
  `youthrace_d2_juv` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_s3_juv` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'Corrida',
  `youthrace_d3_juv` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_date` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youthrace_location` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `athletes`
--
ALTER TABLE `athletes`
  ADD PRIMARY KEY (`athlete_id`),
  ADD KEY `athlete_id` (`athlete_id`),
  ADD KEY `athlete_bib` (`athlete_bib`);

--
-- Indexes for table `chips`
--
ALTER TABLE `chips`
  ADD PRIMARY KEY (`Chip`);

--
-- Indexes for table `cjovem`
--
ALTER TABLE `cjovem`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clubesj`
--
ALTER TABLE `clubesj`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ftpathletes`
--
ALTER TABLE `ftpathletes`
  ADD PRIMARY KEY (`ftpathlete_id`);

--
-- Indexes for table `gunshots`
--
ALTER TABLE `gunshots`
  ADD PRIMARY KEY (`gunshot_id`);

--
-- Indexes for table `live`
--
ALTER TABLE `live`
  ADD PRIMARY KEY (`live_id`);

--
-- Indexes for table `races`
--
ALTER TABLE `races`
  ADD PRIMARY KEY (`race_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`Pid`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `teamresults`
--
ALTER TABLE `teamresults`
  ADD PRIMARY KEY (`teamresult_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_email_unique` (`user_email`);

--
-- Indexes for table `youthraces`
--
ALTER TABLE `youthraces`
  ADD PRIMARY KEY (`youthrace_race_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `athletes`
--
ALTER TABLE `athletes`
  MODIFY `athlete_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `cjovem`
--
ALTER TABLE `cjovem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clubesj`
--
ALTER TABLE `clubesj`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ftpathletes`
--
ALTER TABLE `ftpathletes`
  MODIFY `ftpathlete_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gunshots`
--
ALTER TABLE `gunshots`
  MODIFY `gunshot_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `live`
--
ALTER TABLE `live`
  MODIFY `live_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=572;

--
-- AUTO_INCREMENT for table `teamresults`
--
ALTER TABLE `teamresults`
  MODIFY `teamresult_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
