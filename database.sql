CREATE DATABASE IF NOT EXISTS `simple-api`;

USE `simple-api`;

CREATE TABLE IF NOT EXISTS `students` (
                                          `id` INT AUTO_INCREMENT PRIMARY KEY,
                                          `name` VARCHAR(255) NOT NULL,
                                          `age` INT NOT NULL
);

INSERT INTO `students` (`name`, `age`) VALUES
                                           ('João Silva', 18),
                                           ('Maria Souza', 19),
                                           ('Pedro Almeida', 17),
                                           ('Ana Costa', 20),
                                           ('Lucas Oliveira', 18);

CREATE TABLE IF NOT EXISTS `classrooms` (
                                            `id` INT AUTO_INCREMENT PRIMARY KEY,
                                            `name` VARCHAR(255) NOT NULL UNIQUE,
                                            `capacity` INT NOT NULL
);

INSERT INTO `classrooms` (`name`, `capacity`) VALUES
                                                  ('Sala 101', 30),
                                                  ('Sala 102', 25),
                                                  ('Laboratório A', 20),
                                                  ('Auditório Principal', 100),
                                                  ('Sala de Reuniões', 15);