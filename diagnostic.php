<?php
// diagnostic.php - Page de diagnostic pour vérifier la configuration

require_once __DIR__ . '/vendor/autoload.php';

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Diagnostic BNGRC</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 4px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border-radius: 4px; margin: 10px 0; }
        .info { color: blue; padding: 10px; background: #d1ecf1; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
<h1>🔍 Diagnostic de l'Application BNGRC</h1>";

// Test 1: Vérification de FlightPHP
echo "<h2>1. Test FlightPHP</h2>";
if (class_exists('Flight')) {
    echo "<div class='success'>✅ FlightPHP est correctement installé</div>";
} else {
    echo "<div class='error'>❌ FlightPHP n'est pas disponible</div>";
}

// Test 2: Base de données
echo "<h2>2. Test Base de Données</h2>";
try {
    require_once __DIR__ . '/config/config.php';
    $db = getDB();
    echo "<div class='success'>✅ Connexion à la base de données réussie</div>";
    
    // Test des tables
    $tables = ['regions', 'ville', 'besoins', 'dons', 'user'];
    foreach ($tables as $table) {
        try {
            $stmt = $db->query("SELECT COUNT(*) as count FROM `$table`");
            $result = $stmt->fetch();
            echo "<div class='success'>✅ Table `$table`: {$result['count']} enregistrements</div>";
        } catch (Exception $e) {
            echo "<div class='error'>❌ Table `$table`: {$e->getMessage()}</div>";
        }
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur base de données: " . $e->getMessage() . "</div>";
    echo "<div class='info'>ℹ️ Pour corriger: 
    <ol>
    <li>Démarrer XAMPP MySQL</li>
    <li>Ouvrir phpMyAdmin</li>
    <li>Importer le fichier database/init.sql</li>
    </ol>
    </div>";
}

// Test 3: Vues
echo "<h2>3. Test Vues</h2>";
$viewsPath = __DIR__ . '/controllers/views';
if (is_dir($viewsPath)) {
    echo "<div class='success'>✅ Dossier des vues trouvé</div>";
    $views = ['test.php', 'tableau_bord_simple.php', 'regions_simple.php', 'villes_simple.php', 'besoins_simple.php', 'dons_simple.php'];
    foreach ($views as $view) {
        if (file_exists($viewsPath . '/' . $view)) {
            echo "<div class='success'>✅ Vue $view disponible</div>";
        } else {
            echo "<div class='error'>❌ Vue $view manquante</div>";
        }
    }
} else {
    echo "<div class='error'>❌ Dossier des vues non trouvé</div>";
}

// Test 4: Configuration .htaccess
echo "<h2>4. Test Configuration</h2>";
if (file_exists(__DIR__ . '/.htaccess')) {
    $htaccess = file_get_contents(__DIR__ . '/.htaccess');
    if (strpos($htaccess, 'RewriteBase /exams3/') !== false) {
        echo "<div class='success'>✅ .htaccess correctement configuré</div>";
    } else {
        echo "<div class='error'>❌ .htaccess mal configuré</div>";
    }
} else {
    echo "<div class='error'>❌ Fichier .htaccess manquant</div>";
}

echo "<h2>🔗 Navigation</h2>";
echo "<p><a href='/exams3/'>← Retour à l'application</a></p>";

echo "</body></html>";
?>