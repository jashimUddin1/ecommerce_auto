<?php
require __DIR__ . '/../config/init.php';
if (!is_logged_in()) redirect('/public/login.php');
if (!user_has_role('ceo')) redirect('/public/account.php');

$roles = [
    ['name' => 'ceo', 'access' => 'All access with admin role control'],
    ['name' => 'manager', 'access' => 'Limited access with admin role control'],
    ['name' => 'support', 'access' => 'Only user and reseller management'],
    ['name' => 'data-entry', 'access' => 'Only product and category management'],
    ['name' => 'page-editor', 'access' => 'Only blog management'],
    ['name' => 'sales-controller', 'access' => 'Only sales management'],
    ['name' => 'inventory-manager', 'access' => 'Only inventory management'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Role Management</title>
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
                <a class="active" href="/admin/roles.php">Admin Role Management</a>
                <a href="/admin/settings.php">Site Settings</a>
                <a href="/public/logout.php">Logout</a>
            </nav>
        </aside>
        <main class="col-md-9 p-4">
            <h2 class="mb-4">Admin Role Management</h2>
            <div class="table-responsive">
                <table class="table table-bordered bg-white">
                    <thead>
                        <tr><th>Role</th><th>Permission</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roles as $role): ?>
                            <tr>
                                <td><?= htmlspecialchars($role['name']) ?></td>
                                <td><?= htmlspecialchars($role['access']) ?></td>
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
