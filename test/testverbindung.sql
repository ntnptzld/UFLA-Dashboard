-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 14. Apr 2022 um 15:43
-- Server-Version: 10.4.24-MariaDB
-- PHP-Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `testverbindung`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tabelle_1`
--

CREATE TABLE `tabelle_1` (
  `Woerter1` text NOT NULL,
  `Zahlen1` int(11) NOT NULL,
  `Zahlen2` int(11) NOT NULL,
  `Zahlen3` int(11) NOT NULL,
  `Woerteroderleer1` text NOT NULL,
  `Woerteroderleer2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `tabelle_1`
--

INSERT INTO `tabelle_1` (`Woerter1`, `Zahlen1`, `Zahlen2`, `Zahlen3`, `Woerteroderleer1`, `Woerteroderleer2`) VALUES
('Noe', 10, 11, 13, 'x', 'x'),
('HALLO', 11, 10, 15, '', ''),
('Ich will nicht mehr', 22, 21, 34, '', 'x'),
('ahhhhhhhhhh', 361, 257, 34343, '', ''),
('MAMA', 815, 187, 361, 'x', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tabelle_2`
--

CREATE TABLE `tabelle_2` (
  `Woerter1` text NOT NULL,
  `Woerter2` text NOT NULL,
  `Zahlen1` int(11) NOT NULL,
  `Zahlen2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `tabelle_2`
--

INSERT INTO `tabelle_2` (`Woerter1`, `Woerter2`, `Zahlen1`, `Zahlen2`) VALUES
('fffffffffffff', 'PFFFFFFFFF', 10, 11),
('FICKEN', 'HILFEEEE', 11, 10),
('It\'s dangerous to go alone. Take this! :)', '\"Do Blowjob, don\'t game\"', 361, 257),
('ICH SPRING GLEICH', 'WO IS MEINE MAMA', 815, 187),
('AHHHHHHHHHHHHHHHHHHHHHHHHHH', 'look, a penny!', 113341, 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tabelle_1`
--
ALTER TABLE `tabelle_1`
  ADD PRIMARY KEY (`Zahlen1`);

--
-- Indizes für die Tabelle `tabelle_2`
--
ALTER TABLE `tabelle_2`
  ADD PRIMARY KEY (`Zahlen1`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
