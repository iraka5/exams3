

CREATE DATABASE IF NOT EXISTS `4191_4194_4222` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `4191_4194_4222`;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user'
);


CREATE TABLE `regions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `ville` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `id_regions` INT NOT NULL,
    `nom` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_regions`) REFERENCES `regions`(`id`) ON DELETE CASCADE
);


CREATE TABLE `besoins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL,
    `nombre` DECIMAL(10,2) NOT NULL,
    `id_ville` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_ville`) REFERENCES `ville`(`id`) ON DELETE CASCADE
);


CREATE TABLE `dons` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom_donneur` VARCHAR(100) NOT NULL,
    `type_don` VARCHAR(100) NOT NULL,
    `nombre_don` DECIMAL(10,2) NOT NULL,
    `id_ville` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_ville`) REFERENCES `ville`(`id`) ON DELETE CASCADE
);

CREATE TABLE `echange` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `id_besoins` INT NOT NULL,
    `id_don` INT NOT NULL,
    `nombre_echangee` DECIMAL(10,2) NOT NULL,
    `date_don` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_besoins`) REFERENCES `besoins`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_don`) REFERENCES `dons`(`id`) ON DELETE CASCADE
);


INSERT INTO `regions` (`nom`) VALUES 
('Analamanga'),
('Vakinankaratra'), 
('Itasy'),
('Bongolava');

INSERT INTO `ville` (`id_regions`, `nom`) VALUES
(1, 'Antananarivo'),
(1, 'Ambohidratrimo'),
(2, 'Antsirabe'),
(2, 'Betafo'),
(3, 'Miarinarivo'),
(4, 'Tsiroanomandidy');

INSERT INTO `besoins` (`nom`, `nombre`, `id_ville`) VALUES
('Riz', 500.00, 1),
('Huile', 100.00, 1),
('Tôle', 200.00, 2),
('Clou', 50.00, 2),
('Argent', 1000000.00, 3);

INSERT INTO `dons` (`nom_donneur`, `type_don`, `nombre_don`, `id_ville`) VALUES
('Jean Dupont', 'Riz', 300.00, 1),
('Marie Claire', 'Huile', 80.00, 1),
('ONG Solidarité', 'Tôle', 150.00, 2),
('Entreprise ABC', 'Argent', 500000.00, 3);