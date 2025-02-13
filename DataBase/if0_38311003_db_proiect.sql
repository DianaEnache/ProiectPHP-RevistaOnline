-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Gazdă: sql104.byetcluster.com
-- Timp de generare: feb. 13, 2025 la 05:10 PM
-- Versiune server: 10.6.19-MariaDB
-- Versiune PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `if0_38311003_db_proiect`
--

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `analytics`
--

CREATE TABLE `analytics` (
  `id` int(11) NOT NULL,
  `page` varchar(255) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `visit_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `analytics`
--

INSERT INTO `analytics` (`id`, `page`, `ip_address`, `visit_date`) VALUES
(24, 'home.php', '::1', '2025-02-13 00:39:03'),
(25, 'articles.php', '::1', '2025-02-13 00:39:10'),
(26, 'home.php', '81.196.223.232', '2025-02-13 20:46:47'),
(27, 'articles.php', '81.196.223.232', '2025-02-13 20:46:51'),
(28, 'home.php', '92.87.175.5', '2025-02-13 20:46:52');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `imageUrl` varchar(255) DEFAULT NULL,
  `create_date` datetime DEFAULT current_timestamp(),
  `category` varchar(100) NOT NULL DEFAULT 'General'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `author_id`, `imageUrl`, `create_date`, `category`) VALUES
(59, 'Mitski closes out her final show of â€˜The Land is Inhospitable and So Are Weâ€™ tour', 'Japanese American indie singer-songwriter Mitski closed out her tour for her seventh studio album â€œThe Land is Inhospitable and So Are Weâ€ on September 28 at the Hollywood Bowl.\r\n\r\nAudiences came close to never seeing Mitski perform in person again. In 2019, Mitski announced in a Central Park performance that she would not be performing live anymore, and nearly quit music altogether which caused an uproar on social media.\r\n\r\nâ€œItâ€™s time to be a human again,â€ she wrote on Twitter/X before deleting all social media accounts. After touring for five years with no breaks, Mitski took a hiatus from music and performing, saying that her â€œself-worth/identity will start depending too much on staying in the game.â€\r\n\r\nHowever, after facing a pandemic and taking the time to recuperate, Mitski returned to the music scene in 2022 with her record Laurel Hell.', 8, 'uploads/articles/67ae5c64358f0_mitski.jpg', '2025-02-13 12:56:04', 'Music');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `create_date` datetime DEFAULT current_timestamp(),
  `summary` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `article_id`, `comment`, `create_date`, `summary`) VALUES
(47, 11, 59, 'that\'s pretty good', '2025-02-13 13:13:15', 'good music');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(30) NOT NULL,
  `role` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(8, 'admin', '$2y$10$ZfRDA9VkyJaAs.hakxKD/etHV3VtL0MAmDBfsc0IintU0Kd.t1sDq', 'admin@gmail.com', 'admin'),
(11, 'diana', '$2y$10$PtikY0e0dxX1IF3JmHoFt.77s0oakOC4P.YNhMiOgCDEu2.U.47c6', 'diana@gmail.com', 'user'),
(12, 'editor', '$2y$10$bu8zjGO0hdvrQcr9ZkaF5.GGbYprywrolQHmZR3qLLdR6vhbDTwj6', 'editor@gmail.com', 'editor'),
(19, 'gigel', '$2y$10$q7TN/KQR2gRN4htUoGAYmua7DK9bUa4ebQVLI868cdiH6PFjRY9vm', 'gigel@gmail.com', 'user'),
(20, 'mirel', '$2y$10$Nt8fBpcH1RbOk8XFPiOo3u72VVY9CQf29zSZzW1CL60.zUVn3wdFm', 'mirel@gmail.com', 'user');

--
-- Indexuri pentru tabele eliminate
--

--
-- Indexuri pentru tabele `analytics`
--
ALTER TABLE `analytics`
  ADD PRIMARY KEY (`id`);

--
-- Indexuri pentru tabele `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexuri pentru tabele `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Indexuri pentru tabele `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pentru tabele eliminate
--

--
-- AUTO_INCREMENT pentru tabele `analytics`
--
ALTER TABLE `analytics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pentru tabele `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pentru tabele `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pentru tabele `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constrângeri pentru tabele eliminate
--

--
-- Constrângeri pentru tabele `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

--
-- Constrângeri pentru tabele `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
