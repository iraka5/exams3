<?php
/**
 * Script de diagnostic et configuration complète du projet BNGRC
 * Ce script vérifie tous les composants et installe ce qui manque
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1> Configuration et diagnostic BNGRC</h1>";

// Étape 1: Vérifier la configuration
echo "<h2>1.  Vérification de la configuration</h2>";

if (file_exists(__DIR__ . '/config/config.php')) {
    require_once __DIR__ . '/config/config.php';
    echo " Fichier config.php trouvé<br>";
} else {
    echo " Fichier config.php manquant<br>";
    exit;
}

// Étape 2: Tester la connexion à la base de données  
echo "<h2>2.  Test de connexion à la base de données</h2>";

try {
    $db = getDB();
    echo "Connexion à la base de données réussie<br>";
    
    // Vérifier la version
    $version = $db->query("SELECT VERSION() as version")->fetch();
    echo " Version de la base: " . $version['version'] . "<br>";
    
} catch (Exception $e) {
    echo " Erreur de connexion: " . $e->getMessage() . "<br>";
    
    // Essayer de créer la base de données
    echo " Tentative de création de la base de données...<br>";
    try {
        $tempDb = new PDO("mysql:host=localhost;charset=utf8", "root", "");
        $tempDb->exec("CREATE DATABASE IF NOT EXISTS `4191_4194_4222` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo " Base de données créée<br>";
        $db = getDB(); // Reconnection
    } catch (Exception $e2) {
        echo " Impossible de créer la base: " . $e2->getMessage() . "<br>";
        exit;
    }
}

// Étape 3: Creer/vérifier les tables
echo "<h2>3.  Création et vérification des tables</h2>";

$tables = [
    'users' => "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        nom VARCHAR(100) NOT NULL DEFAULT '',
        prenom VARCHAR(100) NOT NULL DEFAULT '',
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin','user') NOT NULL DEFAULT 'user',
        password_used BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    'regions' => "CREATE TABLE IF NOT EXISTS `regions` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nom` VARCHAR(100) NOT NULL UNIQUE,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    'ville' => "CREATE TABLE IF NOT EXISTS `ville` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `id_regions` INT NOT NULL,
        `nom` VARCHAR(100) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`id_regions`) REFERENCES `regions`(`id`) ON DELETE CASCADE
    )",
    
    'besoins' => "CREATE TABLE IF NOT EXISTS `besoins` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nom` VARCHAR(100) NOT NULL,
        `nombre` DECIMAL(10,2) NOT NULL,
        `id_ville` INT NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`id_ville`) REFERENCES `ville`(`id`) ON DELETE CASCADE
    )",
    
    'dons' => "CREATE TABLE IF NOT EXISTS `dons` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nom_donneur` VARCHAR(100) NOT NULL,
        `type_don` VARCHAR(100) NOT NULL,
        `nombre_don` DECIMAL(10,2) NOT NULL,
        `id_ville` INT NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`id_ville`) REFERENCES `ville`(`id`) ON DELETE CASCADE
    )",
    
    'user_connections' => "CREATE TABLE IF NOT EXISTS user_connections (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        email VARCHAR(255) NOT NULL,
        connection_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ip_address VARCHAR(45),
        user_agent TEXT,
        INDEX idx_user_id (user_id),
        INDEX idx_connection_date (connection_date),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )"
];

foreach ($tables as $tableName => $sql) {
    try {
        $db->exec($sql);
        echo " Table '$tableName' créée/vérifiée<br>";
    } catch (Exception $e) {
        echo " Erreur création table '$tableName': " . $e->getMessage() . "<br>";
    }
}

// Étape 4: Insérer les données de base
echo "<h2>4.  Insertion des données de test</h2>";

// Users 
try {
    // Admin par défaut
    $adminExists = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'")->fetch()['count'];
    if ($adminExists == 0) {
        $adminPass = password_hash('admin123', PASSWORD_DEFAULT);
        $db->prepare("INSERT INTO users (username, nom, prenom, email, password, role) VALUES (?, ?, ?, ?, ?, 'admin')")
           ->execute(['admin', 'Admin', 'BNGRC', 'admin@bngrc.mg', $adminPass]);
        echo " Administrateur créé (admin@bngrc.mg / admin123)<br>";
    }
    
    // Utilisateur test
    $userExists = $db->query("SELECT COUNT(*) as count FROM users WHERE email = 'user@test.com'")->fetch()['count'];
    if ($userExists == 0) {
        $userPass = password_hash('password123', PASSWORD_DEFAULT);
        $db->prepare("INSERT INTO users (username, nom, prenom, email, password, role, password_used) VALUES (?, ?, ?, ?, ?, 'user', FALSE)")
           ->execute(['user_test', 'Dupont', 'Jean', 'user@test.com', $userPass]);
        echo " Utilisateur test créé (user@test.com / password123)<br>";
    }
    
} catch (Exception $e) {
    echo " Erreur insertion utilisateurs: " . $e->getMessage() . "<br>";
}

// Régions
$regionsData = [
    'Analamanga', 'Vakinankaratra', 'Itasy', 'Bongolava'
];

foreach ($regionsData as $region) {
    try {
        $db->prepare("INSERT IGNORE INTO regions (nom) VALUES (?)")->execute([$region]);
    } catch (Exception $e) {
        // Ignore les doublons
    }
}
echo " Régions insérées<br>";

// Villes
$villesData = [
    [1, 'Antananarivo'], [1, 'Ambohidratrimo'],
    [2, 'Antsirabe'], [2, 'Betafo'],
    [3, 'Miarinarivo'], [4, 'Tsiroanomandidy']
];

foreach ($villesData as $ville) {
    try {
        $db->prepare("INSERT IGNORE INTO ville (id_regions, nom) VALUES (?, ?)")->execute($ville);
    } catch (Exception $e) {
        // Ignore les doublons
    }
}
echo " Villes insérées<br>";

// Quelques besoins et dons de test
try {
    $db->exec("INSERT IGNORE INTO besoins (nom, nombre, id_ville) VALUES 
        ('Riz', 500.00, 1), ('Huile', 100.00, 1), ('Tôle', 200.00, 2)");
    
    $db->exec("INSERT IGNORE INTO dons (nom_donneur, type_don, nombre_don, id_ville) VALUES 
        ('Jean Dupont', 'Riz', 300.00, 1), ('ONG Solidarité', 'Tôle', 150.00, 2)");
        
    echo " Données de test ajoutées<br>";
} catch (Exception $e) {
    echo " Erreur données test: " . $e->getMessage() . "<br>";
}

// Étape 5: Vérifier les contrôleurs
echo "<h2>5.  Vérification des contrôleurs</h2>";

$controllers = [
    'LoginController.php', 'UserController.php', 'RegionController.php', 
    'VilleController.php', 'BesoinController.php', 'DonController.php'
];

foreach ($controllers as $controller) {
    if (file_exists(__DIR__ . '/controllers/' . $controller)) {
        echo " $controller trouvé<br>";
    } else {
        echo " $controller manquant<br>";
    }
}

// Étape 6: Vérifier les vues principales
echo "<h2>6.  Vérification des vues</h2>";

$views = [
    'views/tableau_bord_simple.php',
    'views/users/login.php', 
    'views/users/register.php',
    'views/users/dashboard.php'
];

foreach ($views as $view) {
    if (file_exists(__DIR__ . '/' . $view)) {
        echo " $view trouvé<br>";
    } else {
        echo " $view manquant<br>";
    }
}

// Étape 7: Test de FlightPHP
echo "<h2>7.  Vérification de FlightPHP</h2>";

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo " Composer/FlightPHP installé<br>";
} else {
    echo " FlightPHP manquant. Exécutez 'composer install'<br>";
}

echo "<div style='background: #1e3a8a; border: 1px solid #1d4ed8; color: #ffffff; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
echo "<h2>🎉 Configuration terminée !</h2>";
echo "<h3>🔗 Liens d'accès :</h3>";
echo "<ul>";
echo "<li><strong>🏠 Accueil général :</strong> <a href='/exams3-main/exams3/'>http://localhost/exams3-main/exams3/</a></li>";
echo "<li><strong>👨‍💼 Connexion Admin :</strong> <a href='/exams3-main/exams3/login'>http://localhost/exams3-main/exams3/login</a></li>";
echo "<li><strong>👤 Connexion Utilisateur :</strong> <a href='/exams3-main/exams3/user/login'>http://localhost/exams3-main/exams3/user/login</a></li>";
echo "<li><strong>📝 Inscription Utilisateur :</strong> <a href='/exams3-main/exams3/user/register'>http://localhost/exams3-main/exams3/user/register</a></li>";
echo "</ul>";
echo "<h3>🔑 Comptes de test :</h3>";
echo "<p><strong>Administrateur :</strong> admin@bngrc.mg / admin123</p>";
echo "<p><strong>Utilisateur :</strong> user@test.com / password123</p>";
echo "</div>";

echo "<p><strong>⚡ Le projet BNGRC est maintenant opérationnel !</strong></p>";
?>