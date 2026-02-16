-- Extension de la base de données pour les utilisateurs
USE `4191_4194_4222`;

-- Créer la table users si elle n'existe pas
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    password_used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Vérifier si les colonnes existent déjà, sinon les ajouter
-- (Cette approche évite les erreurs si la table existe déjà avec une structure différente)

-- Créer une table pour enregistrer les connexions utilisateur
CREATE TABLE IF NOT EXISTS user_connections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    connection_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    INDEX idx_user_id (user_id),
    INDEX idx_connection_date (connection_date),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insérer un utilisateur de test (ignorer si il existe déjà)
INSERT IGNORE INTO users (username, nom, prenom, email, password, role, password_used) VALUES 
('user_test', 'Dupont', 'Jean', 'user@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', FALSE);