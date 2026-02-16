<?php
/**
 * Script d'installation des extensions utilisateur pour la base de données BNGRC
 * Ce script doit être exécuté une seule fois pour mettre en place les tables et données utilisateur
 */

require_once __DIR__ . '/config/config.php';

echo "<h2>🔧 Installation des extensions utilisateur BNGRC</h2>";

try {
    $db = getDB();
    
    echo "<p>📡 Connexion à la base de données réussie...</p>";
    
    // Vérifier si les colonnes existent déjà
    $checkColumns = $db->query("SHOW COLUMNS FROM users LIKE 'nom'");
    if ($checkColumns->rowCount() == 0) {
        // Modifier la table users pour ajouter les nouveaux champs
        echo "<p>🔄 Mise à jour de la table users...</p>";
        $db->exec("ALTER TABLE users 
                   ADD COLUMN nom VARCHAR(100) NOT NULL AFTER username,
                   ADD COLUMN prenom VARCHAR(100) NOT NULL AFTER nom,
                   ADD COLUMN password_used BOOLEAN DEFAULT FALSE AFTER password,
                   ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        echo "<p>✅ Table users mise à jour</p>";
    } else {
        echo "<p>ℹ️ Table users déjà mise à jour</p>";
    }
    
    // Créer la table user_connections si elle n'existe pas
    $checkTable = $db->query("SHOW TABLES LIKE 'user_connections'")->rowCount();
    if ($checkTable == 0) {
        echo "<p>🔄 Création de la table user_connections...</p>";
        $db->exec("CREATE TABLE user_connections (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            email VARCHAR(255) NOT NULL,
            connection_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            ip_address VARCHAR(45),
            user_agent TEXT,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )");
        echo "<p>✅ Table user_connections créée</p>";
    } else {
        echo "<p>ℹ️ Table user_connections existe déjà</p>";
    }
    
    // Vérifier si l'utilisateur test existe
    $checkUser = $db->query("SELECT id FROM users WHERE email = 'user@test.com'")->rowCount();
    if ($checkUser == 0) {
        echo "<p>🔄 Création de l'utilisateur de test...</p>";
        $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
        $db->prepare("INSERT INTO users (username, nom, prenom, email, password, role, password_used) VALUES (?, ?, ?, ?, ?, 'user', FALSE)")
           ->execute(['user_test', 'Dupont', 'Jean', 'user@test.com', $hashedPassword]);
        echo "<p>✅ Utilisateur de test créé (email: user@test.com, mot de passe: password123)</p>";
    } else {
        echo "<p>ℹ️ Utilisateur de test existe déjà</p>";
    }
    
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>🎉 Installation terminée avec succès !</h3>";
    echo "<p><strong>Système utilisateur opérationnel :</strong></p>";
    echo "<ul>";
    echo "<li>✅ Tables étendues pour gérer les utilisateurs avec noms/prénoms</li>";
    echo "<li>✅ Système de mots de passe à usage unique implémenté</li>";
    echo "<li>✅ Tracking des connexions utilisateur activé</li>";
    echo "<li>✅ Utilisateur de test disponible</li>";
    echo "</ul>";
    echo "<br>";
    echo "<h4>🔗 Liens d'accès :</h4>";
    echo "<p><strong>Inscription utilisateur :</strong> <a href='/exams3-main/exams3/user/register'>/user/register</a></p>";
    echo "<p><strong>Connexion utilisateur :</strong> <a href='/exams3-main/exams3/user/login'>/user/login</a></p>";
    echo "<p><strong>Connexion admin :</strong> <a href='/exams3-main/exams3/login'>/login</a></p>";
    echo "<br>";
    echo "<h4>📝 Compte de test :</h4>";
    echo "<p><strong>Email :</strong> user@test.com<br><strong>Mot de passe :</strong> password123</p>";
    echo "<p style='color: #856404; background: #fff3cd; padding: 10px; border-radius: 4px;'>";
    echo "<strong>⚠️ Important :</strong> Ce mot de passe ne peut être utilisé qu'une seule fois. Après la première connexion, vous devrez créer un nouveau compte.";
    echo "</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 20px; border-radius: 8px;'>";
    echo "<h3>❌ Erreur lors de l'installation</h3>";
    echo "<p>Erreur : " . $e->getMessage() . "</p>";
    echo "</div>";
}
?>