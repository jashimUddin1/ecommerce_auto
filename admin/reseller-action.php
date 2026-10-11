<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) redirect('/public/login.php');
if (!user_has_role('ceo') && !in_array($_SESSION['user']['role'], ['manager'], true)) redirect('/public/account.php');

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('/admin/coupons.php');

try {
    $stmt = $pdo->prepare("DELETE FROM coupons WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['success'] = 'Coupon deleted successfully.';
} catch (Exception $e) {
    $_SESSION['error'] = 'Could not delete coupon.';
}

redirect('/admin/coupons.php');
