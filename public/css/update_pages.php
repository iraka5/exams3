<?php
// update_pages.php - À exécuter UNE SEULE FOIS

function removeStyleBlocks($content) {
    // Supprime tous les blocs <style>...</style>
    $content = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $content);
    
    // Ajoute le lien CSS si pas déjà présent
    if (strpos($content, 'styles.css') === false) {
        $content = preg_replace(
            '/(<\/head>)/i',
            '    <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">' . "\n$1",
            $content
        );
    }
    
    return $content;
}

function processDirectory($dir) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            processDirectory($path);
        } else {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array($extension, ['php', 'html', 'phtml'])) {
                $content = file_get_contents($path);
                
                // Vérifie s'il y a des styles à supprimer
                if (preg_match('/<style/i', $content) || strpos($content, 'styles.css') === false) {
                    // Backup
                    copy($path, $path . '.backup2');
                    
                    $newContent = removeStyleBlocks($content);
                    file_put_contents($path, $newContent);
                    echo "✓ Modifié: $path\n";
                }
            }
        }
    }
}

echo "🔍 Mise à jour des fichiers...\n\n";
processDirectory(__DIR__ . '/views'); // Ajustez le chemin
processDirectory(__DIR__); // Pour les fichiers à la racine
echo "\n✅ Terminé! Vérifiez les fichiers .backup2 en cas de problème.\n";