<?php
/**
 * Visit one GET route as a fake logged-in user (no database needed) and report the result.
 *   php tests/route_check.php /farmers [technician|agriculturist]
 * Output: "<path> => 200" | "<path> => 403/404" | "<path> => redirect <url>"
 */
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
require APP_PATH . '/bootstrap.php';

$path = $argv[1] ?? '/home';
$role = $argv[2] ?? 'agriculturist';

$_SESSION = ['user' => ['id' => 1, 'full_name' => 'Test User', 'username' => 'test', 'role' => $role, 'sector' => 'all']];
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI']    = $path;

ob_start();
register_shutdown_function(function () use ($path) {
    $html = (string) ob_get_clean();
    foreach (headers_list() as $h) {
        if (stripos($h, 'Location:') === 0) {
            echo "$path => redirect " . trim(substr($h, 9)) . PHP_EOL;
            return;
        }
    }
    if (preg_match('#<h1 class="display-4"[^>]*>(\d+)</h1>#', $html, $m)) {
        echo "$path => {$m[1]}" . PHP_EOL;
        return;
    }
    $ok = str_contains($html, '</html>') && !str_contains($html, 'Warning:') && !str_contains($html, 'Fatal error');
    echo "$path => " . ($ok ? '200' : 'PHP ERROR') . PHP_EOL;
});

$router = new Router('');
require BASE_PATH . '/config/routes.php';
$router->dispatch('GET', $path);
