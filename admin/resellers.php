<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) redirect('/public/login.php');
if (!user_has_role('ceo') && !in_array($_SESSION['user']['role'], ['manager', 'support'], true)) redirect('/public/account.php');

$id = (int)($_GET['id'] ?? 0);
$action = $_GET['action'] ?? 'pending';
if (!$id) redirect('/admin/resellers.php');

$allowed = ['approved', 'rejected', 'pending'];
if (!in_array($action, $allowed, true)) {
    redirect('/admin/resellers.php');
}

$stmt = $pdo->prepare("UPDATE reseller_requests SET status = :status WHERE id = :id");
$stmt->execute(['status' => $action, 'id' => $id]);

redirect('/admin/resellers.php');
