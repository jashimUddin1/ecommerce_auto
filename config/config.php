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

// Determine BASE_URL dynamically. This helps when the project is served from a subdirectory like /ecommerce_auto
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])); // e.g. /ecommerce_auto/public
$base = rtrim($scriptDir, '/');
// If served from the "public" folder, remove the trailing /public to get project base
if (substr($base, -7) === '/public') {
    $base = substr($base, 0, -7);
}
if ($base === '/' || $base === '') {
    $base = '';
}
// BASE_URL will be an empty string for root or '/your_subfolder' when served from a subdirectory
if (!defined('BASE_URL')) {
    define('BASE_URL', $base);
}

function redirect(string $url): void {
    // If a path-like URL (starting with '/'), prefix it with BASE_URL
    if (strpos($url, '/') === 0) {
        $url = BASE_URL . $url;
    }
    header('Location: ' . $url);
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
