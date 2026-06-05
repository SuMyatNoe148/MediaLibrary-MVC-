CREATE DATABASE IF NOT EXISTS `Database01` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `Database01`;


-- 1. PEOPLE TABLE
DROP TABLE IF EXISTS `People`;
CREATE TABLE `People` (
  `people_id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  PRIMARY KEY (`people_id`)
);

INSERT INTO `People` (`fullname`) VALUES 
('Erich Gamma'),('Richard Helm'),('Ralph Johnson'),('John Vlissides'),('Robert C. Martin'),
('Andrew Hunt'),('David Thomas'),('Martin Fowler'),('Steve McConnell'),('David Sklar'),
('Matt Zandstra'),('Matt Stauffer'),('Douglas Crockford'),('Marijn Haverbeke'),('Eric Freeman'),
('Elisabeth Robson'),('Thomas H. Cormen'),('Charles E. Leiserson'),('Ronald L. Rivest'),('Clifford Stein'),
('Robert Zemeckis'),('Tom Hanks'),('Robin Wright'),('Gary Sinise'),('Frank Darabont'),
('Tim Robbins'),('Morgan Freeman'),('Christopher Nolan'),('Leonardo DiCaprio'),('Joseph Gordon-Levitt'),
('Brian De Palma'),('Tom Cruise'),('Christian Bale'),('Heath Ledger'),('Rob Reiner'),
('Cary Elwes'),('Mandy Patinkin'),('Michael Jackson'),('AC/DC'),('Pink Floyd'),
('Adele'),('The Beatles'),('Elvis Presley'),('Garth Brooks');

-- 2. GENRES TABLE
DROP TABLE IF EXISTS `Genres`;
CREATE TABLE `Genres` (
  `genre_id` INT NOT NULL AUTO_INCREMENT,
  `genre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`genre_id`)
);

INSERT INTO `Genres`(`genre`)VALUES 
('Tech'),('Drama'),('Sci-Fi'),('Action'),('Fantasy'),
('Pop'),('Rock'),('Progressive Rock'),('Soul'),('Rock & Roll'),('Country');

-- 3. GENRE_CATEGORIES TABLE
DROP TABLE IF EXISTS `Media_Types`;
CREATE TABLE `Media_Types` (
  `media_types_id` INT NOT NULL auto_increment,
  `category` VARCHAR(6) NOT NULL, #maybe del
  PRIMARY KEY (`media_types_id`)
);

INSERT INTO `Media_Types`(`category`) VALUES 
('Books'),('Movies'),('Music');


-- 4. MEDIA TABLE
DROP TABLE IF EXISTS `Media`;
CREATE TABLE `Media` (
  `media_id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `img` VARCHAR(255) NOT NULL,
  `genre_id` INT NOT NULL,
  `format` VARCHAR(25) NOT NULL,
  `year` INT NOT NULL,
  `media_types_id` INT NOT NULL,
  `price` DECIMAL(10, 2) DEFAULT 0.00,
  PRIMARY KEY (`media_id`),
  CONSTRAINT `fk_media_genre`
    FOREIGN KEY (`genre_id`)
    REFERENCES `Genres` (`genre_id`)
    ON DELETE RESTRICT,
    CONSTRAINT `fk_media_types`
    FOREIGN KEY (`media_types_id`)
    REFERENCES `Media_Types` (`media_types_id`)
    ON DELETE RESTRICT
);
INSERT INTO `Media`(`title`,`img`,`genre_id`,`format`, `year`,`media_types_id`,`price`) VALUES 
('Design Patterns','Public/assets/img/books/design-patterns.jpg',1,'Paperback',1994,1,45.99),
('Clean Code','Public/assets/img/books/clean-code.jpg',1,'Paperback',2008,1,39.99),
('The Pragmatic Programmer','Public/assets/img/books/pragmatic-programmer.jpg',1,'Paperback',1999,1,42.50),
('Refactoring','Public/assets/img/books/refactoring.jpg',1,'Hardcover',1999,1,55.00),
('Code Complete','Public/assets/img/books/code-complete.jpg',1,'Paperback',2004,1,48.75),
('Learning PHP','Public/assets/img/books/learning-php.jpg',1,'Paperback',2014,1,29.99),
('PHP Objects, Patterns, and Practice','Public/assets/img/books/php-patterns.jpg',1,'Paperback',2010,1,44.50),
('Laravel Up & Running','Public/assets/img/books/laravel.jpg',1,'Paperback',2019,1,35.00),
('JavaScript: The Good Parts','Public/assets/img/books/js-good-parts.jpg',1,'Paperback',2008,1,24.99),
('Eloquent JavaScript','Public/assets/img/books/eloquent-js.jpg',1,'Paperback',2018,1,32.50),
('Head First Design Patterns','Public/assets/img/books/head-first-design-patterns.jpg',1,'Paperback',2004,1,38.99),
('Introduction to Algorithms','Public/assets/img/books/algorithms.jpg',1,'Hardcover',2009,1,75.00),
('Forrest Gump','Public/assets/img/movies/forestgump.jpg',2,'DVD',1994,2,12.99),
('The Shawshank Redemption','Public/assets/img/movies/shawshank.jpg',2,'Blu-ray',1994,2,15.50),
('Inception','Public/assets/img/movies/inception.jpg',3,'Blu-ray',2010,2,18.99),
('Mission: Impossible','Public/assets/img/movies/mission-Impossible.jpg',4,'DVD',1996,2,9.99),
('The Dark Knight','Public/assets/img/movies/dark-knight.jpg',4,'Blu-ray',2008,2,14.50),
('The Princess Bride','Public/assets/img/movies/princess.jpg',5,'DVD',1987,2,8.99),
('Thriller','Public/assets/img/music/thriller.jpg',6,'CD',1982,3,15.99),
('Back in Black','Public/assets/img/music/back-in-black.jpg',7,'CD',1980,3,12.50),
('The Dark Side of the Moon','Public/assets/img/music/dark-side-moon.jpg',8,'Vinyl',1973,3,35.00),
('21','Public/assets/img/music/adele-21.jpg',9,'CD',2011,3,14.99),
('Abbey Road','Public/assets/img/music/abbey-road.jpg',7,'Vinyl',1969,3,28.50),
('Elvis Presley','Public/assets/img/music/ElvisPresley.jpg',10,'CD',1956,3,18.99),
('Garth Brooks','Public/assets/img/music/Brooks_Garth.jpg',11,'CD',1990,3,16.50);

-- 5. People Role TABLE
DROP TABLE IF EXISTS `Role`;
CREATE TABLE `Role` (
  `role_id` INT NOT NULL auto_increment,
  `role` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`role_id`)
);

INSERT INTO `Role`(`role`) VALUES 
('Author'),('Director'),('Star'),('Artist');


-- 6. MEDIA_PEOPLE TABLE (Linking IDs)
DROP TABLE IF EXISTS `Media_People`;
CREATE TABLE `Media_People` (
  `media_people_id` INT NOT NULL auto_increment,
  `media_id` INT NOT NULL,
  `people_id` INT NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`media_people_id`),
  CONSTRAINT `fk_mp_media`
    FOREIGN KEY (`media_id`)
    REFERENCES `Media` (`media_id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_mp_people`
    FOREIGN KEY (`people_id`)
    REFERENCES `People` (`people_id`)
    ON DELETE CASCADE,
      CONSTRAINT `fk_mp_role`
    FOREIGN KEY (`role_id`)
    REFERENCES `Role` (`role_id`)
    ON DELETE CASCADE
);

INSERT INTO `Media_People`(`media_id`,`people_id`,`role_id`) VALUES
(1,1,1),(1,2,1),(1,3,1),(1,4,1),
(2,5,1),(3,6,1),(3,7,1),(4,8,1),
(5,9,1),(6,10,1),(7,11,1),(8,12,1),
(9,13,1),(10,14,1),(11,15,1),(11,16,1),
(12,17,1),(12,18,1),(12,19,1),(12,20,1),
(13,21,2),(13,22,3),(13,23,3),(13,24,3),
(14,25,2),(14,26,3),(14,27,3),
(15,28,2),(15,29,3),(15,30,3),
(16,31,2),(16,32,3),
(17,28,2),(17,33,3),(17,34,3),
(18,35,2),(18,36,3),(18,23,3),(18,37,3),
(19,38,4),
(20,39,4),
(21,40,4),
(22,41,4),
(23,42,4),
(24,43,4),
(25,44,4);




-- 7. BOOKS TABLE (ISBN & Publisher)
DROP TABLE IF EXISTS `Books`;
CREATE TABLE `Books` (
`book_id` INT NOT NULL auto_increment,
  `media_id` INT NOT NULL,
  `publisher` VARCHAR(50) NOT NULL,
  `isbn` VARCHAR(14) NOT NULL,
  PRIMARY KEY (`book_id`),
  CONSTRAINT `fk_books_media`
    FOREIGN KEY (`media_id`)
    REFERENCES `Media` (`media_id`)
    ON DELETE CASCADE
);

INSERT INTO `Books`(`media_id`,`publisher`,`isbn`) VALUES
(1,'Prentice Hall','9780201633610'),
(2,'Prentice Hall','9780132350884'),
(3,'Addison-Wesley','9780201616224'),
(4,'Addison-Wesley','9780201485677'),
(5,'Microsoft Press','9780735619678'),
(6,'O\'Reilly','9781449361068'),
(7,'Apress','9781430229254'),
(8,'O\'Reilly','9781492041214'),
(9,'O\'Reilly','9780596517748'),
(10,'No Starch Press','9781593279509'),
(11,'O\'Reilly','9780596007126'),
(12,'MIT Press','9780262033848');




























