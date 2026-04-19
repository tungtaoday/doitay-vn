<?php

/**
 * PHP built-in server router.
 *
 * Usage:  php -S 127.0.0.1:8000 -t . server.php
 *
 * Serves static files (CSS/JS/images/fonts) directly from disk with correct
 * MIME types. Everything else goes to index.php (Laravel front controller).
 *
 * Note: `return false` does not work reliably on Windows PHP built-in server,
 * so we manually read and output static files.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$filePath = __DIR__ . $uri;

if ($uri !== '/' && is_file($filePath)) {
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimeMap = [
        'css'   => 'text/css',
        'js'    => 'application/javascript',
        'json'  => 'application/json',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'svg'   => 'image/svg+xml',
        'ico'   => 'image/x-icon',
        'webp'  => 'image/webp',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'eot'   => 'application/vnd.ms-fontobject',
        'map'   => 'application/json',
        'mp4'   => 'video/mp4',
        'webm'  => 'video/webm',
    ];

    if (isset($mimeMap[$ext])) {
        header('Content-Type: ' . $mimeMap[$ext]);
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        return;
    }
}

require __DIR__ . '/index.php';
