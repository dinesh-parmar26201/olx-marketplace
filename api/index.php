<?php

// Prepare writable /tmp directories needed for Vercel serverless environment
$tmpDirectories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app/public',
    '/tmp/views',
];

foreach ($tmpDirectories as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Support zero-config demo SQLite database in /tmp if no external DB_HOST is provided
if (!getenv('DB_HOST') && (!getenv('DB_CONNECTION') || getenv('DB_CONNECTION') === 'sqlite')) {
    putenv('DB_CONNECTION=sqlite');
    putenv('DB_DATABASE=/tmp/database.sqlite');
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_ENV['DB_DATABASE'] = '/tmp/database.sqlite';

    $dbFile = '/tmp/database.sqlite';
    if (!file_exists($dbFile)) {
        touch($dbFile);
    }
}

require __DIR__ . '/../public/index.php';
