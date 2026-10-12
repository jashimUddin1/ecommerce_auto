<?php
session_start();

const DB_HOST = 'localhost';
const DB_NAME = 'shopamar';
const DB_USER = 'root';
const DB_PASS = '';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Determine BASE_URL robustly. This ensures BASE_URL becomes the project root (e.g. /ecommerce_auto)
// regardless of whether the current script is in /public, /admin, /user, or /reseller folders.
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ''); // e.g. /ecommerce_auto/public/index.php
$scriptDir = dirname($scriptName); // e.g. /ecommerce_auto/public or /ecommerce_auto/admin

// Remove common subfolders to get project root
$base = preg_replace('#/(public|admin|user|reseller)(/.*)?$#i', '', $scriptDir);

// normalize root
if ($base === '/' || $base === '.' || $base === '') {
    $base = '';
}

if (!defined('BASE_URL')) {
    define('BASE_URL', $base);
}

function redirect(string $url): void {
    // If full URL provided, redirect as-is
    if (preg_match('#^https?://#i', $url)) {
        header('Location: ' . $url);
        exit;
    }

    // If path-like URL (starting with '/'), prefix with BASE_URL
    if (strpos($url, '/') === 0) {
        $location = BASE_URL . $url;
    } else {
        // relative path provided, append to BASE_URL with a slash
        $location = rtrim(BASE_URL, '/') . '/' . ltrim($url, '/');
    }

    header('Location: ' . $location);
    exit;
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function currentUserRole(): string {
    return $_SESSION['role'] ?? 'visitor';
}

function currentUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
}
