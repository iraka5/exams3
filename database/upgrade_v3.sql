-- Script de mise à jour pour la V3 - Système de vente d'articles
USE `4191_4194_4222`;

-- ========== TABLE PARAMETRES ==========
-- Table pour stocker les paramètres configurables du système
CREATE TABLE IF NOT EXISTS `parametres` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `cle` VARCHAR(100) NOT NULL UNIQUE,
    `valeur` TEXT NOT NULL,
    `description` TEXT,
    `type` ENUM('integer', 'decimal', 'text', 'boolean') NOT NULL DEFAULT 'text',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertion du paramètre taux de diminution par défaut
INSERT IGNORE INTO `parametres` (`cle`, `valeur`, `description`, `type`) VALUES
('taux_diminution_vente', '10', 'Pourcentage de diminution appliqué lors de la vente d\'articles (défaut: 10%)', 'integer');

-- ========== MODIFICATION TABLE BESOINS ==========
-- Ajout du champ essentiel pour marquer les articles qui ne peuvent pas être vendus
ALTER TABLE `besoins` 
ADD COLUMN IF NOT EXISTS `essentiel` BOOLEAN NOT NULL DEFAULT FALSE AFTER `type_besoin`;

-- ========== MODIFICATION TABLE DONS ==========
-- Ajout du champ vendu pour marquer les articles vendus et convertis en argent
ALTER TABLE `dons` 
ADD COLUMN IF NOT EXISTS `vendu` BOOLEAN NOT NULL DEFAULT FALSE AFTER `id_ville`,
ADD COLUMN IF NOT EXISTS `prix_original` DECIMAL(10,2) DEFAULT NULL AFTER `vendu`,
ADD COLUMN IF NOT EXISTS `prix_vente` DECIMAL(10,2) DEFAULT NULL AFTER `prix_original`,
ADD COLUMN IF NOT EXISTS `date_vente` TIMESTAMP NULL DEFAULT NULL AFTER `prix_vente`;

-- ========== MISE À JOUR DES DONNÉES EXISTANTES ==========
-- Marquer certains besoins comme essentiels (exemple: Riz et Argent sont essentiels)
UPDATE `besoins` SET `essentiel` = TRUE 
WHERE `nom` IN ('Riz', 'Argent') AND `essentiel` = FALSE;

-- Ajouter des prix originaux aux dons existants pour permettre les calculs de vente
UPDATE `dons` SET `prix_original` = 1500.00 WHERE `type_don` = 'Riz' AND `prix_original` IS NULL;
UPDATE `dons` SET `prix_original` = 3500.00 WHERE `type_don` = 'Huile' AND `prix_original` IS NULL;  
UPDATE `dons` SET `prix_original` = 25000.00 WHERE `type_don` = 'Tôle' AND `prix_original` IS NULL;
UPDATE `dons` SET `prix_original` = 1.00 WHERE `type_don` = 'Argent' AND `prix_original` IS NULL;

-- ========== INDEX POUR PERFORMANCES ==========
CREATE INDEX IF NOT EXISTS idx_parametres_cle ON parametres(cle);
CREATE INDEX IF NOT EXISTS idx_besoins_essentiel ON besoins(essentiel);
CREATE INDEX IF NOT EXISTS idx_dons_vendu ON dons(vendu);
CREATE INDEX IF NOT EXISTS idx_dons_date_vente ON dons(date_vente);

-- ========== DONNÉES DE TEST ==========
-- Ajouter quelques dons supplémentaires pour tester la fonctionnalité de vente
INSERT IGNORE INTO `dons` (`nom_donneur`, `type_don`, `nombre_don`, `id_ville`, `prix_original`) VALUES
('Donateur Test 1', 'Camion', 1.00, 1, 500000.00),
('Donateur Test 2', 'Mobilier', 5.00, 2, 50000.00),
('Donateur Test 3', 'Matériaux', 10.00, 3, 15000.00);