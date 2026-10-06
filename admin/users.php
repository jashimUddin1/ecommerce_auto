<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) {
    redirect('/public/login.php');
}

if (!user_has_role('ceo') && !is_admin_access()) {
    redirect('/public/account.php');
}

$totalUsers = (int)($pdo->query("SELECT COUNT(*) FROM users")->fetchColumn());
$totalOrders = (int)($pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn());
$totalProducts = (int)($pdo->query("SELECT COUNT(*) FROM products")->fetchColumn());
$totalRevenue = (float)($pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders")->fetchColumn());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 bg-dark text-white min-vh-100 p-3">
            <h3 class="mb-4">shopAmar</h3>
            <nav class="admin-nav">
                <a class="active" href="/admin/index.php">Dashboard</a>
                <a href="/admin/users.php">User Management</a>
                <a href="/admin/products.php">Product Management</a>
                <a href="/admin/categories.php">Category Management</a>
                <a href="/admin/orders.php">Order Management</a>
                <a href="/admin/coupons.php">Coupon Management</a>
                <a href="/admin/resellers.php">Reseller Management</a>
                <a href="/admin/roles.php">Admin Role Management</a>
                <a href="/admin/settings.php">Site Settings</a>
                <a href="/public/logout.php">Logout</a>
            </nav>
        </aside>
        <main class="col-md-9 p-4">
            <h2 class="mb-4">Admin Dashboard</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6>Total Users</h6>
                            <h3><?= $totalUsers ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6>Total Orders</h6>
                            <h3><?= $totalOrders ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6>Products</h6>
                            <h3><?= $totalProducts ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6>Revenue</h6>
                            <h3><?= pretty_money($totalRevenue) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
