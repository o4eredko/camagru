CREATE TABLE IF NOT EXISTS `comments` (
                            `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                            `owner` varchar(30) NOT NULL,
                            `text` text NOT NULL,
                            `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `likes`  (
                         `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                         `owner` varchar(30) NOT NULL,
                         `post_id` int(11) NOT NULL
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `posts` (
                         `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                         `owner` varchar(30) NOT NULL,
                         `title` varchar(255) NOT NULL,
                         `description` text NOT NULL,
                         `img` varchar(255) NOT NULL,
                         `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `snapshots` (
                             `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                             `img` varchar(255) NOT NULL,
                             `owner` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
                         `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                         `username` varchar(50) NOT NULL,
                         `email` varchar(30) NOT NULL,
                         `pass` varchar(255) NOT NULL,
                         `status` enum('confirmed','unconfirmed') NOT NULL DEFAULT 'unconfirmed',
                         `token` varchar(10) NOT NULL,
                         `notifications` tinyint(1) NOT NULL DEFAULT '1',
                         `avatar` varchar(255) NOT NULL DEFAULT 'img/profile.png',
                         `info` varchar(255),
                         `about` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
