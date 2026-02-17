-- Script de mise à jour pour le système d'achats
USE `4191_4194_4222`;

-- Ajout des colonnes prix_unitaire et type_besoin à la table besoins (seulement si elles n'existent pas)
ALTER TABLE `besoins` 
ADD COLUMN IF NOT EXISTS `prix_unitaire` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `nombre`,
ADD COLUMN IF NOT EXISTS `type_besoin` ENUM('nature', 'materiaux', 'argent') NOT NULL DEFAULT 'nature' AFTER `prix_unitaire`;

-- Création de la table achats (seulement si elle n'existe pas)
CREATE TABLE IF NOT EXISTS `achats` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `id_besoin` INT NOT NULL,
    `quantite` DECIMAL(10,2) NOT NULL,
    `montant_total` DECIMAL(10,2) NOT NULL,
    `id_ville` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_besoin`) REFERENCES `besoins`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_ville`) REFERENCES `ville`(`id`) ON DELETE CASCADE
);

-- Mise à jour des données existantes avec des prix unitaires et types (seulement si pas déjà mise à jour)
UPDATE `besoins` SET 
    `prix_unitaire` = 1500.00, 
    `type_besoin` = 'nature' 
WHERE `nom` = 'Riz' AND `prix_unitaire` = 0;

UPDATE `besoins` SET 
    `prix_unitaire` = 3500.00, 
    `type_besoin` = 'nature' 
WHERE `nom` = 'Huile' AND `prix_unitaire` = 0;

UPDATE `besoins` SET 
    `prix_unitaire` = 25000.00, 
    `type_besoin` = 'materiaux' 
WHERE `nom` = 'Tôle' AND `prix_unitaire` = 0;

UPDATE `besoins` SET 
    `prix_unitaire` = 500.00, 
    `type_besoin` = 'materiaux' 
WHERE `nom` = 'Clou' AND `prix_unitaire` = 0;

UPDATE `besoins` SET 
    `prix_unitaire` = 1.00, 
    `type_besoin` = 'argent' 
WHERE `nom` = 'Argent' AND `prix_unitaire` = 0;

-- Insertion de données de test pour les achats (seulement si la table est vide)
INSERT IGNORE INTO `achats` (`id_besoin`, `quantite`, `montant_total`, `id_ville`) VALUES
(1, 100.00, 150000.00, 1),  -- Riz: 100 * 1500 = 150000
(2, 20.00, 70000.00, 1),    -- Huile: 20 * 3500 = 70000
(3, 10.00, 250000.00, 2),   -- Tôle: 10 * 25000 = 250000
(4, 50.00, 25000.00, 2);    -- Clou: 50 * 500 = 25000

-- Ajout d'index pour améliorer les performances (seulement si ils n'existent pas)
CREATE INDEX IF NOT EXISTS idx_achats_ville ON achats(id_ville);
CREATE INDEX IF NOT EXISTS idx_achats_besoin ON achats(id_besoin);
CREATE INDEX IF NOT EXISTS idx_besoins_type ON besoins(type_besoin);