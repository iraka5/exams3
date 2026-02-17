<?php
// index.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Servir les fichiers statiques du dossier public
$publicPath = __DIR__ . '/public';
if (preg_match('#^/public/(.+)$#', $_SERVER['REQUEST_URI'], $matches)) {
    $file = realpath($publicPath . '/' . $matches[1]);
    if ($file && strpos($file, $publicPath) === 0 && is_file($file)) {
        $mime = mime_content_type($file);
        header('Content-Type: ' . $mime);
        readfile($file);
        exit;
    }
}

// Charger Composer (Flight, etc.)
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/routes.php';
?>
