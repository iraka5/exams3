-- Script de reset des données de base (seed reset) - V3
USE `4191_4194_4222`;

-- ========== DÉSACTIVATION DES CONTRAINTES ==========
SET FOREIGN_KEY_CHECKS = 0;

-- ========== VIDAGE DES TABLES (sauf users et parametres) ==========
-- On garde les utilisateurs et paramètres, mais on remet les données de base pour le reste
TRUNCATE TABLE `echange`;
TRUNCATE TABLE `achats`;
DELETE FROM `dons` WHERE id > 0;
DELETE FROM `besoins` WHERE id > 0;
-- Ne pas supprimer les villes et régions car elles peuvent être référencées

-- ========== RÉINITIALISATION DES AUTO_INCREMENT ==========
ALTER TABLE `dons` AUTO_INCREMENT = 1;
ALTER TABLE `besoins` AUTO_INCREMENT = 1; 
ALTER TABLE `echange` AUTO_INCREMENT = 1;
ALTER TABLE `achats` AUTO_INCREMENT = 1;

-- ========== RESTAURATION DES DONNÉES DE BASE ==========

-- BESOINS DE BASE avec les nouveaux champs
INSERT INTO `besoins` (`nom`, `nombre`, `prix_unitaire`, `type_besoin`, `essentiel`, `id_ville`) VALUES
-- Besoins essentiels (ne peuvent pas être vendus)
('Riz', 500.00, 1500.00, 'nature', TRUE, 1),
('Argent', 1000000.00, 1.00, 'argent', TRUE, 3),
-- Besoins non essentiels (peuvent être vendus si plus demandés)
('Huile', 100.00, 3500.00, 'nature', FALSE, 1),
('Tôle', 200.00, 25000.00, 'materiaux', FALSE, 2),
('Clou', 50.00, 500.00, 'materiaux', FALSE, 2),
-- Besoins supplémentaires pour tester le système
('Ciment', 30.00, 45000.00, 'materiaux', FALSE, 1),
('Couverture', 100.00, 15000.00, 'nature', FALSE, 2),
('Médicaments', 50.00, 8000.00, 'nature', TRUE, 3);

-- DONS DE BASE avec les nouveaux champs
INSERT INTO `dons` (`nom_donneur`, `type_don`, `nombre_don`, `id_ville`, `vendu`, `prix_original`) VALUES
-- Dons correspondant aux besoins
('Jean Dupont', 'Riz', 300.00, 1, FALSE, 1500.00),
('Marie Claire', 'Huile', 80.00, 1, FALSE, 3500.00),
('ONG Solidarité', 'Tôle', 150.00, 2, FALSE, 25000.00),
('Entreprise ABC', 'Argent', 500000.00, 3, FALSE, 1.00),
-- Dons supplémentaires qui pourraient être vendus
('Donateur Généreux', 'Camion', 1.00, 1, FALSE, 500000.00),
('Association Locale', 'Mobilier Bureau', 10.00, 2, FALSE, 25000.00),
('Particulier', 'Ordinateurs', 5.00, 1, FALSE, 100000.00),
('Entreprise XYZ', 'Matériaux Divers', 20.00, 3, FALSE, 15000.00),
-- Exemples de dons déjà vendus (pour démonstration)
('Ancien Donateur', 'Véhicule Ancien', 1.00, 2, TRUE, 300000.00, 270000.00, NOW() - INTERVAL 7 DAY);

-- ========== QUELQUES ÉCHANGES DE BASE ==========
-- On remet quelques échanges pour que le système soit réaliste
INSERT INTO `echange` (`id_besoins`, `id_don`, `nombre_echangee`, `date_don`) VALUES
(1, 1, 200.00, NOW() - INTERVAL 3 DAY),  -- 200 kg de riz échangés
(3, 2, 50.00, NOW() - INTERVAL 2 DAY),   -- 50L d'huile échangées  
(4, 3, 100.00, NOW() - INTERVAL 1 DAY);  -- 100 tôles échangées

-- ========== RÉACTIVATION DES CONTRAINTES ==========
SET FOREIGN_KEY_CHECKS = 1;

-- ========== VÉRIFICATION ==========
-- Compter les enregistrements restaurés
SELECT 
    (SELECT COUNT(*) FROM regions) as regions_count,
    (SELECT COUNT(*) FROM ville) as villes_count,
    (SELECT COUNT(*) FROM besoins) as besoins_count,
    (SELECT COUNT(*) FROM dons) as dons_count,
    (SELECT COUNT(*) FROM echange) as echanges_count,
    (SELECT COUNT(*) FROM parametres) as parametres_count;