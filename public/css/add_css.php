<?php
// add_css.php - À exécuter UNE SEULE FOIS

function addCssLink($content) {
    // Cherche la balise </head>
    $pattern = '/(<\/head>)/i';
    $replacement = '    <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">' . "\n$1";
    return preg_replace($pattern, $replacement, $content);
}

function processDirectory($dir) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || $file === 'add_css.php') continue;
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            processDirectory($path);
        } else {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array($extension, ['php', 'html', 'phtml'])) {
                $content = file_get_contents($path);
                
                // Vérifie si le lien CSS est déjà présent
                if (strpos($content, 'styles.css') === false) {
                    // Crée un backup
                    copy($path, $path . '.backup');
                    
                    $newContent = addCssLink($content);
                    file_put_contents($path, $newContent);
                    echo "✓ CSS ajouté à: $path (backup créé)\n";
                } else {
                    echo "→ Déjà présent: $path\n";
                }
            }
        }
    }
}

echo "🔍 Ajout du lien CSS à tous les fichiers...\n\n";
processDirectory(__DIR__);
echo "\n✅ Terminé! Les backups sont sauvegardés avec .backup\n";