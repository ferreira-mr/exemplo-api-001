CREATE DATABASE `simple-api`;

USE `simple-api`;

CREATE TABLE `students` (
                            `id` INT AUTO_INCREMENT PRIMARY KEY,
                            `name` VARCHAR(255) NOT NULL,
                            `age` INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `students` (`name`, `age`) VALUES ('Alice', 20);
INSERT INTO `students` (`name`, `age`) VALUES ('Bob', 22);
INSERT INTO `students` (`name`, `age`) VALUES ('Charlie', 19);