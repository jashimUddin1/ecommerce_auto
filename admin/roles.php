<?php
require __DIR__ . '/../config/init.php';
if (!is_logged_in()) redirect('/public/login.php');
if (!user_has_role('ceo')) redirect('/public/account.php');

$settings = $pdo->query("SELECT * FROM settings ORDER BY id DESC LIMIT 1")->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 bg-dark text-white min-vh-100 p-3">
            <h3 class="mb-4">shopAmar</h3>
            <nav class="admin-nav">
                <a href="/admin/index.php">Dashboard</a>
                <a href="/admin/users.php">User Management</a>
                <a href="/admin/products.php">Product Management</a>
                <a href="/admin/categories.php">Category Management</a>
                <a href="/admin/orders.php">Order Management</a>
                <a href="/admin/coupons.php">Coupon Management</a>
                <a href="/admin/resellers.php">Reseller Management</a>
                <a href="/admin/roles.php">Admin Role Management</a>
                <a class="active" href="/admin/settings.php">Site Settings</a>
                <a href="/public/logout.php">Logout</a>
            </nav>
        </aside>
        <main class="col-md-9 p-4">
            <h2 class="mb-4">Site Settings</h2>
            <div class="card">
                <div class="card-body">
                    <p><strong>Site Name:</strong> <?= htmlspecialchars($settings['site_name'] ?? 'shopAmar') ?></p>
                    <p><strong>Currency:</strong> <?= htmlspecialchars($settings['currency'] ?? 'BDT') ?></p>
                    <p><strong>Language:</strong> <?= htmlspecialchars($settings['default_language'] ?? 'en') ?></p>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
