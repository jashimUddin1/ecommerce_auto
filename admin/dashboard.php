<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'ceo', 'manager', 'support', 'data-entry', 'sales-controller', 'inventory-manager']);

global $pdo;

$totalUsers = (int) $pdo->query('SELECT COUNT(*) AS total FROM users')->fetch()['total'];
$totalProducts = (int) $pdo->query('SELECT COUNT(*) AS total FROM products')->fetch()['total'];
$totalOrders = (int) $pdo->query('SELECT COUNT(*) AS total FROM orders')->fetch()['total'];
$pendingOrders = (int) $pdo->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'pending'")->fetch()['total'];
$totalRevenue = (float) $pdo->query('SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders')->fetch()['total'];

$user = currentUser();
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Admin Dashboard</h2>
            <p class="text-muted">Welcome, <?= htmlspecialchars($user['name']) ?></p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Users</h6>
                    <h3><?= $totalUsers ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Products</h6>
                    <h3><?= $totalProducts ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Orders</h6>
                    <h3><?= $totalOrders ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Revenue</h6>
                    <h3>$<?= number_format($totalRevenue, 2) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Pending Orders</h5>
                    <h2><?= $pendingOrders ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Management Links</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="/admin/users.php" class="btn btn-primary btn-sm">Users</a>
                        <a href="/admin/products.php" class="btn btn-primary btn-sm">Products</a>
                        <a href="/admin/categories.php" class="btn btn-primary btn-sm">Categories</a>
                        <a href="/admin/orders.php" class="btn btn-primary btn-sm">Orders</a>
                        <a href="/admin/coupons.php" class="btn btn-primary btn-sm">Coupons</a>
                        <a href="/admin/roles.php" class="btn btn-primary btn-sm">Roles</a>
                        <a href="/admin/settings.php" class="btn btn-primary btn-sm">Settings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
