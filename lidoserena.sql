-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 19 mars 2025 à 19:47
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lidoserena`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`) VALUES
(17, 'Boisson'),
(18, 'Dessert'),
(20, 'Pâtes'),
(16, 'Pizza'),
(19, 'Plat Complet');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `table_id` int NOT NULL,
  `date_commande` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('en cours','payé','pret','annulé') NOT NULL DEFAULT 'en cours',
  `prix_total` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `table_id` (`table_id`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `table_id`, `date_commande`, `statut`, `prix_total`) VALUES
(47, 1, '2025-03-12 16:29:44', 'payé', 0.00),
(48, 2, '2025-03-12 16:29:53', 'payé', 0.00),
(49, 1, '2025-03-16 01:59:14', 'payé', 0.00),
(50, 1, '2025-03-16 02:42:58', 'payé', 0.00),
(51, 1, '2025-03-16 02:45:58', 'payé', 0.00),
(52, 6, '2025-03-16 12:12:50', 'payé', 0.00),
(53, 1, '2025-03-17 01:23:14', 'payé', 0.00),
(54, 1, '2025-03-17 18:12:12', 'payé', 0.00),
(55, 6, '2025-03-17 18:12:58', 'payé', 0.00),
(56, 5, '2025-03-17 18:13:17', 'payé', 0.00),
(57, 1, '2025-03-17 18:15:17', 'payé', 89.00),
(58, 1, '2025-03-17 18:19:30', 'payé', 344.80),
(59, 2, '2025-03-17 18:24:51', 'payé', 9.00),
(60, 1, '2025-03-17 18:27:37', 'payé', 38.50),
(61, 1, '2025-03-17 18:28:03', 'payé', 47.50),
(117, 1, '2025-03-17 19:21:51', 'payé', 0.00),
(118, 1, '2025-03-17 19:24:18', 'payé', 0.00),
(119, 1, '2025-03-17 19:25:35', 'payé', 40.00),
(120, 1, '2025-03-17 19:26:08', 'payé', 177.50),
(121, 3, '2025-03-17 19:54:19', 'payé', 19.00),
(122, 2, '2025-03-17 19:57:53', 'payé', 23.50),
(123, 1, '2025-03-17 20:12:38', 'payé', 60.10),
(124, 1, '2025-03-17 20:29:50', 'payé', 9.50),
(125, 1, '2025-03-17 21:53:23', 'payé', 20.00),
(126, 1, '2025-03-17 22:15:40', 'payé', 20.00),
(127, 1, '2025-03-19 12:16:31', 'payé', 43.40),
(128, 6, '2025-03-19 12:18:35', 'payé', 30.50),
(129, 2, '2025-03-19 12:19:07', 'payé', 34.00),
(130, 4, '2025-03-19 12:26:15', 'payé', 16.00),
(131, 4, '2025-03-19 12:26:16', 'payé', 16.00),
(132, 1, '2025-03-19 20:05:08', 'payé', 37.00),
(133, 1, '2025-03-19 20:21:44', 'payé', 42.50);

-- --------------------------------------------------------

--
-- Structure de la table `commandes_details`
--

DROP TABLE IF EXISTS `commandes_details`;
CREATE TABLE IF NOT EXISTS `commandes_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `commande_id` int NOT NULL,
  `produit_id` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `statut` varchar(20) DEFAULT 'en_cours',
  PRIMARY KEY (`id`),
  KEY `commande_id` (`commande_id`),
  KEY `produit_id` (`produit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=319 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes_details`
--

INSERT INTO `commandes_details` (`id`, `commande_id`, `produit_id`, `prix_unitaire`, `statut`) VALUES
(125, 48, 43, 15.00, 'pret'),
(126, 48, 43, 15.00, 'pret'),
(137, 47, 37, 9.00, 'en_cours'),
(138, 47, 37, 9.00, 'en_cours'),
(139, 47, 37, 9.00, 'en_cours'),
(145, 49, 35, 9.50, 'pret'),
(146, 49, 50, 4.50, 'pret'),
(147, 49, 50, 4.50, 'pret'),
(148, 49, 36, 10.00, 'pret'),
(149, 49, 36, 10.00, 'pret'),
(150, 50, 37, 9.00, 'en_cours'),
(151, 51, 47, 6.50, 'pret'),
(152, 52, 37, 9.00, 'en_cours'),
(153, 52, 51, 5.50, 'pret'),
(154, 52, 50, 4.50, 'en_cours'),
(155, 52, 50, 4.50, 'en_cours'),
(156, 52, 47, 6.50, 'en_cours'),
(157, 53, 37, 9.00, 'pret'),
(158, 53, 37, 9.00, 'pret'),
(159, 53, 36, 10.00, 'pret'),
(160, 53, 51, 5.50, 'pret'),
(161, 53, 52, 6.00, 'pret'),
(162, 53, 53, 5.50, 'pret'),
(163, 54, 57, 4.90, 'en_cours'),
(164, 54, 57, 4.90, 'en_cours'),
(165, 54, 37, 9.00, 'en_cours'),
(166, 55, 56, 5.80, 'en_cours'),
(167, 55, 56, 5.80, 'en_cours'),
(168, 56, 37, 9.00, 'en_cours'),
(169, 56, 37, 9.00, 'en_cours'),
(170, 56, 37, 9.00, 'en_cours'),
(171, 56, 37, 9.00, 'en_cours'),
(172, 56, 48, 2.00, 'en_cours'),
(173, 56, 48, 2.00, 'en_cours'),
(174, 56, 37, 9.00, 'en_cours'),
(175, 56, 36, 10.00, 'en_cours'),
(176, 56, 47, 6.50, 'en_cours'),
(177, 57, 38, 10.50, 'en_cours'),
(178, 57, 38, 10.50, 'en_cours'),
(179, 57, 38, 10.50, 'en_cours'),
(180, 57, 37, 9.00, 'en_cours'),
(181, 57, 55, 4.50, 'en_cours'),
(182, 58, 38, 10.50, 'en_cours'),
(183, 58, 38, 10.50, 'en_cours'),
(184, 58, 38, 10.50, 'en_cours'),
(185, 58, 38, 10.50, 'en_cours'),
(186, 58, 38, 10.50, 'en_cours'),
(187, 58, 38, 10.50, 'en_cours'),
(188, 58, 38, 10.50, 'en_cours'),
(189, 58, 38, 10.50, 'en_cours'),
(190, 58, 41, 13.50, 'en_cours'),
(191, 58, 41, 13.50, 'en_cours'),
(192, 58, 41, 13.50, 'en_cours'),
(193, 58, 41, 13.50, 'en_cours'),
(194, 58, 56, 5.80, 'en_cours'),
(195, 58, 41, 13.50, 'en_cours'),
(196, 58, 41, 13.50, 'en_cours'),
(197, 58, 41, 13.50, 'en_cours'),
(198, 58, 41, 13.50, 'en_cours'),
(199, 58, 41, 13.50, 'en_cours'),
(200, 58, 50, 4.50, 'en_cours'),
(201, 58, 50, 4.50, 'en_cours'),
(202, 58, 50, 4.50, 'en_cours'),
(203, 59, 49, 3.00, 'en_cours'),
(204, 59, 49, 3.00, 'en_cours'),
(205, 59, 49, 3.00, 'en_cours'),
(206, 58, 36, 10.00, 'en_cours'),
(207, 58, 36, 10.00, 'en_cours'),
(208, 60, 38, 10.50, 'en_cours'),
(209, 60, 37, 9.00, 'en_cours'),
(210, 60, 37, 9.00, 'en_cours'),
(211, 61, 35, 9.50, 'en_cours'),
(212, 61, 35, 9.50, 'en_cours'),
(213, 61, 35, 9.50, 'en_cours'),
(214, 61, 35, 9.50, 'en_cours'),
(215, 61, 35, 9.50, 'en_cours'),
(251, 117, 36, 0.00, 'en_cours'),
(252, 118, 50, 0.00, 'en_cours'),
(253, 118, 50, 0.00, 'en_cours'),
(254, 118, 44, 0.00, 'en_cours'),
(255, 118, 39, 0.00, 'en_cours'),
(256, 119, 35, 9.50, 'en_cours'),
(257, 119, 35, 9.50, 'en_cours'),
(258, 119, 37, 9.00, 'en_cours'),
(259, 119, 49, 3.00, 'en_cours'),
(260, 119, 50, 4.50, 'en_cours'),
(261, 119, 50, 4.50, 'en_cours'),
(262, 120, 49, 3.00, 'en_cours'),
(263, 120, 49, 3.00, 'en_cours'),
(264, 120, 36, 10.00, 'en_cours'),
(265, 120, 36, 10.00, 'en_cours'),
(266, 120, 36, 10.00, 'en_cours'),
(267, 120, 36, 10.00, 'en_cours'),
(268, 120, 35, 9.50, 'en_cours'),
(269, 120, 36, 10.00, 'en_cours'),
(270, 120, 51, 5.50, 'en_cours'),
(271, 120, 51, 5.50, 'en_cours'),
(272, 120, 50, 4.50, 'en_cours'),
(273, 120, 50, 4.50, 'en_cours'),
(274, 120, 36, 10.00, 'en_cours'),
(275, 120, 36, 10.00, 'en_cours'),
(276, 120, 36, 10.00, 'en_cours'),
(277, 120, 50, 4.50, 'en_cours'),
(278, 120, 50, 4.50, 'en_cours'),
(279, 120, 49, 3.00, 'en_cours'),
(280, 120, 49, 3.00, 'en_cours'),
(281, 120, 42, 18.00, 'en_cours'),
(282, 120, 42, 18.00, 'en_cours'),
(283, 121, 35, 9.50, 'en_cours'),
(284, 121, 35, 9.50, 'en_cours'),
(285, 120, 51, 5.50, 'en_cours'),
(286, 120, 51, 5.50, 'en_cours'),
(287, 122, 35, 9.50, 'en_cours'),
(288, 122, 35, 9.50, 'en_cours'),
(289, 122, 50, 4.50, 'en_cours'),
(290, 123, 46, 5.50, 'en_cours'),
(291, 123, 46, 5.50, 'en_cours'),
(292, 123, 47, 6.50, 'en_cours'),
(293, 123, 55, 4.50, 'en_cours'),
(294, 123, 55, 4.50, 'en_cours'),
(295, 123, 56, 5.80, 'en_cours'),
(296, 123, 56, 5.80, 'en_cours'),
(297, 124, 35, 9.50, 'en_cours'),
(298, 127, 43, 15.00, 'en_cours'),
(299, 127, 57, 4.90, 'pret'),
(300, 127, 53, 5.50, 'en_cours'),
(301, 128, 46, 5.50, 'en_cours'),
(302, 128, 49, 3.00, 'en_cours'),
(303, 130, 38, 10.50, 'en_cours'),
(304, 130, 53, 5.50, 'en_cours'),
(305, 131, 38, 10.50, 'en_cours'),
(306, 131, 53, 5.50, 'en_cours'),
(307, 129, 36, 10.00, 'en_cours'),
(308, 129, 54, 6.00, 'en_cours'),
(309, 132, 36, 10.00, 'pret'),
(310, 132, 37, 9.00, 'en_cours'),
(311, 132, 37, 9.00, 'en_cours'),
(312, 132, 37, 9.00, 'en_cours'),
(313, 133, 38, 10.50, 'pret'),
(314, 133, 52, 6.00, 'pret'),
(315, 133, 52, 6.00, 'en_cours'),
(316, 133, 34, 8.50, 'en_cours'),
(317, 133, 34, 8.50, 'en_cours'),
(318, 133, 49, 3.00, 'en_cours');

-- --------------------------------------------------------

--
-- Structure de la table `commandes_menus`
--

DROP TABLE IF EXISTS `commandes_menus`;
CREATE TABLE IF NOT EXISTS `commandes_menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `commande_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `commande_id` (`commande_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes_menus`
--

INSERT INTO `commandes_menus` (`id`, `commande_id`, `menu_id`, `prix`) VALUES
(36, 48, 48, 16.00),
(38, 49, 50, 14.00),
(39, 49, 44, 15.00),
(40, 49, 49, 19.00),
(41, 49, 50, 14.00),
(42, 49, 49, 19.00),
(43, 51, 46, 22.00),
(44, 54, 49, 19.00),
(45, 54, 45, 18.00),
(46, 55, 46, 22.00),
(47, 55, 53, 25.00),
(48, 57, 50, 14.00),
(49, 117, 45, 0.00),
(50, 118, 49, 0.00),
(51, 123, 46, 22.00),
(52, 125, 43, 20.00),
(53, 126, 43, 20.00),
(54, 128, 46, 22.00),
(55, 127, 45, 18.00),
(56, 129, 45, 18.00);

-- --------------------------------------------------------

--
-- Structure de la table `commandes_menus_details`
--

DROP TABLE IF EXISTS `commandes_menus_details`;
CREATE TABLE IF NOT EXISTS `commandes_menus_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `commande_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `produit_id` int NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `type` enum('plat','boisson','dessert') NOT NULL,
  `statut` varchar(20) DEFAULT 'en_cours',
  PRIMARY KEY (`id`),
  KEY `commande_id` (`commande_id`),
  KEY `menu_id` (`menu_id`),
  KEY `produit_id` (`produit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes_menus_details`
--

INSERT INTO `commandes_menus_details` (`id`, `commande_id`, `menu_id`, `produit_id`, `prix`, `type`, `statut`) VALUES
(82, 48, 48, 48, 0.00, 'plat', 'pret'),
(83, 48, 48, 40, 0.00, 'plat', 'pret'),
(86, 49, 50, 45, 0.00, 'plat', 'pret'),
(87, 49, 50, 41, 0.00, 'plat', 'pret'),
(88, 49, 44, 36, 0.00, 'plat', 'pret'),
(89, 49, 44, 45, 0.00, 'plat', 'pret'),
(90, 49, 49, 49, 0.00, 'plat', 'en_cours'),
(91, 49, 49, 46, 0.00, 'plat', 'en_cours'),
(92, 49, 49, 40, 0.00, 'plat', 'en_cours'),
(93, 49, 50, 46, 0.00, 'plat', 'en_cours'),
(94, 49, 50, 40, 0.00, 'plat', 'en_cours'),
(95, 49, 49, 49, 0.00, 'plat', 'en_cours'),
(96, 49, 49, 45, 0.00, 'plat', 'en_cours'),
(97, 49, 49, 40, 0.00, 'plat', 'en_cours'),
(98, 51, 46, 48, 0.00, 'plat', 'en_cours'),
(99, 51, 46, 45, 0.00, 'plat', 'en_cours'),
(100, 51, 46, 43, 0.00, 'plat', 'en_cours'),
(101, 54, 49, 48, 0.00, 'plat', 'en_cours'),
(102, 54, 49, 45, 0.00, 'plat', 'en_cours'),
(103, 54, 49, 40, 0.00, 'plat', 'en_cours'),
(104, 54, 45, 48, 0.00, 'plat', 'en_cours'),
(105, 54, 45, 43, 0.00, 'plat', 'en_cours'),
(106, 55, 46, 48, 0.00, 'plat', 'en_cours'),
(107, 55, 46, 47, 0.00, 'plat', 'en_cours'),
(108, 55, 46, 43, 0.00, 'plat', 'en_cours'),
(109, 55, 53, 50, 0.00, 'plat', 'en_cours'),
(110, 55, 53, 42, 0.00, 'plat', 'en_cours'),
(111, 57, 50, 45, 0.00, 'plat', 'en_cours'),
(112, 57, 50, 40, 0.00, 'plat', 'en_cours'),
(113, 117, 45, 48, 0.00, 'plat', 'en_cours'),
(114, 117, 45, 42, 0.00, 'plat', 'en_cours'),
(115, 118, 49, 48, 0.00, 'plat', 'en_cours'),
(116, 118, 49, 46, 0.00, 'plat', 'en_cours'),
(117, 118, 49, 39, 0.00, 'plat', 'en_cours'),
(118, 123, 46, 48, 0.00, 'plat', 'en_cours'),
(119, 123, 46, 45, 0.00, 'plat', 'en_cours'),
(120, 123, 46, 42, 0.00, 'plat', 'en_cours'),
(121, 125, 43, 34, 0.00, 'plat', 'pret'),
(122, 125, 43, 52, 0.00, 'plat', 'en_cours'),
(123, 125, 43, 57, 0.00, 'plat', 'en_cours'),
(124, 126, 43, 34, 0.00, 'plat', 'pret'),
(125, 126, 43, 49, 0.00, 'plat', 'en_cours'),
(126, 126, 43, 53, 0.00, 'plat', 'en_cours'),
(127, 128, 46, 51, 0.00, 'plat', 'en_cours'),
(128, 128, 46, 45, 0.00, 'plat', 'en_cours'),
(129, 128, 46, 42, 0.00, 'plat', 'en_cours'),
(130, 127, 45, 48, 0.00, 'plat', 'en_cours'),
(131, 127, 45, 43, 0.00, 'plat', 'en_cours'),
(132, 129, 45, 52, 0.00, 'plat', 'en_cours'),
(133, 129, 45, 43, 0.00, 'plat', 'en_cours');

-- --------------------------------------------------------

--
-- Structure de la table `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `abreviation` varchar(25) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `menus`
--

INSERT INTO `menus` (`id`, `nom`, `abreviation`, `prix`) VALUES
(43, 'Pizza Boisson Dessert', '', 20.00),
(44, 'Pizza Dessert', '', 15.00),
(45, 'Plat complet Boisson', '', 18.00),
(46, 'Plat complet Boisson Dessert', '', 22.00),
(47, 'Plats Dessert', '', 17.00),
(48, 'Pâtes Boisson', '', 16.00),
(49, 'Pâtes Boisson Dessert', '', 19.00),
(50, 'Pâtes Dessert', '', 14.00),
(51, 'Test', 'T', 22.00),
(53, 'Plat Test', 'PT', 25.00);

-- --------------------------------------------------------

--
-- Structure de la table `menu_categories`
--

DROP TABLE IF EXISTS `menu_categories`;
CREATE TABLE IF NOT EXISTS `menu_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_id` int NOT NULL,
  `categorie_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  KEY `categorie_id` (`categorie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `menu_categories`
--

INSERT INTO `menu_categories` (`id`, `menu_id`, `categorie_id`) VALUES
(4, 43, 16),
(5, 43, 17),
(6, 43, 18),
(7, 44, 16),
(8, 44, 18),
(9, 45, 19),
(10, 46, 19),
(11, 46, 17),
(12, 46, 18),
(13, 47, 19),
(14, 47, 18),
(15, 48, 20),
(16, 48, 17),
(17, 49, 20),
(18, 49, 17),
(19, 49, 18),
(20, 50, 20),
(21, 50, 18),
(22, 45, 17),
(23, 53, 19),
(24, 53, 17);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `commande_id` int NOT NULL,
  `message` text NOT NULL,
  `statut` enum('non_lu','lu') NOT NULL DEFAULT 'non_lu',
  `type` varchar(50) NOT NULL DEFAULT 'cuisine',
  PRIMARY KEY (`id`),
  KEY `commande_id` (`commande_id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `commande_id`, `message`, `statut`, `type`) VALUES
(53, 48, 'Produit (ligne ID 125) de la commande #48 est prêt', 'lu', 'serveur'),
(54, 48, 'Produit (ligne ID 125) de la commande #48 est prêt', 'lu', 'serveur'),
(55, 48, 'Produit (ligne ID 125) de la commande #48 est prêt', 'lu', 'serveur'),
(56, 48, 'Produit (ligne ID 125) de la commande #48 est prêt', 'lu', 'serveur'),
(57, 48, 'Produit (ligne ID 125) de la commande #48 est prêt', 'lu', 'serveur'),
(58, 48, 'Produit (ligne ID 126) de la commande #48 est prêt', 'lu', 'serveur'),
(59, 48, 'Produit (ligne ID 82) de la commande #48 est prêt', 'lu', 'serveur'),
(60, 48, 'Produit (ligne ID 83) de la commande #48 est prêt', 'lu', 'serveur'),
(61, 49, 'Produit (ligne ID 140) de la commande #49 est prêt', 'lu', 'serveur'),
(62, 49, 'Produit (ligne ID 141) de la commande #49 est prêt', 'lu', 'serveur'),
(63, 49, 'Produit (ligne ID 142) de la commande #49 est prêt', 'lu', 'serveur'),
(64, 49, 'Produit (ligne ID 145) de la commande #49 est prêt', 'lu', 'serveur'),
(65, 49, 'Produit (ligne ID 146) de la commande #49 est prêt', 'lu', 'serveur'),
(66, 49, 'Produit (ligne ID 147) de la commande #49 est prêt', 'lu', 'serveur'),
(67, 49, 'Produit (ligne ID 88) de la commande #49 est prêt', 'lu', 'serveur'),
(68, 49, 'Produit (ligne ID 149) de la commande #49 est prêt', 'lu', 'serveur'),
(69, 49, 'Produit (ligne ID 148) de la commande #49 est prêt', 'lu', 'serveur'),
(70, 49, 'Produit (ligne ID 86) de la commande #49 est prêt', 'lu', 'serveur'),
(71, 49, 'Produit (ligne ID 87) de la commande #49 est prêt', 'lu', 'serveur'),
(72, 49, 'Produit (ligne ID 89) de la commande #49 est prêt', 'lu', 'serveur'),
(73, 51, 'Produit (ligne ID 151) de la commande #51 est prêt', 'lu', 'serveur'),
(74, 52, 'Produit (ligne ID 153) de la commande #52 est prêt', 'lu', 'serveur'),
(75, 53, 'Produit (ligne ID 157) de la commande #53 est prêt', 'lu', 'serveur'),
(76, 53, 'Produit (ligne ID 158) de la commande #53 est prêt', 'lu', 'serveur'),
(77, 53, 'Produit (ligne ID 159) de la commande #53 est prêt', 'lu', 'serveur'),
(78, 53, 'Produit (ligne ID 160) de la commande #53 est prêt', 'lu', 'serveur'),
(79, 53, 'Produit (ligne ID 161) de la commande #53 est prêt', 'lu', 'serveur'),
(80, 53, 'Produit (ligne ID 162) de la commande #53 est prêt', 'lu', 'serveur'),
(81, 125, 'Produit (ligne ID 121) de la commande #125 est prêt', 'lu', 'serveur'),
(82, 126, 'Produit (ligne ID 124) de la commande #126 est prêt', 'lu', 'serveur'),
(83, 127, 'Produit (ligne ID 299) de la commande #127 est prêt', 'lu', 'serveur'),
(84, 132, 'Produit (ligne ID 309) de la commande #132 est prêt', 'lu', 'serveur'),
(85, 133, 'Produit (ligne ID 313) de la commande #133 est prêt', 'lu', 'serveur'),
(86, 133, 'Produit (ligne ID 314) de la commande #133 est prêt', 'lu', 'serveur');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text,
  `prix` decimal(10,2) NOT NULL,
  `categorie_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `categorie_id` (`categorie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `description`, `prix`, `categorie_id`) VALUES
(34, 'Margherita', 'Tomate, mozzarella, basilic', 8.50, 16),
(35, 'Pepperoni', 'Tomate, mozzarella, pepperoni', 9.50, 16),
(36, 'Quatre Fromages', 'Mozzarella, gorgonzola, parmesan, chèvre', 10.00, 16),
(37, 'Végétarienne', 'Tomate, mozzarella, poivrons, champignons, oignons', 9.00, 16),
(38, 'Calzone', 'Tomate, mozzarella, jambon, champignons', 10.50, 16),
(39, 'Spaghetti Bolognaise', 'Sauce tomate, viande hachée', 11.00, 20),
(40, 'Penne Carbonara', 'Crème, lardons, parmesan', 12.00, 20),
(41, 'Lasagnes', 'Viande hachée, sauce tomate, béchamel', 13.50, 20),
(42, 'Entrecôte grillée', 'Viande de bœuf grillée avec frites', 18.00, 19),
(43, 'Poulet rôti', 'Poulet fermier rôti avec pommes de terre', 15.00, 19),
(44, 'Poisson du jour', 'Poisson frais accompagné de légumes', 17.00, 19),
(45, 'Tiramisu', 'Mascarpone, café, cacao', 6.00, 18),
(46, 'Mousse au chocolat', 'Chocolat noir, œufs, sucre', 5.50, 18),
(47, 'Crème brûlée', 'Vanille, sucre caramélisé', 6.50, 18),
(48, 'Eau minérale', 'Bouteille de 50cl', 2.00, 17),
(49, 'Coca-Cola', 'Canette 33cl', 3.00, 17),
(50, 'Jus d’orange', 'Jus pressé frais', 4.50, 17),
(51, 'Vin rouge', 'Verre 12cl', 5.50, 17),
(52, 'Bière pression', 'Pinte 50cl', 6.00, 17),
(53, 'Tiramisu', 'Dessert italien au café et mascarpone', 5.50, 18),
(54, 'Fondant au chocolat', 'Gâteau au cœur coulant de chocolat', 6.00, 18),
(55, 'Crème brûlée', 'Crème à la vanille avec une couche caramélisée', 4.50, 18),
(56, 'Cheesecake', 'Gâteau au fromage frais avec un coulis de fruits rouges', 5.80, 18),
(57, 'Mille-feuille', 'Pâtisserie feuilletée avec crème pâtissière', 4.90, 18);

-- --------------------------------------------------------

--
-- Structure de la table `tables`
--

DROP TABLE IF EXISTS `tables`;
CREATE TABLE IF NOT EXISTS `tables` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero` (`numero`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tables`
--

INSERT INTO `tables` (`id`, `numero`) VALUES
(1, 'Table 1'),
(2, 'Table 2'),
(3, 'Table 3'),
(4, 'Table 4'),
(5, 'Table 5'),
(6, 'Table 6');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','serveur','cuisinier') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, '', '$2y$10$7X7v4Lwq7cg3A8rHa6rbBuvmCuEDuOjrgNw7ZO4x/ezaouGdVXd3a', 'admin'),
(2, 'gardien', 'gardien', 'admin'),
(5, 'gardien2', '$2y$10$IEKpKCUwZ5QLjdoHI.1/verhDSZDC1OkDbPmtqDi3eovoTnrA/GZm', 'admin');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commandes_details`
--
ALTER TABLE `commandes_details`
  ADD CONSTRAINT `commandes_details_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commandes_details_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commandes_menus`
--
ALTER TABLE `commandes_menus`
  ADD CONSTRAINT `commandes_menus_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commandes_menus_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commandes_menus_details`
--
ALTER TABLE `commandes_menus_details`
  ADD CONSTRAINT `commandes_menus_details_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commandes_menus_details_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commandes_menus_details_ibfk_3` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD CONSTRAINT `menu_categories_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_categories_ibfk_2` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
