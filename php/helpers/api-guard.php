<?php

// Prevent direct access to api scripts.
class ApiGuard
{
    public static function protect(): void
    {
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            ($_SERVER['HTTP_HX_REQUEST'] ?? '') !== 'true'
        ) {
            http_response_code(403);
            exit('Direct access denied.');
        }
    }

    public static function redirect(): void
    {
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            ($_SERVER['HTTP_HX_REQUEST'] ?? '') !== 'true'
        ) {
            header('Location: /');
            exit;
        }
    }
}
