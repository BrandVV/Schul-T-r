-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 11. Sep 2021 um 21:45
-- Server-Version: 10.4.21-MariaDB
-- PHP-Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `test`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `actions`
--

CREATE TABLE `actions` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vorname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nachname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `offene_zeit` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `oeffnungs_anzahl` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `actions`
--

INSERT INTO `actions` (`id`, `email`, `vorname`, `nachname`, `created_at`, `offene_zeit`, `oeffnungs_anzahl`) VALUES
(1, 'n.z@gmx.de', 'Niklas', 'Zerbst', '2021-09-11 15:47:04', '10', '1'),
(2, 'n.z@gmx.de', 'Niklas', 'Zerbst', '2021-09-11 15:47:15', '10', '2'),
(3, 'n.z@gmx.de', 'Niklas', 'Zerbst', '2021-09-11 15:47:27', '5', '3'),
(4, 'max.musterman@gmx.com', 'Max', 'Musteman', '2021-09-11 15:47:52', '10', '1'),
(5, 'max.musterman@gmx.com', 'Max', 'Musteman', '2021-09-11 15:55:07', '10', '2'),
(6, 'max.musterman@gmx.com', 'Max', 'Musteman', '2021-09-11 15:57:29', '10', '3'),
(7, 'max.musterman@gmx.com', 'Max', 'Musteman', '2021-09-11 15:58:24', '10', '4'),
(8, 'max.musterman@gmx.com', 'Max', 'Musteman', '2021-09-11 16:01:11', '10', '5'),
(9, 'max.musterman@gmx.com', 'Max', 'Musteman', '2021-09-11 16:02:35', '10', '6'),
(10, 'n.z@gmx.de', 'Niklas', 'Zerbst', '2021-09-11 16:09:28', '10', '4'),
(11, 'n.z@gmx.de', 'Niklas', 'Zerbst', '2021-09-11 16:24:20', '', '5'),
(12, 'n.z@gmx.de', 'Niklas', 'Zerbst', '2021-09-11 16:25:23', '5', '6'),
(13, 'n.z@gmx.de', 'Niklas', 'Zerbst', '2021-09-11 16:27:23', '5', '7'),
(14, 'n.z@gmx.de', 'Niklas', 'Zerbst', '2021-09-11 18:31:14', '', '8'),
(15, 'n.z@gmx.de', 'Niklas', 'Zerbst', '2021-09-11 18:31:40', '', '9'),
(16, 'n.z@gmx.de', 'Niklas', 'Zerbst', '2021-09-11 18:49:34', '', '10'),
(17, 'n.z@gmx.de', 'Niklas', 'Zerbst', '2021-09-11 18:52:57', '', '10');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passwort` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vorname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nachname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `get_admin` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `email`, `passwort`, `vorname`, `nachname`, `created_at`, `updated_at`, `get_admin`) VALUES
(1, 'n.z@gmx.de', '$2y$10$4uRlGBEsIUYZH4eU7w.0b.tjjE8GqKJhHoCOyHJqEbiKzOFMSj4JS', 'Niklas', 'Zerbst', '2021-09-10 18:37:04', '2021-09-10 18:46:11', 'on'),
(5, 'max.musterman@gmx.com', '$2y$10$O6QJghx2HG1twl9LiNpLVO2hPUXTHHSgrBnXyFDzb20wW58L2line', 'Max', 'Musteman', '2021-09-10 18:52:16', '2021-09-11 15:24:43', 'off');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `actions`
--
ALTER TABLE `actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
