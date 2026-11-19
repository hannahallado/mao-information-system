<?php
declare(strict_types=1);

/**
 * Shared bootstrap: constants, helpers, config, autoloader, error handling.
 * Used by public/index.php and tests/route_check.php.
 */
defined('BASE_PATH') || define('BASE_PATH', dirname(__DIR__));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

require APP_PATH . '/core/Helpers.php';
$GLOBALS['__config'] = require BASE_PATH . '/config/config.php';
date_default_timezone_set(config('timezone', 'Asia/Manila'));

spl_autoload_register(function (string $class): void {
    foreach (['/app/core', '/app/controllers', '/app/models', '/app/services', '/config'] as $dir) {
        $file = BASE_PATH . $dir . '/' . $class . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

if (config('debug')) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}

set_exception_handler(function (Throwable $e): void {
    error_log('[' . date('c') . '] ' . $e . PHP_EOL, 3, BASE_PATH . '/storage/logs/app.log');
    http_response_code(500);
    if (config('debug')) {
        echo '<pre>' . e((string) $e) . '</pre>';
    } else {
        echo Controller::render('errors/error', ['code' => 500, 'message' => 'Something went wrong.', 'title' => 'Error'], 'auth');
    }
});
