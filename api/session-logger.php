<?php

/**
 * The following code stands as a session logger as well as a cheat sheet, allowing devs to monitor
 * session activity and state, and to display some basic session manipulation methods and functions.
 * The first idea for managing sessions was to create a custom directory to store all session
 * files, but it was found that this method also comes with the tedious necessity of adding custom
 * configuration depending on the hosting provider. For ease of use, sessions will be stored
 * in the default session directory assigned by the remote server.
 * 
 * OVH hosting provider: 
 * Add thhe following line to ovh.config for overriding session_save_path:
 * app.engine.flags=noforcetmp
 */

// Show errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load the session helper
require_once __DIR__ . '/../php/helpers/session-helper.php';

// Start the session (sets lifetime, cookies, etc.)
SessionHelper::start();

// Session info
$sessionPath = ini_get('session.save_path');
$sessionId = session_id();
$sessionFile = $sessionPath . '/sess_' . $sessionId;
$maxLifetime = (int) ini_get('session.gc_maxlifetime');
$expiresAt = time() + $maxLifetime;

echo "Session path: $sessionPath<br>";
echo "Writable? " . (is_writable($sessionPath) ? 'Yes ✅' : 'No ❌') . "<br>";
echo "Session ID: $sessionId<br>";
echo "Expected session file: $sessionFile<br>";
echo "Session file exists? " . (file_exists($sessionFile) ? 'Yes ✅' : 'No ❌') . "<br>";
echo "Session lifetime: {$maxLifetime} seconds<br>";
echo "Session will expire at: " . date('Y-m-d H:i:s', $expiresAt) . "<br>";

// Session test logic
$_SESSION['test'] = $_SESSION['test'] ?? 0;
$_SESSION['test']++;
echo "Session counter: " . $_SESSION['test'];

