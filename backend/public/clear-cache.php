<?php
// TEMPORARY file — DELETE after running once!
// Access via: https://your-domain.com/abby/clear-cache.php

// Basic security check
if (!isset($_GET['key']) || $_GET['key'] !== 'abby2024clear') {
    die('Unauthorized');
}

$base = dirname(__DIR__); // points to project root (one level above public/)

// Clear compiled views
$viewsPath = $base . '/storage/framework/views/';
$files = glob($viewsPath . '*.php');
$count = 0;
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
        $count++;
    }
}

// Clear cached config/routes if they exist
@unlink($base . '/bootstrap/cache/config.php');
@unlink($base . '/bootstrap/cache/routes-v7.php');
@unlink($base . '/bootstrap/cache/services.php');

echo "<h2>Done!</h2>";
echo "<p>Deleted <strong>{$count}</strong> compiled Blade view files.</p>";
echo "<p style='color:red;'><strong>DELETE this file (clear-cache.php) from your server immediately after this!</strong></p>";
