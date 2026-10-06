<?php
session_start();

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = in_array($_GET['lang'], ['en', 'bn'], true) ? $_GET['lang'] : 'en';
}

require __DIR__ . '/database.php';
require __DIR__ . '/lang.php';

function redirect($path) {
    header('Location: ' . $path);
    exit;
}

function current_user() {
    return $_SESSION['user'] ?? null;
}

function is_logged_in() {
    return isset($_SESSION['user']);
}

function user_has_role($role) {
    $user = current_user();
    return $user && ($user['role'] === $role || $user['role'] === 'ceo');
}

function is_admin() {
    $user = current_user();
    return $user && in_array($user['role'], ['ceo', 'manager', 'support', 'data-entry', 'page-editor', 'sales-controller', 'inventory-manager'], true);
}

function pretty_money($amount) {
    return '৳ ' . number_format((float) $amount, 2);
}
