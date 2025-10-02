<?php

/**
 * This file contains two classes that handle the creation of a custom session storage system.
 * 
 * Goal: Avoid erratic session behavior.
 * Issue: Many applications are hosted on "clusters" (groups of servers working simultaneously). 
 * This common setup manages network traffic and workloads by switching between servers — a process called "load balancing". 
 * During these silent server switches, the "/tmp" directory that stores sessions may change 
 * (because /tmp is not synchronized between servers), causing the current session to be lost.
 * 
 * Solution: Create a custom directory to store all session files, and implement custom session handling logic to manage them.
 * 
 * 1. `CustomSession` handles the implementation of the personal session storage logic — based on https://dnada.fr/bug/perte_session_ovh.html
 * 2. `SessionHelper` takes care of standard session configuration while using the new custom session system.
 */

class CustomSession implements SessionHandlerInterface
{
    private $path;

    public function open($path, $sessionName): bool
    {

        $this->path = $path . '/sess_';

        if (! is_dir($path)) {
            mkdir($path, 0777);
            return is_dir($path);
        }
        return true;
    }

    private function id_to_filename($id)
    {
        // protège le numéro de session pour utilisation en nom de fichier
        return preg_replace('{\W}', '-', $id) ? $this->path . $id : false;
    }

    #[\ReturnTypeWillChange]
    public function read($id)
    {
        $filename = $this->id_to_filename($id);
        return ($filename && file_exists($filename)) ? (string)file_get_contents($filename) : '';
    }

    public function write($id, $data): bool
    {
        $filename = $this->id_to_filename($id);
        return $filename ? file_put_contents($filename, $data) !== false : false;
    }

    public function destroy($id): bool
    {
        $filename = $this->id_to_filename($id);
        if (! $filename || ! file_exists($filename))
            return false;
        unlink($filename);
        return true;
    }

    #[\ReturnTypeWillChange]
    public function gc($maxlifetime)
    {
        $deadline = time() - $maxlifetime;
        foreach (glob($this->path . '*') as $file)
            if (filemtime($file) < $deadline)
                unlink($file);
        return true;
    }

    public function close(): bool
    {
        return true;
    }
}

class SessionHelper
{
    public static function start(): void
    {

        // Start session only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            // modifie le mécanisme de session PHP
            session_set_save_handler(new CustomSession(), true);

            session_save_path(__DIR__ . "/../../sessions");

            // Set session lifetime to 7 days (604800 seconds)
            ini_set('session.gc_maxlifetime', 604800);

            // Set session cookie parameters (persist across browser restarts)
            session_set_cookie_params([
                'lifetime' => 604800,
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_start();
        }
    }

    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            // Clear all session variables
            $_SESSION = [];

            // Check if PHP is using cookies to store the session ID
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();

                // Remove the session cookie by setting it to expire in the past
                setcookie(
                    session_name(),        // Cookie name (e.g. "PHPSESSID")
                    '',                    // Empty value
                    time() - 42000,        // Expired
                    $params["path"],       // Match original path
                    $params["domain"],     // Match original domain
                    $params["secure"],     // Match original secure flag
                    $params["httponly"]    // Match original httponly flag
                );
            }

            // Destroy the session
            session_destroy();
        }
    }
}
