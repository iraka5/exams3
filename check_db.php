<?php
require_once __DIR__ . '/config/config.php';

echo "=== Diagnostic de la base de données ===\n";

try {
    $pdo = getDB();
    echo "✓ Connexion à la base de données réussie\n\n";
    
    // Vérifier quelles tables existent
    echo "Tables existantes :\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    
    echo "\n";
    
    // Vérifier la structure de la table users si elle existe
    if (in_array('users', $tables)) {
        echo "Structure de la table 'users' :\n";
        $columns = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $column) {
            echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
        }
        
        echo "\nUtilisateurs dans la table 'users' :\n";
        $users = $pdo->query("SELECT id, username, email, role FROM users")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $user) {
            echo "- ID: {$user['id']}, Username: {$user['username']}, Email: {$user['email']}, Role: {$user['role']}\n";
        }
    }
    
    // Vérifier la structure de la table user si elle existe
    if (in_array('user', $tables)) {
        echo "Structure de la table 'user' :\n";
        $columns = $pdo->query("DESCRIBE user")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $column) {
            echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
        }
        
        echo "\nUtilisateurs dans la table 'user' :\n";
        $users = $pdo->query("SELECT id, nom, email, role FROM user")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $user) {
            echo "- ID: {$user['id']}, Nom: {$user['nom']}, Email: {$user['email']}, Role: {$user['role']}\n";
        }
    }
    
} catch (Exception $e) {
    echo " Erreur : " . $e->getMessage() . "\n";
}
?>