<?php
// cleanup_styles.php - À EXÉCUTER UNE SEULE FOIS
// Ce script est SÉCURISÉ : il crée des backups avant modification

echo "🚀 NETTOYAGE DES STYLES INTERNES - VERSION SÉCURISÉE\n";
echo "============================================\n\n";

// 1. D'abord, mettre à jour styles.css
$cssPath = __DIR__ . '/public/css/styles.css';
$newCss = '<?php
// EMPLACEMENT: public/css/styles.css
// Copie ici le nouveau CSS que je t\'ai fourni
?>';

// 2. Fonction pour supprimer les blocs 
    // Le "s" permet de capturer les retours à la ligne
    // Le "i" rend la recherche insensible à la casse
    $content = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $content);
    
    // Nettoie les lignes vides multiples créées
    $content = preg_replace('/\n\s*\n\s*\n/', "\n\n", $content);
    
    return $content;
}

// 3. Fonction pour traiter un fichier
function processFile($filePath) {
    echo "📁 Traitement de: " . basename($filePath) . "\n";
    
    // Lire le contenu
    $content = file_get_contents($filePath);
    
    // Vérifier s'il y a des styles à supprimer
    if (preg_match('/<style/i', $content)) {
        // Crée un backup
        $backupPath = $filePath . '.backup3';
        copy($filePath, $backupPath);
        echo "   ✅ Backup créé: " . basename($backupPath) . "\n";
        
        // Supprimer les styles
        $newContent = removeStyleBlocks($content);
        
        // Vérifier que le lien CSS est présent
        if (strpos($newContent, 'styles.css') === false) {
            // Ajouter le lien CSS juste avant </head>
            $newContent = preg_replace(
                '/(<\/head>)/i',
                '    <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">' . "\n$1",
                $newContent
            );
            echo "   ✅ Lien CSS ajouté\n";
        }
        
        // Sauvegarder
        file_put_contents($filePath, $newContent);
        echo "   ✅ Fichier mis à jour\n";
    } else {
        echo "   ⏭️  Aucun style à supprimer\n";
    }
    echo "\n";
}

// 4. Fonction pour scanner les dossiers
function scanDirectory($dir) {
    $files = scandir($dir);
    $results = [];
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            $results = array_merge($results, scanDirectory($path));
        } else {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array($extension, ['php', 'html', 'phtml'])) {
                $results[] = $path;
            }
        }
    }
    
    return $results;
}

// 5. LISTE DES FICHIERS À TRAITER
echo "🔍 RECHERCHE DES FICHIERS À NETTOYER...\n\n";

$directories = [
    __DIR__ . '/views',
    __DIR__                  // Pour les fichiers à la racine
];

$allFiles = [];
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $allFiles = array_merge($allFiles, scanDirectory($dir));
    }
}

// Filtres d'exclusion
$excludePatterns = [
    '/backup/',
    '/cleanup_styles\.php$/',
    '/add_css\.php$/',
    '/update_pages\.php$/'
];

$filesToProcess = array_filter($allFiles, function($file) use ($excludePatterns) {
    foreach ($excludePatterns as $pattern) {
        if (preg_match($pattern, $file)) {
            return false;
        }
    }
    return true;
});

echo "📊 " . count($filesToProcess) . " fichiers PHP trouvés\n\n";

// 6. DEMANDER CONFIRMATION
echo "⚠️  ATTENTION !\n";
echo "Ce script va:\n";
echo "  - Créer des fichiers .backup3 pour chaque fichier modifié\n";
echo "  - Supprimer TOUS les blocs <style> dans les fichiers PHP\n";
echo "  - Ajouter le lien vers styles.css si absent\n\n";

echo "Fichiers qui seront modifiés:\n";
foreach ($filesToProcess as $file) {
    echo "  - " . str_replace(__DIR__, '', $file) . "\n";
}
echo "\n";

echo "Voulez-vous continuer? (oui/non): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
if (trim(strtolower($line)) !== 'oui') {
    echo "❌ Annulé.\n";
    exit;
}

echo "\n⏳ TRAITEMENT EN COURS...\n\n";

// 7. TRAITER LES FICHIERS
$success = 0;
$errors = 0;

foreach ($filesToProcess as $file) {
    try {
        processFile($file);
        $success++;
    } catch (Exception $e) {
        echo "❌ ERREUR sur " . basename($file) . ": " . $e->getMessage() . "\n";
        $errors++;
    }
}

// 8. RAPPORT FINAL
echo "\n============================================\n";
echo "📊 RAPPORT FINAL\n";
echo "============================================\n";
echo "✅ Fichiers traités avec succès: $success\n";
if ($errors > 0) {
    echo "❌ Erreurs: $errors\n";
}
echo "\n";
echo "📦 Backups créés: fichiers .backup3\n";
echo "   En cas de problème, renommez .backup3 en .php\n";
echo "\n";
echo "🎨 N'oublie PAS de:\n";
echo "   1. Copier le nouveau CSS dans public/css/styles.css\n";
echo "   2. Tester quelques pages pour vérifier\n";
echo "\n✅ TERMINÉ !\n";