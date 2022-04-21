-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 28 Avril 2017 à 17:00
-- Version du serveur :  5.7.11
-- Version de PHP :  5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `dbquiz`
--

-- --------------------------------------------------------

--
-- Structure de la table `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `type` varchar(60) NOT NULL,
  `reponses` text NOT NULL,
  `bonneReponse` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `question`
--

INSERT INTO `question` (`id`, `titre`, `type`, `reponses`, `bonneReponse`) VALUES
(1, 'Quelle fonction permet de transformer une chaîne en majuscules?', 'choix unique', '{\n	"0": "toUpper()",\n	"1": "upperCase()",\n	"2": "strToUpper()",\n	"3": "toUpperCase()",\n	"4": "majuscules()"\n}', 2),
(2, 'Quelle fonction permet d\'envoyer un email?', 'choix unique', '{\r\n"0":"sendmail()",\r\n"1":"mail()",\r\n"2":"email()",\r\n"3":"post()"\r\n}', 1),
(3, 'Quelle fonction permet d\'ajouter un élément dans un tableau?', 'choix unique', '{\r\n"0":"pop()",\r\n"1":"insert()",\r\n"2":"array_push()",\r\n"3":"push()"\r\n}', 2);

-- --------------------------------------------------------

--
-- Structure de la table `quiz`
--

CREATE TABLE `quiz` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateDebutPublication` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateFinPublication` datetime DEFAULT NULL,
  `privé` enum('Y','N') NOT NULL,
  `activated` enum('Y','N') NOT NULL,
  `illustration` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `quiz`
--

INSERT INTO `quiz` (`id`, `user_id`, `titre`, `description`, `dateCreation`, `dateDebutPublication`, `dateFinPublication`, `privé`, `activated`, `illustration`) VALUES
(1, 1, 'Les fonctions PHP', 'Test de connaissance des fonctions intégrées du langage PHP.', '2017-04-15 00:00:00', '2017-04-15 00:00:00', NULL, 'N', 'Y', 'design/assets/PHP_functions.jpg'),
(2, 2, 'Géopolitique', 'Une liste des questions portant sur la géopolitique dans le monde.', '2017-03-28 00:00:00', '2017-03-28 00:00:00', '2017-05-13 00:00:00', 'N', 'Y', 'design/assets/geopolitique.jpg'),
(3, 2, 'Histoire', 'Questions portant sur l\'histoire du monde.', '2017-03-28 00:00:00', '2017-03-28 00:00:00', NULL, 'N', 'Y', 'design/assets/histoire.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_question`
--

CREATE TABLE `quiz_question` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `quiz_question`
--

INSERT INTO `quiz_question` (`id`, `quiz_id`, `question_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `uq_evaluation`
--

CREATE TABLE `uq_evaluation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `evaluation` tinyint(4) NOT NULL,
  `dateEvaluation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `uq_evaluation`
--

INSERT INTO `uq_evaluation` (`id`, `user_id`, `quiz_id`, `evaluation`, `dateEvaluation`) VALUES
(1, 1, 1, 4, '2017-04-28 14:21:20'),
(2, 2, 1, 5, '2017-04-28 14:27:38'),
(3, 3, 1, 4, '2017-04-28 14:27:38');

-- --------------------------------------------------------

--
-- Structure de la table `uq_participation`
--

CREATE TABLE `uq_participation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `temps` int(11) NOT NULL,
  `dateParticipation` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `uq_participation`
--

INSERT INTO `uq_participation` (`id`, `user_id`, `quiz_id`, `score`, `temps`, `dateParticipation`) VALUES
(1, 1, 1, 1, 15, '2017-04-28 13:30:00'),
(2, 2, 1, 1, 15, '2017-04-28 14:00:00'),
(3, 3, 1, 0, 74, '2017-04-28 14:10:00');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `type` varchar(30) NOT NULL,
  `activated` enum('Y','N') NOT NULL,
  `email` varchar(255) NOT NULL,
  `sexe` enum('m','f') NOT NULL,
  `dateInscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `type`, `activated`, `email`, `sexe`, `dateInscription`) VALUES
(1, 'marc', '', 'admin', 'Y', 'marc@sull.com', 'm', '2017-04-01 00:00:00'),
(2, 'bob', '', 'membre', 'Y', 'bob@sull.com', 'm', '2017-04-08 00:00:00'),
(3, 'fred', '', 'membre', 'N', 'fred@sull.com', 'm', '2017-04-08 00:00:00'),
(4, 'claire', '', 'membre', 'Y', 'claire@sull.com', 'f', '2017-04-15 00:00:00'),
(5, 'king', '$2y$10$U0DRf7t6BPCbWA2Khu3uAezBBPk3rm0NqSY2MuEMQ2IuEoDKqVIn6', 'membre', 'N', 'king@sull.com', 'm', '2017-04-28 16:48:26'),
(6, 'Mike', '$2y$10$Zj.eTY9Uk4gBeFFxZ9uLHudj0UHzhHQK2SIRbLE0O5eF4vTSLCS9W', 'membre', 'N', 'mike@sull.com', 'm', '2017-04-28 16:59:26');

-- --------------------------------------------------------

--
-- Structure de la table `user_meta`
--

CREATE TABLE `user_meta` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `meta_key` varchar(60) NOT NULL,
  `meta_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `user_meta`
--

INSERT INTO `user_meta` (`id`, `user_id`, `meta_key`, `meta_value`) VALUES
(1, 1, 'langue', 'fr'),
(2, 1, 'couleur', 'lightblue'),
(3, 4, 'couleur', 'lightgreen');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `quiz_question`
--
ALTER TABLE `quiz_question`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quiz_question_unique` (`quiz_id`,`question_id`) USING BTREE,
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Index pour la table `uq_evaluation`
--
ALTER TABLE `uq_evaluation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Index pour la table `uq_participation`
--
ALTER TABLE `uq_participation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_2` (`username`),
  ADD KEY `username` (`username`);

--
-- Index pour la table `user_meta`
--
ALTER TABLE `user_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `quiz_question`
--
ALTER TABLE `quiz_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `uq_evaluation`
--
ALTER TABLE `uq_evaluation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `uq_participation`
--
ALTER TABLE `uq_participation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `user_meta`
--
ALTER TABLE `user_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
