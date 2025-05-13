-- 1. Criar o Banco de Dados
-- O nome agora é 'simple-api'
CREATE DATABASE `simple-api`;

-- 2. Selecionar o Banco de Dados para uso
USE `simple-api`;

-- 3. Criar a tabela 'students'
CREATE TABLE `students` (
                            `id` INT AUTO_INCREMENT PRIMARY KEY,
                            `name` VARCHAR(255) NOT NULL,
                            `age` INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Opcional: Criar um usuário específico para a API e conceder privilégios
-- Use o mesmo usuário e senha que você configurou em Database.php
CREATE USER 'seu_usuario_do_banco'@'localhost' IDENTIFIED BY 'sua_senha_do_banco';

-- Conceder privilégios (SELECT, INSERT, UPDATE, DELETE) na tabela 'students' do banco 'simple-api'
GRANT SELECT, INSERT, UPDATE, DELETE ON `simple-api`.`students` TO 'seu_usuario_do_banco'@'localhost';

-- Aplicar as mudanças de privilégio
FLUSH PRIVILEGES;

-- Opcional: Inserir alguns dados de exemplo
INSERT INTO `students` (`name`, `age`) VALUES ('Alice', 20);
INSERT INTO `students` (`name`, `age`) VALUES ('Bob', 22);
INSERT INTO `students` (`name`, `age`) VALUES ('Charlie', 19);