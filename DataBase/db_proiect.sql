-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gazdă: 127.0.0.1
-- Timp de generare: feb. 13, 2025 la 09:22 PM
-- Versiune server: 10.4.32-MariaDB
-- Versiune PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `db_proiect`
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
(25, 'articles.php', '::1', '2025-02-13 00:39:10');

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
(55, 'f', 'f', 8, 'uploads/articles/67ad3f109f930_k6cuax6hr7nd1.png', '2025-02-13 01:57:35', 'f'),
(56, 'g', 'g', 8, 'uploads/articles/67ad357889dc5_IMG-20240923-WA0008.jpg', '2025-02-13 01:57:44', 'g'),
(57, 'h', 'h', 8, 'uploads/articles/67ad357fcb831_Screenshot_2024-09-21_023832.png', '2025-02-13 01:57:51', 'h');

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
(19, 'gigel', '$2y$10$q7TN/KQR2gRN4htUoGAYmua7DK9bUa4ebQVLI868cdiH6PFjRY9vm', 'gigel@gmail.com', 'user');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pentru tabele `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pentru tabele `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pentru tabele `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
