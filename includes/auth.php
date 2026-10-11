<?php
require_once __DIR__ . '/../config/config.php';

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect('/public/login.php');
    }
}

function requireRole(array $allowedRoles): void {
    requireLogin();
    $role = currentUserRole();
    if (!in_array($role, $allowedRoles, true)) {
        redirect('/public/index.php');
    }
}

function currentUser(): ?array {
    global $pdo;
    if (!isLoggedIn()) {
        return null;
    }

    $stmt = $pdo->prepare(
        "SELECT u.*, r.name AS role_name
         FROM users u
         JOIN roles r ON r.id = u.role_id
         WHERE u.id = ?"
    );
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}
