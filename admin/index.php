<?php
require __DIR__ . '/../config/init.php';
if (!is_logged_in()) redirect('/public/login.php');
if (!user_has_role('ceo') && !in_array($_SESSION['user']['role'], ['manager', 'support'], true)) redirect('/public/account.php');

$resellers = $pdo->query("SELECT rr.*, u.name, u.email FROM reseller_requests rr LEFT JOIN users u ON u.id = rr.user_id ORDER BY rr.created_at DESC")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <h1>Admin Dashboard</h1>
    <!-- Dashboard content -->
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
