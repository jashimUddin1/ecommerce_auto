<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupon Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 bg-dark text-white min-vh-100 p-3">
            <h3 class="mb-4">shopAmar</h3>
            <nav class="admin-nav">
                <a href="<?= BASE_URL ?>/admin/index.php">Dashboard</a>
                <a href="<?= BASE_URL ?>/admin/users.php">User Management</a>
                <a href="<?= BASE_URL ?>/admin/products.php">Product Management</a>
                <a href="<?= BASE_URL ?>/admin/categories.php">Category Management</a>
                <a href="<?= BASE_URL ?>/admin/orders.php">Order Management</a>
                <a class="active" href="<?= BASE_URL ?>/admin/coupons.php">Coupon Management</a>
                <a href="<?= BASE_URL ?>/admin/resellers.php">Reseller Management</a>
                <a href="<?= BASE_URL ?>/admin/roles.php">Admin Role Management</a>
                <a href="<?= BASE_URL ?>/admin/settings.php">Site Settings</a>
                <a href="<?= BASE_URL ?>/public/logout.php">Logout</a>
            </nav>
        </aside>
        <main class="col-md-9 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Coupon Management</h2>
                <a href="<?= BASE_URL ?>/admin/coupon-add.php" class="btn btn-primary">+ Add Coupon</a>
            </div>
