-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 30 août 2024 à 13:28
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `voyages_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `etapes`
--

CREATE TABLE `etapes` (
  `id` int(11) NOT NULL,
  `trajet_id` int(11) NOT NULL,
  `ville_id` int(11) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etapes`
--

INSERT INTO `etapes` (`id`, `trajet_id`, `ville_id`, `position`) VALUES
(1, 1, 2, 0),
(2, 1, 1, 1),
(3, 1, 6, 2),
(4, 2, 2, 0),
(5, 2, 5, 1),
(6, 3, 1, 0),
(7, 3, 2, 1),
(8, 4, 2, 0),
(9, 4, 1, 1),
(10, 4, 26, 2),
(11, 5, 2, 0),
(12, 5, 5, 1),
(13, 6, 2, 0),
(14, 6, 3, 1),
(15, 7, 2, 0),
(16, 7, 11, 1),
(17, 8, 17, 0),
(18, 8, 22, 1);

-- --------------------------------------------------------

--
-- Structure de la table `trajets`
--

CREATE TABLE `trajets` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `transport_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trajets`
--

INSERT INTO `trajets` (`id`, `nom`, `transport_id`) VALUES
(1, 'Voyage test', NULL),
(2, 'Test 2', NULL),
(3, 'Test 3', 1),
(4, 'Vacances Australie', 1),
(5, 'Vacances chez les grands-parents', 2),
(6, 'Vacances', 4),
(7, 'Séjour linguistique', 3),
(8, 'Croisière', 5);

-- --------------------------------------------------------

--
-- Structure de la table `transports`
--

CREATE TABLE `transports` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `transports`
--

INSERT INTO `transports` (`id`, `type`) VALUES
(1, 'avion'),
(2, 'train'),
(3, 'voiture'),
(4, 'bus'),
(5, 'bateau');

-- --------------------------------------------------------

--
-- Structure de la table `villes`
--

CREATE TABLE `villes` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `pays` varchar(255) DEFAULT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `villes`
--

INSERT INTO `villes` (`id`, `nom`, `pays`, `latitude`, `longitude`) VALUES
(1, 'Paris', 'France', '48.856600', '2.352200'),
(2, 'Bordeaux', 'France', '44.837800', '-0.579200'),
(3, 'Lyon', 'France', '45.750000', '4.850000'),
(4, 'Nice', 'France', '43.710200', '7.262000'),
(5, 'Toulouse', 'France', '43.604700', '1.444200'),
(6, 'New York', 'États-Unis', '40.712800', '-74.006000'),
(7, 'Los Angeles', 'États-Unis', '34.052200', '-118.243700'),
(8, 'San Francisco', 'États-Unis', '37.774900', '-122.419400'),
(9, 'Chicago', 'États-Unis', '41.878100', '-87.629800'),
(10, 'Las Vegas', 'États-Unis', '36.169900', '-115.139800'),
(11, 'Londres', 'Royaume-Uni', '51.507400', '-0.127800'),
(12, 'Edinburgh', 'Royaume-Uni', '55.953300', '-3.188300'),
(13, 'Manchester', 'Royaume-Uni', '53.483900', '-2.244600'),
(14, 'Liverpool', 'Royaume-Uni', '53.408400', '-2.991600'),
(15, 'Birmingham', 'Royaume-Uni', '52.486200', '-1.890400'),
(16, 'Madrid', 'Espagne', '40.416800', '-3.703800'),
(17, 'Barcelone', 'Espagne', '41.385063', '2.173403'),
(18, 'Seville', 'Espagne', '37.388600', '-5.982300'),
(19, 'Valence', 'Espagne', '39.469900', '-0.376300'),
(20, 'Bilbao', 'Espagne', '43.263000', '-2.935000'),
(21, 'Rome', 'Italie', '41.902800', '12.496400'),
(22, 'Venise', 'Italie', '45.440800', '12.315500'),
(23, 'Florence', 'Italie', '43.769600', '11.255800'),
(24, 'Milan', 'Italie', '45.464200', '9.190000'),
(25, 'Naples', 'Italie', '40.852200', '14.268100'),
(26, 'Sydney', 'Australie', '-33.868800', '151.209300'),
(27, 'Melbourne', 'Australie', '-37.813600', '144.963100'),
(28, 'Brisbane', 'Australie', '-27.469800', '153.025100'),
(29, 'Perth', 'Australie', '-31.950500', '115.860500'),
(30, 'Adelaide', 'Australie', '-34.928500', '138.600700');

-- --------------------------------------------------------

--
-- Structure de la table `voyages`
--

CREATE TABLE `voyages` (
  `id` int(11) NOT NULL,
  `depart` varchar(100) DEFAULT NULL,
  `correspondance` varchar(100) NOT NULL,
  `arrivee` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `voyages`
--

INSERT INTO `voyages` (`id`, `depart`, `correspondance`, `arrivee`) VALUES
(1, 'Bordeaux', '', 'Paris'),
(2, 'Paris', '', 'Tokyo'),
(3, 'Bordeaux ', '', 'New York'),
(4, 'Bordeaux', '', 'New York'),
(5, 'Bordeaux', 'Paris', 'Sydney');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `etapes`
--
ALTER TABLE `etapes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trajet_id` (`trajet_id`),
  ADD KEY `ville_id` (`ville_id`);

--
-- Index pour la table `trajets`
--
ALTER TABLE `trajets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transport` (`transport_id`);

--
-- Index pour la table `transports`
--
ALTER TABLE `transports`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `villes`
--
ALTER TABLE `villes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `voyages`
--
ALTER TABLE `voyages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `etapes`
--
ALTER TABLE `etapes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `trajets`
--
ALTER TABLE `trajets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `transports`
--
ALTER TABLE `transports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `villes`
--
ALTER TABLE `villes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `voyages`
--
ALTER TABLE `voyages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `etapes`
--
ALTER TABLE `etapes`
  ADD CONSTRAINT `etapes_ibfk_1` FOREIGN KEY (`trajet_id`) REFERENCES `trajets` (`id`),
  ADD CONSTRAINT `etapes_ibfk_2` FOREIGN KEY (`ville_id`) REFERENCES `villes` (`id`);

--
-- Contraintes pour la table `trajets`
--
ALTER TABLE `trajets`
  ADD CONSTRAINT `fk_transport` FOREIGN KEY (`transport_id`) REFERENCES `transports` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
