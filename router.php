<?php

// Example: user visits http://localhost:8888/build/bundle/main.js
// This line builds the full file path on disk from the URL path:
// $requested = "/Users/me/project/build/bundle/main.js"
$requested = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// If the file exists and is a regular file (not a folder), serve it directly.
// This applies to assets like JS, CSS, fonts, images, etc.
// Example: "/build/bundle/main.js" → served directly
if (file_exists($requested) && is_file($requested)) {
    return false;
}

// Otherwise, treat the request as a page and pass it to index.php
// Example: "/contact" → resolved by index.php to "pages/contact.php"
// Pages are not public files — they’re handled internally by index.php
require __DIR__ . '/index.php';