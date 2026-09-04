<?php

$publicPath = __DIR__.'/public';
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/');
$file = realpath($publicPath.$uri);
$publicRoot = realpath($publicPath);

// Serve built assets from public/ when PHP's document root is the project root.
if ($uri !== '/' && $file && $publicRoot && str_starts_with($file, $publicRoot) && is_file($file)) {
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
    ];
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mime = $mimeTypes[$extension] ?? 'application/octet-stream';
    header('Content-Type: '.$mime);
    readfile($file);
    return;
}

chdir($publicPath);

require __DIR__.'/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php';
