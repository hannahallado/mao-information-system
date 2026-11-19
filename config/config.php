<?php
/**
 * App configuration. Values come from .env (never commit the real .env).
 */
$envFile = dirname(__DIR__) . '/.env';
$env = is_file($envFile) ? parse_ini_file($envFile, false, INI_SCANNER_RAW) : [];
$get = static fn (string $key, string $default = '') => $env[$key] ?? (getenv($key) ?: $default);

return [
    'app_name'        => 'Municipal Agriculture Office',
    'debug'           => $get('APP_DEBUG', 'true') === 'true',
    // '' if the web server points at /public; '/mao-system/public' if under htdocs
    'base_url'        => rtrim($get('BASE_URL', ''), '/'),
    'timezone'        => 'Asia/Manila',
    'session_timeout' => 1800,   // seconds of inactivity
    'per_page'        => 15,

    'db' => [
        'host' => $get('DB_HOST', '127.0.0.1'),
        'port' => $get('DB_PORT', '3306'),
        'name' => $get('DB_NAME', 'db_mao_system'),
        'user' => $get('DB_USER', 'root'),
        'pass' => $get('DB_PASS', ''),
    ],
];
