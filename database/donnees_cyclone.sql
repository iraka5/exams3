
SELECT id, nom FROM ville WHERE nom IN ('Toamasina', 'Mananjary', 'Farafangana', 'Nosy Be', 'Morondava');


INSERT INTO ville (nom, id_regions) VALUES 
('Mananjary', 1),  
('Farafangana', 1),
('Nosy Be', 1),
('Morondava', 1);



INSERT INTO besoins (nom, nombre, prix_unitaire, type_besoin, id_ville, created_at) VALUES
('Riz (kg)', 800, 3000, 'nature', (SELECT id FROM ville WHERE nom = 'Toamasina'), '2026-02-16'),
('Eau (L)', 1500, 1000, 'nature', (SELECT id FROM ville WHERE nom = 'Toamasina'), '2026-02-15'),
('Tôle', 120, 25000, 'materiel', (SELECT id FROM ville WHERE nom = 'Toamasina'), '2026-02-16'),
('Bâche', 200, 15000, 'materiel', (SELECT id FROM ville WHERE nom = 'Toamasina'), '2026-02-15'),
('Argent', 12000000, 1, 'argent', (SELECT id FROM ville WHERE nom = 'Toamasina'), '2026-02-16'),
('groupe', 3, 6750000, 'materiel', (SELECT id FROM ville WHERE nom = 'Toamasina'), '2026-02-15');

-- Mananjary
INSERT INTO besoins (nom, nombre, prix_unitaire, type_besoin, id_ville, created_at) VALUES
('Riz (kg)', 500, 3000, 'nature', (SELECT id FROM ville WHERE nom = 'Mananjary'), '2026-02-15'),
('Huile (L)', 120, 6000, 'nature', (SELECT id FROM ville WHERE nom = 'Mananjary'), '2026-02-16'),
('Tôle', 80, 25000, 'materiel', (SELECT id FROM ville WHERE nom = 'Mananjary'), '2026-02-15'),
('Clous (kg)', 60, 8000, 'materiel', (SELECT id FROM ville WHERE nom = 'Mananjary'), '2026-02-16'),
('Argent', 6000000, 1, 'argent', (SELECT id FROM ville WHERE nom = 'Mananjary'), '2026-02-15');

-- Farafangana
INSERT INTO besoins (nom, nombre, prix_unitaire, type_besoin, id_ville, created_at) VALUES
('Riz (kg)', 600, 3000, 'nature', (SELECT id FROM ville WHERE nom = 'Farafangana'), '2026-02-16'),
('Eau (L)', 1000, 1000, 'nature', (SELECT id FROM ville WHERE nom = 'Farafangana'), '2026-02-15'),
('Bâche', 150, 15000, 'materiel', (SELECT id FROM ville WHERE nom = 'Farafangana'), '2026-02-16'),
('Bois', 100, 10000, 'materiel', (SELECT id FROM ville WHERE nom = 'Farafangana'), '2026-02-15'),
('Argent', 8000000, 1, 'argent', (SELECT id FROM ville WHERE nom = 'Farafangana'), '2026-02-16');

-- Nosy Be
INSERT INTO besoins (nom, nombre, prix_unitaire, type_besoin, id_ville, created_at) VALUES
('Riz (kg)', 300, 3000, 'nature', (SELECT id FROM ville WHERE nom = 'Nosy Be'), '2026-02-15'),
('Haricots', 200, 4000, 'nature', (SELECT id FROM ville WHERE nom = 'Nosy Be'), '2026-02-16'),
('Tôle', 40, 25000, 'materiel', (SELECT id FROM ville WHERE nom = 'Nosy Be'), '2026-02-15'),
('Clous (kg)', 30, 8000, 'materiel', (SELECT id FROM ville WHERE nom = 'Nosy Be'), '2026-02-16'),
('Argent', 4000000, 1, 'argent', (SELECT id FROM ville WHERE nom = 'Nosy Be'), '2026-02-15');

-- Morondava
INSERT INTO besoins (nom, nombre, prix_unitaire, type_besoin, id_ville, created_at) VALUES
('Riz (kg)', 700, 3000, 'nature', (SELECT id FROM ville WHERE nom = 'Morondava'), '2026-02-16'),
('Eau (L)', 1200, 1000, 'nature', (SELECT id FROM ville WHERE nom = 'Morondava'), '2026-02-15'),
('Bâche', 180, 15000, 'materiel', (SELECT id FROM ville WHERE nom = 'Morondava'), '2026-02-16'),
('Bois', 150, 10000, 'materiel', (SELECT id FROM ville WHERE nom = 'Morondava'), '2026-02-15'),
('Argent', 10000000, 1, 'argent', (SELECT id FROM ville WHERE nom = 'Morondava'), '2026-02-16');