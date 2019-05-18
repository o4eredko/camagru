-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Май 15 2019 г., 06:47
-- Версия сервера: 5.7.25
-- Версия PHP: 7.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `camagru`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `owner` varchar(30) NOT NULL,
  `text` text NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `owner`, `text`, `post_id`) VALUES
(33, 'yochered', '&lt;script&gt;alert&lpar;1&rpar;&lt;&sol;script&gt;', 17),
(34, 'yochered', 'fadsfadsf', 16),
(35, 'yochered', 'fadsfdasf', 16),
(36, 'yochered', '123', 16),
(37, 'yochered', '321', 16),
(38, 'yochered', 'qwerqwer', 16),
(39, 'yochered', '123123123', 16),
(40, 'yochered', 'fadsfdsfsadf', 16),
(41, 'yochered', 'Nice shot', 21),
(42, 'yochered', 'Nice', 21),
(43, 'o4eredko', '&lt;script&gt;alert&lpar;1&rpar;&lt;script&gt;', 21),
(44, 'o4eredko', 'gjhghjgkj', 24),
(45, 'yochered', '&gcy;&shchcy;&zcy;&shcy;&kcy;&gcy;&ucy;&tscy;&zcy;&shchcy;&shcy;&kcy;&tscy;&ucy;&jcy;&kcy;', 22),
(46, 'yochered', '&lt;script&gt;alert&lpar;1&rpar;&lt;&sol;script&gt;', 22);

-- --------------------------------------------------------

--
-- Структура таблицы `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `owner` varchar(30) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `likes`
--

INSERT INTO `likes` (`id`, `owner`, `post_id`) VALUES
(1, 'konstantin', 10),
(2, 'yochered', 9),
(58, 'yochered', 7),
(59, 'yochered', 2),
(60, 'yochered', 1),
(61, 'yochered', 3),
(66, 'yochered', 15),
(67, 'yochered', 24);

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `owner` varchar(30) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `img` varchar(255) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`id`, `owner`, `title`, `description`, `img`, `creation_date`) VALUES
(15, 'yochered', 'My new post', '', 'img/yochered_sdxj1MJ1.jpg', '2019-05-13 15:16:08'),
(16, 'yochered', 'My new post', '', 'img/yochered_OiiSaUxM.jpg', '2019-05-13 15:16:49'),
(17, 'yochered', 'My new post', '', 'img/yochered_GqpBMEab.jpg', '2019-05-13 15:18:11'),
(18, 'yochered', 'My new post', '', 'img/yochered_SdmeRTYB.jpg', '2019-05-13 16:59:13'),
(19, 'yochered', 'My new post', '', 'img/yochered_HbGjl5XX.jpg', '2019-05-13 17:06:05'),
(20, 'yochered', 'My new post', '', 'img/yochered_nBZwECfh.jpg', '2019-05-13 17:31:54'),
(21, 'yochered', 'Me and dinosaur bro', 'Aspizhav pidoras', 'img/yochered_HrTTV02n.jpg', '2019-05-14 11:29:34'),
(22, 'yochered', 'My new post', '', 'img/yochered_SJikH7Hu.jpg', '2019-05-14 12:58:46'),
(23, 'yochered', 'My new post', '', 'img/yochered_U1lSrYnI.jpg', '2019-05-14 15:41:10'),
(24, 'yochered', 'My new post', '', 'img/yochered_82Q1EQbv.jpg', '2019-05-14 18:18:37'),
(25, 'o4eredko', 'My new post', '', 'img/o4eredko_VlpGkunj.jpg', '2019-05-14 18:27:34'),
(26, 'yochered', 'My new post', '', 'img/yochered_p15.jpg', '2019-05-15 13:20:20');

-- --------------------------------------------------------

--
-- Структура таблицы `snapshots`
--

CREATE TABLE `snapshots` (
  `id` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `owner` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `snapshots`
--

INSERT INTO `snapshots` (`id`, `img`, `owner`) VALUES
(15, 'img/o4eredko_VlpGkunj.jpg', 'o4eredko'),
(24, 'img/yochered_tyKZCBEE.jpg', 'yochered');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `status` enum('confirmed','unconfirmed') NOT NULL DEFAULT 'unconfirmed',
  `token` varchar(10) NOT NULL,
  `notifications` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `pass`, `status`, `token`, `notifications`) VALUES
(5, 'yochered', 'evgeny.ocheredko@gmail.com', '4e0658d00f47d86d19a0e792e4bb94b16db2e902d307da5637f57cf60e7a174cb4bb6d7095621745b2065df0c87b77af69f5d0fbd63359ad3cc6b72f076c3e1e', 'confirmed', 'qZ5idM3YRM', 0),
(6, 'fdsa', 'fdsaf@fdsa', '4e0658d00f47d86d19a0e792e4bb94b16db2e902d307da5637f57cf60e7a174cb4bb6d7095621745b2065df0c87b77af69f5d0fbd63359ad3cc6b72f076c3e1e', 'unconfirmed', 'WAJHZzy1b2', 1),
(7, '; DROP DATABASE', 'fdsa@fdsa', '4e0658d00f47d86d19a0e792e4bb94b16db2e902d307da5637f57cf60e7a174cb4bb6d7095621745b2065df0c87b77af69f5d0fbd63359ad3cc6b72f076c3e1e', 'unconfirmed', 'eNVP1aDjVk', 1),
(8, 'o4eredko', 'o4eredko.crypto@gmail.com', '4e0658d00f47d86d19a0e792e4bb94b16db2e902d307da5637f57cf60e7a174cb4bb6d7095621745b2065df0c87b77af69f5d0fbd63359ad3cc6b72f076c3e1e', 'confirmed', 'ZA8R8Nnbgj', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `snapshots`
--
ALTER TABLE `snapshots`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT для таблицы `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `snapshots`
--
ALTER TABLE `snapshots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
