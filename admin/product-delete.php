<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) redirect('/public/login.php');
if (!user_has_role('ceo') && !in_array($_SESSION['user']['role'], ['manager', 'data-entry'], true)) redirect('/public/account.php');

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('/admin/products.php');

try {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['success'] = 'Product deleted successfully!';
} catch (Exception $e) {
    $_SESSION['error'] = 'Error deleting product.';
}

redirect('/admin/products.php');
