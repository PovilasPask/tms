-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 09, 2019 at 02:12 AM
-- Server version: 5.7.19
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tms`
--

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`) VALUES
(1, 'Lietuva'),
(2, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
CREATE TABLE IF NOT EXISTS `migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migration_versions`
--

INSERT INTO `migration_versions` (`version`) VALUES
('20181229175924'),
('20190101235130'),
('20190102123030'),
('20190104105938'),
('20190106001909'),
('20190106002357'),
('20190106124508'),
('20190106132331'),
('20190108143825'),
('20190108145220'),
('20190108165300'),
('20190108165459');

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

DROP TABLE IF EXISTS `player`;
CREATE TABLE IF NOT EXISTS `player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `goal_count` int(11) NOT NULL,
  `b_date` datetime NOT NULL,
  `number` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_98197A65296CD8AE` (`team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`id`, `team_id`, `name`, `goal_count`, `b_date`, `number`) VALUES
(8, 16, 'Andrius Antanavičius', 0, '1995-05-18 22:47:23', 1),
(9, 16, 'Benas Butkus', 0, '1997-02-26 22:48:55', 3),
(10, 16, 'Carlo Piccini', 0, '1992-12-31 22:51:52', 5),
(11, 16, 'Darius Dukauskas', 0, '1982-04-01 22:53:29', 7),
(12, 16, 'Edgaras Einikis', 0, '1990-01-01 22:55:38', 8),
(13, 16, 'Feliksas Filipavičius', 0, '1999-09-09 22:59:34', 9);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE IF NOT EXISTS `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `venue` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacts` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `region` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tournament_id` int(11) DEFAULT NULL,
  `state` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C4E0A61FA76ED395` (`user_id`),
  KEY `IDX_C4E0A61FF92F3E70` (`country_id`),
  KEY `IDX_C4E0A61F33D1A3E7` (`tournament_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `user_id`, `name`, `venue`, `contacts`, `country_id`, `region`, `tournament_id`, `state`) VALUES
(16, 2, 'Kauno Spyris', 'Rožių g., 15, Kaunas', 'Komandos kapitonas:\r\nPertas Petrauskas: +3705566556', 1, 'Kaunas', NULL, 0),
(17, 2, 'Kauno Spartak', 'Lelijų g., 45, Kaunas', 'Nėra', 1, 'Kaunas', NULL, 0),
(18, 2, 'Kauno Studentai', 'Tulpių g., 10, Kaunas', 'Nėra', 1, 'Kaunas', NULL, 0),
(19, 2, 'Kauno Sakalai', 'Jezminų g., 20, Kaunas', 'Nėra', 1, 'Kaunas', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tournament`
--

DROP TABLE IF EXISTS `tournament`;
CREATE TABLE IF NOT EXISTS `tournament` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `tournament_desc` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rounds` smallint(6) DEFAULT NULL,
  `playoffs_places` smallint(6) DEFAULT NULL,
  `rounds_per_pair` smallint(6) DEFAULT NULL,
  `group_count` smallint(6) DEFAULT NULL,
  `players_on_field` smallint(6) NOT NULL,
  `rules` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prizes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `venue` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacts` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_started` tinyint(1) NOT NULL,
  `is_ended` tinyint(1) NOT NULL,
  `type_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_BD5FB8D977153098` (`code`),
  KEY `IDX_BD5FB8D9F92F3E70` (`country_id`),
  KEY `IDX_BD5FB8D9C54C8C93` (`type_id`),
  KEY `IDX_BD5FB8D9A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tournament`
--

INSERT INTO `tournament` (`id`, `country_id`, `tournament_desc`, `rounds`, `playoffs_places`, `rounds_per_pair`, `group_count`, `players_on_field`, `rules`, `prizes`, `region`, `venue`, `contacts`, `code`, `is_started`, `is_ended`, `type_id`, `name`, `user_id`) VALUES
(3, 1, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 'Kauno apskritis', NULL, NULL, 'NGOT-7935', 0, 0, 1, 'Kauno apskrities salės futbolo pirmenybės', 2),
(4, 1, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, 'Šiauliai', NULL, NULL, 'ECXK-8593', 0, 0, 1, 'Šiaulių miesto lyga', 2),
(5, 1, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, 'Klaipėdos rajonas', NULL, NULL, 'EFXZ-3822', 0, 0, 1, 'Klaipėdos rajono jaunučių (U-16) lyga', 2),
(6, 1, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 'Šiauliai', NULL, NULL, 'PVRY-8109', 0, 0, 1, 'Šiaulių miesto salės futbolo čempionatas', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tournament_type`
--

DROP TABLE IF EXISTS `tournament_type`;
CREATE TABLE IF NOT EXISTS `tournament_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tournament_type`
--

INSERT INTO `tournament_type` (`id`, `type`) VALUES
(1, 'Lyga');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_blocked` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `name`, `is_blocked`) VALUES
(2, 'petras@mail.com', '[\"ROLE_MANAGER\"]', '$2y$13$3AvhHZX0Xpw4ihmX06kaAOry3TK0wox6uOGQvegknzN2NfznSuxPe', 'Petras Petrauskas', 0),
(3, 'admin@mail.com', '[\"ROLE_ADMIN\"]', '$2y$13$t2BX5bUOASmGH9IXlT9AHOFUepCT3wyc3/BK3dgg8FEQu5r76IA4S', 'Admin Admin', 0),
(5, 'ref@mail.com', '[\"ROLE_REFEREE\"]', '$2y$13$POXbzxO3et5geh70MHUSh.N8u6TAParS0Np1wJEoAgYem1LAPcUgO', 'Teisėjas', 0),
(8, 'test1@mail.com', '[\"ROLE_MANAGER\"]', '$2y$13$5cGCm/jC/H182OpXstNUju/LibvSkSNN4cEej/9i8mQ2VbHt1vbGG', 'Test User1', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `FK_98197A65296CD8AE` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`);

--
-- Constraints for table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `FK_C4E0A61F33D1A3E7` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`id`),
  ADD CONSTRAINT `FK_C4E0A61FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_C4E0A61FF92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);

--
-- Constraints for table `tournament`
--
ALTER TABLE `tournament`
  ADD CONSTRAINT `FK_BD5FB8D9A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_BD5FB8D9C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `tournament_type` (`id`),
  ADD CONSTRAINT `FK_BD5FB8D9F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
