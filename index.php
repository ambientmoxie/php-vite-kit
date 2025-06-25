<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get the path from the current URL (e.g., "/contact", "/about")
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove leading/trailing slashes (e.g., "/contact/" → "contact")
$uri = trim($uri, '/');

// If the URI is empty (i.e., homepage), set page to "home"
$page = $uri === '' ? 'home' : $uri;

// Define the base directory where page files are stored
$basePath = __DIR__ . '/pages/';

// Define supported file extensions to check in order
$extensions = ['.php', '/index.php', '.html', '/index.html'];

// Initialize the file variable
$file = null;

// Try all combinations of page + extension until a valid file is found
foreach ($extensions as $ext) {
    $tryPath = $basePath . $page . $ext;
    if (is_file($tryPath)) {
        $file = $tryPath;
        break;
    }
}

// If a matching file was found, load it
if ($file) {
    require $file;
} else {
    // If no matching file, return a 404 status and load the 404 page
    http_response_code(404);
    require $basePath . '404.php';
}
