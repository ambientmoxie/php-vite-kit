<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Serve static files (JS, CSS, images, etc.) directly if they exist
$staticPath = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (is_file($staticPath)) {
    $mime = mime_content_type($staticPath);
    header("Content-Type: $mime");
    readfile($staticPath);
    exit;
}

// Route API requests (e.g. /api/check-password.php)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (str_starts_with($uri, '/api/')) {
    $apiScript = realpath(__DIR__ . '/../' . ltrim($uri, '/'));
    $allowedDir = realpath(__DIR__ . '/../api/');

    // Make sure it's a valid file inside the /api directory
    if ($apiScript && str_starts_with($apiScript, $allowedDir) && is_file($apiScript)) {
        require $apiScript;
        exit;
    }

    // If not valid, return 404
    http_response_code(404);
    echo "API endpoint not found.";
    exit;
}

// Route dynamic PHP pages from ../pages/
$uri = trim($uri, '/');
$page = $uri === '' ? 'home' : $uri;

$basePath = dirname(__DIR__) . '/pages/';
$extensions = ['.php', '/index.php', '.html', '/index.html'];

$file = null;
foreach ($extensions as $ext) {
    $tryPath = $basePath . $page . $ext;
    if (is_file($tryPath)) {
        $file = $tryPath;
        break;
    }
}

// Load the resolved page or fallback to 404
if ($file) {
    require $file;
} else {
    http_response_code(404);
    require $basePath . '404.php';
}
