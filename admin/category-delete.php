<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) redirect('/public/login.php');
if (!user_has_role('ceo') && !in_array($_SESSION['user']['role'], ['manager', 'data-entry'], true)) redirect('/public/account.php');

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('/admin/categories.php');

try {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['success'] = 'Category deleted successfully!';
} catch (Exception $e) {
    $_SESSION['error'] = 'Error deleting category.';
}

redirect('/admin/categories.php');
