<?php
require __DIR__ . '/../config/init.php';
if (!is_logged_in()) redirect('/public/login.php');
if (!user_has_role('ceo') && !in_array($_SESSION['user']['role'], ['manager', 'sales-controller'], true)) redirect('/public/account.php');

$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
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
                <a class="active" href="/admin/orders.php">Order Management</a>
                <a href="/admin/coupons.php">Coupon Management</a>
                <a href="/admin/resellers.php">Reseller Management</a>
                <a href="/admin/roles.php">Admin Role Management</a>
                <a href="/admin/settings.php">Site Settings</a>
                <a href="/public/logout.php">Logout</a>
            </nav>
        </aside>
        <main class="col-md-9 p-4">
            <h2 class="mb-4">Order Management</h2>
            <div class="table-responsive">
                <table class="table table-bordered bg-white">
                    <thead>
                        <tr><th>ID</th><th>User ID</th><th>Total</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?= (int)$order['id'] ?></td>
                                <td><?= (int)$order['user_id'] ?></td>
                                <td><?= pretty_money($order['total_amount']) ?></td>
                                <td><?= htmlspecialchars($order['status']) ?></td>
                                <td><?= htmlspecialchars($order['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
</body>
</html>
