<?php
/**
 * CLI helper to create a login (passwords are hashed with bcrypt).
 *   php database/create_user.php <username> "<Full Name>" <password> <role> [sector]
 *   php database/create_user.php admin "MAO Agriculturist" secret123 agriculturist all
 *   php database/create_user.php tech1 "Crop Technician" secret123 technician crops
 */
if (PHP_SAPI !== 'cli') { exit('CLI only.'); }

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
require APP_PATH . '/core/Helpers.php';
$GLOBALS['__config'] = require BASE_PATH . '/config/config.php';
require BASE_PATH . '/config/Database.php';

[$script, $username, $fullName, $password, $role] = array_pad($argv, 5, null);
$sector = $argv[5] ?? 'all';

if (!$username || !$fullName || !$password || !in_array($role, ['technician', 'agriculturist'], true)) {
    exit("Usage: php database/create_user.php <username> \"<Full Name>\" <password> <technician|agriculturist> [sector]\n");
}

$now = date('Y-m-d H:i:s');
Database::connect()->prepare(
    'INSERT INTO users (full_name, username, password_hash, role, sector, is_active, created_at, updated_at)
     VALUES (?, ?, ?, ?, ?, 1, ?, ?)'
)->execute([$fullName, $username, password_hash($password, PASSWORD_BCRYPT), $role, $sector, $now, $now]);

echo "User '$username' created.\n";
