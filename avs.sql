-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 31 août 2022 à 08:50
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `avs`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `idAdmin` int(11) NOT NULL,
  `ident` varchar(250) NOT NULL,
  `mdp` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `idCom` int(11) NOT NULL,
  `commentaire` longtext NOT NULL,
  `idSouhait` int(11) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `idMessage` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `dateMess` datetime NOT NULL DEFAULT current_timestamp(),
  `idUser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`idMessage`, `message`, `dateMess`, `idUser`) VALUES
(1, 'hello friend', '2022-07-14 00:00:00', 1),
(2, 'I\'m happy', '2022-07-15 00:00:00', 8),
(10, 'hello !!!', '2022-07-18 22:13:13', NULL),
(11, 'hello !!!', '2022-07-18 22:13:15', NULL),
(12, 'welcome !!!!', '2022-07-18 22:15:52', NULL),
(14, 'coucou', '2022-07-18 23:20:09', NULL),
(15, 'yellow submarine !!!!', '2022-07-19 08:21:13', NULL),
(16, 'hello', '2022-07-19 10:20:53', NULL),
(17, 'hello???????????????????????????????', '2022-07-19 10:22:51', NULL),
(18, 'hello ju !!', '2022-07-19 11:08:03', NULL),
(19, 'ju my love ', '2022-07-19 11:14:45', NULL),
(20, '', '2022-07-19 11:14:48', NULL),
(21, '', '2022-07-19 11:14:49', NULL),
(22, '', '2022-07-19 11:14:49', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE `session` (
  `idSession` int(11) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `session`
--

INSERT INTO `session` (`idSession`, `idUser`) VALUES
(1, 14);

-- --------------------------------------------------------

--
-- Structure de la table `souhait`
--

CREATE TABLE `souhait` (
  `idSouhait` int(11) NOT NULL,
  `titre` longtext NOT NULL,
  `categorie` varchar(250) NOT NULL,
  `descriptif` longtext NOT NULL,
  `zoneGeo` varchar(250) DEFAULT NULL,
  `dateAjout` datetime NOT NULL DEFAULT current_timestamp(),
  `dateRealisation` date DEFAULT NULL,
  `resume` longtext DEFAULT NULL,
  `img` varchar(50) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `souhait`
--

INSERT INTO `souhait` (`idSouhait`, `titre`, `categorie`, `descriptif`, `zoneGeo`, `dateAjout`, `dateRealisation`, `resume`, `img`, `status`, `idUser`) VALUES
(1, 'Apprendre le Kite Surf', 'Sport', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 'Les Landes', '2022-07-22 00:00:00', NULL, NULL, NULL, 0, 8),
(2, 'Faire un road trip en Argentine', 'Voyage', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 'Savoie', '2022-07-20 00:00:00', NULL, NULL, NULL, 0, 1),
(3, 'Voir Bob Marley en live', 'Musique', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 'Haut de France', '2022-07-18 00:00:00', NULL, NULL, NULL, 0, 9);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `image` varchar(250) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `dateDeNaissance` date NOT NULL,
  `email` varchar(50) NOT NULL,
  `motDePasse` varchar(250) NOT NULL,
  `ville` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `image`, `nom`, `prenom`, `pseudo`, `dateDeNaissance`, `email`, `motDePasse`, `ville`) VALUES
(1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTVJBpm0tb7hiTtW6DV6p4nlh1CNITi9Kdv2Q&usqp=CAU', 'Fist', 'Jean', 'JF', '1980-10-14', 'jf@live.fr', 'jf', 'avion'),
(8, 'https://img.freepik.com/photos-gratuite/femme-portant-lunettes-vr-flotte-dans-espace-neon-cables-attaches-elle-concept-avatar-metaverse_1217-3926.jpg?size=626&ext=jpg&ga=GA1.2.842613585.1659331630', 'JESS', 'jess', 'jessie', '2022-07-01', 'jess@live.fr', 'jess', 'bethune'),
(9, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTVJBpm0tb7hiTtW6DV6p4nlh1CNITi9Kdv2Q&usqp=CAU', 'juju', 'juju', 'juju', '2022-07-05', 'juju@live.fr', 'juju', 'arras'),
(14, 'https://m.media-amazon.com/images/I/71rKq5xSsTL._SL1400_.jpg', 'JUJU', 'JUJU', 'JUJU', '0000-00-00', 'juju@hotmail.fr', '$argon2i$v=19$m=65536,t=4,p=1$cmp5VExBamFGeTN1NU1CcA$i07sS6gEWzhWeyViHmuppVtsxABS59IyC3Rx7HXfGEk', 'AVION');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idAdmin`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`idCom`),
  ADD KEY `commentaire_Souhait_FK` (`idSouhait`),
  ADD KEY `commentaire_USER0_FK` (`idUser`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`idMessage`),
  ADD KEY `Message_USER_FK` (`idUser`);

--
-- Index pour la table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`idSession`);

--
-- Index pour la table `souhait`
--
ALTER TABLE `souhait`
  ADD PRIMARY KEY (`idSouhait`),
  ADD KEY `Souhait_USER_FK` (`idUser`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `idAdmin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `idCom` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `idMessage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `session`
--
ALTER TABLE `session`
  MODIFY `idSession` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `souhait`
--
ALTER TABLE `souhait`
  MODIFY `idSouhait` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_Souhait_FK` FOREIGN KEY (`idSouhait`) REFERENCES `souhait` (`idSouhait`),
  ADD CONSTRAINT `commentaire_USER0_FK` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `Message_USER_FK` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `souhait`
--
ALTER TABLE `souhait`
  ADD CONSTRAINT `Souhait_USER_FK` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
