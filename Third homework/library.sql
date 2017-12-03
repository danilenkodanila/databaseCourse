CREATE TABLE `readers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `readers` (`id`, `name`, `email`, `phone`) VALUES
('1', 'Алексей', 'al@mail.com', '+79140719727'),
('2', 'Иван', 'iv@mail.com', '+79140719728'),
('3', 'Георгий', 'ge@mail.com', '+79140719729'),
('4', 'Екатерина', 'ek@mail.com', '+79140719730'),
('5', 'Евгений', 'eugen@mail.com', '+79140719731'),
('6', 'Евгения', 'eugenia@mail.com', '+79140719732'),
('7', 'Александра', 'alex@mail.com', '+79140719733');

CREATE TABLE `genre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `genre` (`id`, `name`) VALUES
('1', 'Автобиография'),
('2', 'Научная фантастика'),
('3', 'Роман-эпопея'),
('4', 'Образование'),
('5', 'Философия'),
('6', 'Утопия'),
('7', 'Сатира');

CREATE TABLE `authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `authors` (`id`, `name`) VALUES
('1', 'Брэдбери'),
('2', 'Тиньков'),
('3', 'Толстой'),
('4', 'Паланик'),
('5', 'Соколов-Митрич'),
('6', 'Кафка'),
('7', 'Уэлш'),
('8', 'Буковски'),
('9', 'Хемингуэй'),
('10', 'Твен'),
('11', 'Пушкин'),
('12', 'Тургенев');

CREATE TABLE `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_author` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `id_genre` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
   PRIMARY KEY (`id`),
   FOREIGN KEY (`id_author`) REFERENCES `authors` (`id`),
   FOREIGN KEY (`id_genre`) REFERENCES `genre` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `books` (`id`, `id_author`, `title`, `id_genre`, `price`, `amount`) VALUES
('1', '1', '451 градус по Фаренгейту', '6', '200', '2'),
('2', '1', 'Вино из одуванчиков', '2', '300', '20'),
('3', '2', 'Я такой как все', '1', '2000', '10'),
('4', '3', 'Война и Мир', '3', '300', '90'),
('5', '4', 'Бойцовский клуб', '7', '200', '+2'),
('6', '5', 'Яндекс.Книга', '4', '210', '52'),
('7', '6', 'Замок', '5', '100', '5');

CREATE TABLE `issue` (
  `id_reader` int(11) NOT NULL,
  `id_book` int(11) NOT NULL,
  `date_s` date NOT NULL,
  `date_e` date,
   FOREIGN KEY (`id_reader`) REFERENCES `readers` (`id`),
   FOREIGN KEY (`id_book`) REFERENCES `books` (`id`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;