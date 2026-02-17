<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🚀 Test de Déploiement BNGRC</h1>";
echo "<hr>";

// Test 1: Vérifier la configuration
echo "<h2>1. Configuration</h2>";
try {
    require_once 'config/database.php';
    echo "✅ Fichier database.php chargé<br>";
    echo "📊 Base: examens3 (définie dans Database class)<br>";
    echo "🔗 Host: localhost<br>";
    echo "👤 User: root<br>";
} catch (Exception $e) {
    echo "❌ Erreur config: " . $e->getMessage() . "<br>";
}

// Test 2: Connexion BDD
echo "<h2>2. Connexion Base de Données</h2>";
try {
    $pdo = Database::getConnection();
    echo "✅ Connexion MySQL réussie<br>";
    
    // Test table regions
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM regions");
    $count = $stmt->fetch()['count'];
    echo "📍 Régions: {$count}<br>";
    
    // Test table villes
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM villes");
    $count = $stmt->fetch()['count'];
    echo "🏘️ Villes: {$count}<br>";
    
    // Test table besoins
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM besoins");
    $count = $stmt->fetch()['count'];
    echo "📦 Besoins: {$count}<br>";
    
    // Test table dons
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM dons");
    $count = $stmt->fetch()['count'];
    echo "🎁 Dons: {$count}<br>";
    
    // Test table parametres (V3)
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM parametres");
        $count = $stmt->fetch()['count'];
        echo "⚙️ Paramètres V3: {$count}<br>";
    } catch (Exception $e) {
        echo "⚠️ Table paramètres manquante (V3 non installé)<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur BDD: " . $e->getMessage() . "<br>";
}

// Test 3: Autoloader
echo "<h2>3. Autoloader</h2>";
try {
    require_once 'vendor/autoload.php';
    echo "✅ Composer autoload OK<br>";
    
    if (class_exists('Flight')) {
        echo "✅ Flight framework chargé<br>";
    } else {
        echo "❌ Flight framework non trouvé<br>";
    }
} catch (Exception $e) {
    echo "❌ Erreur autoload: " . $e->getMessage() . "<br>";
}

// Test 4: Routes principales
echo "<h2>4. URLs de test</h2>";
$base_url = "http://localhost:8000";
echo "<a href='{$base_url}/' target='_blank'>🏠 Accueil</a><br>";
echo "<a href='{$base_url}/regions' target='_blank'>🗺️ Régions</a><br>";
echo "<a href='{$base_url}/villes' target='_blank'>🏘️ Villes</a><br>";
echo "<a href='{$base_url}/besoins' target='_blank'>📦 Besoins</a><br>";
echo "<a href='{$base_url}/dons' target='_blank'>🎁 Dons</a><br>";
echo "<a href='{$base_url}/create' target='_blank'>➕ Créer</a><br>";
echo "<a href='{$base_url}/config-taux' target='_blank'>⚙️ Config V3</a><br>";

echo "<hr>";
echo "<h2>✅ Test de déploiement terminé</h2>";
echo "<p>Serveur PHP: " . phpversion() . "</p>";
echo "<p>Date: " . date('Y-m-d H:i:s') . "</p>";
?>